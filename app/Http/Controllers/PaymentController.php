<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function createVa(Request $request)
    {
        // Validate the request - max 1 ticket per user
        $validated = $request->validate([
            'event_id' => 'required|exists:events,event_id',
            'ticket_id' => 'required|exists:tickets,ticket_id',
            'quantity' => 'required|integer|min:1|max:1',
            'attendee_emails' => 'required|array|max:1',
            'attendee_emails.*' => 'required|email',
        ]);

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
                $payment->payment_date = now();
                $payment->status = $responseData['data']['status'] ?? 'pending';
                $payment->external_id = $externalId;
                $payment->va_number = $responseData['data']['va_number'] ?? null;
                $payment->payment_url = $responseData['data']['payment_url'] ?? null;
                $payment->expired_at = $responseData['data']['expired_at'] ?? null;
                $payment->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Virtual account created successfully',
                    'data' => [
                        'va_number' => $responseData['data']['va_number'],
                        'amount' => $totalAmount,
                        'expired_at' => $responseData['data']['expired_at'],
                        'payment_url' => $responseData['data']['payment_url'],
                        'event_name' => $event->event_name,
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
                        // Update payment to active and generate QR code
                        if ($payment->status !== 'active') {
                            $payment->status = 'active';
                            $payment->payment_date = now();
                            $payment->qr_code = $this->generateQrCode($payment);
                            $payment->save();

                            \Log::info('Payment activated via status check', [
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

