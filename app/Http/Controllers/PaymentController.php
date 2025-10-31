<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\TicketHolder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function createVa(Request $request)
    {
        // Validate the request - allow multiple quantities and emails
        $validated = $request->validate([
            'event_id' => 'required|exists:events,event_id',
            'ticket_id' => 'required|exists:tickets,ticket_id',
            'quantity' => 'required|integer|min:1',
            'attendee_emails' => 'required|array',
            'attendee_emails.*' => 'required|email',
        ]);

        if (count($validated['attendee_emails']) !== (int) $validated['quantity']) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jumlah email attendee harus sama dengan quantity'
            ], 422);
        }

        // Get event and ticket info
        $event = Event::findOrFail($validated['event_id']);
        $ticket = Ticket::findOrFail($validated['ticket_id']);

        // Get authenticated user or use first email
        $user = auth()->user();
        $customerName = $user ? $user->name : explode('@', $validated['attendee_emails'][0])[0];
        $customerEmail = $validated['attendee_emails'][0];

        // Calculate total amount
        $totalAmount = $ticket->price * $validated['quantity'];

        // Generate external ID
        $externalId = 'EVENT-' . $event->event_id . '-TICKET-' . $validated['ticket_id'] . '-' . time();

        // Get API URL and key from env
        $apiUrl = 'https://payment-dummy.doovera.com/api/v1/virtual-account/create';
        $apiKey = '4zww8RNj9koxcwUigghYeYaWCCZGqaYf';

        // Prepare payload for VA API
        $payload = [
            'external_id' => $externalId,
            'amount' => $totalAmount,
            'customer_name' => $customerName,
            'customer_email' => $customerEmail,
            'description' => "Payment for {$event->event_name} - {$ticket->name} (Qty: {$validated['quantity']})",
            'expired_duration' => 24, // 24 hours
            'metadata' => [
                'event_id' => $event->event_id,
                'event_name' => $event->event_name,
                'ticket_id' => $validated['ticket_id'],
                'ticket_name' => $ticket->name,
                'quantity' => $validated['quantity'],
                'total_amount' => $totalAmount,
                'attendee_emails' => $validated['attendee_emails'],
            ],
        ];

        // Call VA API
        try {
            $response = Http::withOptions([
                'verify' => false
            ])->withHeaders([
                'X-API-Key' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post($apiUrl, $payload);

            if ($response->successful()) {
                $responseData = $response->json();

                // Get existing attendee for logged-in user, or create a new one only if user is logged in
                $attendeeId = null;
                if ($user) {
                    // Find or create attendee profile for this user
                    $attendee = Attendee::firstOrCreate(
                        ['user_id' => $user->id]
                    );
                    $attendeeId = $attendee->id;
                }

                // Save payment to database (main payment record)
                $payment = new \App\Models\Payment();
                $payment->attendee_id = $attendeeId; // Can be null if user not logged in
                $payment->event_id = $event->event_id;
                $payment->user_id = $user ? $user->id : null;
                $payment->ticket_id = $validated['ticket_id'];
                $payment->quantity = $validated['quantity'];
                $payment->method = 'virtual_account';
                $payment->amount = $totalAmount;
                $payment->payment_date = null;
                $payment->status = 'pending';
                $payment->external_id = $externalId;
                $payment->va_number = $responseData['data']['va_number'] ?? null;
                $payment->payment_url = $responseData['data']['payment_url'] ?? null;
                $payment->expired_at = $responseData['data']['expired_at'] ?? null;
                $payment->save();

                // Cache attendee emails for later fulfillment (webhook or status check)
                // Keyed by external_id and VA number for easy retrieval
                $cachePayload = [
                    'attendee_emails' => $validated['attendee_emails'],
                    'quantity' => (int) $validated['quantity'],
                    'event_id' => $event->event_id,
                    'ticket_id' => (int) $validated['ticket_id'],
                    'user_id' => $user ? $user->id : null,
                ];
                Cache::put('payment_meta:' . $externalId, $cachePayload, now()->addHours(48));
                if ($payment->va_number) {
                    Cache::put('payment_meta_va:' . $payment->va_number, $cachePayload, now()->addHours(48));
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Virtual account created successfully',
                    'data' => [
                        'va_number' => $responseData['data']['va_number'],
                        'amount' => $totalAmount,
                        'expired_at' => $responseData['data']['expired_at'],
                        'payment_url' => $responseData['data']['payment_url'],
                        'event_name' => $event->event_name,
                        'external_id' => $externalId,
                        'quantity' => (int) $validated['quantity'],
                        'attendee_emails' => $validated['attendee_emails'],
                    ]
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to create virtual account',
                    'error' => $response->json()
                ], $response->status());
            }
        } catch (\Exception $e) {
            \Log::error('Payment VA Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating virtual account',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkAttendees(Request $request)
    {
        $validated = $request->validate([
            'attendee_emails' => 'required|array|min:1',
            'attendee_emails.*' => 'required|email',
        ]);

        $emails = $validated['attendee_emails'];
        $missing = [];
        foreach ($emails as $email) {
            if (! User::where('email', $email)->exists()) {
                $missing[] = $email;
            }
        }

        return response()->json([
            'status' => 'success',
            'missing' => $missing,
        ]);
    }

    /**
     * Check VA payment status from gateway
     */
    public function checkStatus(Request $request, $vaNumber)
    {
        try {
            $apiUrl = 'https://payment-dummy.doovera.com/api/v1/virtual-account/' . $vaNumber . '/status';
            $apiKey = '4zww8RNj9koxcwUigghYeYaWCCZGqaYf';

            $response = Http::withOptions([
                'verify' => false
            ])->withHeaders([
                'X-API-Key' => $apiKey,
                'Content-Type' => 'application/json',
            ])->get($apiUrl);

            if ($response->successful()) {
                $responseData = $response->json();

                // Find payment by VA number
                $payment = Payment::where('va_number', $vaNumber)->first();

                if ($payment) {
                    // Check if status changed to paid
                    if ($responseData['data']['status'] === 'paid') {
                        if ($payment->status !== 'paid') {
                            $payment->status = 'paid';
                            $payment->payment_date = now();
                            $payment->save();

                            // Retrieve cached attendee emails; fallback to buyer only if cache missing
                            $meta = Cache::get('payment_meta_va:' . $vaNumber) ?? Cache::get('payment_meta:' . ($payment->external_id ?? '')); 
                            $emails = is_array($meta['attendee_emails'] ?? null) ? $meta['attendee_emails'] : [];
                            if (empty($emails) && $payment->user_id) {
                                $buyer = User::find($payment->user_id);
                                if ($buyer) {
                                    $emails = array_fill(0, max(1, (int) $payment->quantity), $buyer->email);
                                }
                            }

                            // Prevent duplicate creation if already created for this payment
                            $existingCount = \App\Models\TicketHolder::where('qr_code', 'like', 'QR-' . $payment->payment_id . '-%')->count();
                            if ($existingCount === 0) {
                                foreach ($emails as $index => $email) {
                                    $user = User::where('email', $email)->first();
                                    $generatedPassword = null;
                                    if (! $user) {
                                        $generatedPassword = Str::random(10);
                                        $user = User::create([
                                            'name' => explode('@', $email)[0],
                                            'username' => explode('@', $email)[0] . '_' . Str::lower(Str::random(4)),
                                            'email' => $email,
                                            'password' => bcrypt($generatedPassword),
                                            'role' => 'attendee',
                                        ]);
                                        try {
                                            Mail::to($email)->send(new \App\Mail\AttendeeAccountCreated($user, $generatedPassword));
                                        } catch (\Throwable $e) {
                                            \Log::error('Failed to send attendee account email', ['email' => $email, 'error' => $e->getMessage()]);
                                        }
                                    }
                                    $attendee = Attendee::firstOrCreate(['user_id' => $user->id]);
                                    TicketHolder::create([
                                        'attendee_id' => $attendee->id,
                                        'event_id' => $payment->event_id,
                                        'qr_code' => $this->generateQrCode($payment),
                                        'status' => 'active',
                                    ]);
                                }
                            }

                            \Log::info('Payment marked paid and ticket holders created via status check', [
                                'payment_id' => $payment->payment_id,
                                'va_number' => $vaNumber
                            ]);
                        }
                    }

                    return response()->json([
                        'status' => 'success',
                        'data' => [
                            'va_number' => $responseData['data']['va_number'],
                            'payment_status' => $payment->status,
                            'gateway_status' => $responseData['data']['status'],
                            'is_paid' => $responseData['data']['status'] === 'paid',
                            'amount' => $responseData['data']['amount'],
                        ]
                    ]);
                }

                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment not found'
                ], 404);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to check status'
            ], $response->status());

        } catch (\Exception $e) {
            \Log::error('Check VA status error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while checking status'
            ], 500);
        }
    }

    /**
     * Generate unique QR code for payment
     */
    private function generateQrCode(Payment $payment)
    {
        $random = strtoupper(\Illuminate\Support\Str::random(8));
        return "QR-{$payment->payment_id}-{$random}";
    }
}

