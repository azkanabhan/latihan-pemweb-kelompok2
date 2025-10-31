<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use App\Models\Attendee;
use App\Models\TicketHolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class WebhookController extends Controller
{
    /**
     * Handle webhook from payment gateway
     */
    public function handle(Request $request)
    {
        try {
            // Verify signature
            $signature = $request->header('X-Webhook-Signature');
            $payload = $request->getContent();
            
            if (!$this->verifySignature($payload, $signature)) {
                Log::error('Invalid webhook signature', [
                    'signature' => $signature,
                    'payload' => $payload
                ]);
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid signature'
                ], 401);
            }

            $data = $request->json()->all();
            
            // Log webhook received
            Log::info('Webhook received', [
                'event' => $data['event'] ?? 'unknown',
                'data' => $data['data'] ?? []
            ]);

            switch ($data['event'] ?? '') {
                case 'payment.success':
                    return $this->handlePaymentSuccess($data);
                case 'payment.expired':
                    return $this->handlePaymentExpired($data);
                case 'payment.cancelled':
                    return $this->handlePaymentCancelled($data);
                default:
                    Log::warning('Unknown webhook event', ['event' => $data['event'] ?? 'unknown']);
                    return response()->json(['status' => 'ignored'], 200);
            }
        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify webhook signature
     */
    private function verifySignature($payload, $signature)
    {
        $secret = config('services.payment.webhook_key', 'jH88NU1TqDW9IgokdRLRzyTFJCcXbcI4');
        $expectedSignature = hash_hmac('sha256', $payload, $secret);
        
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Handle payment success event
     */
    private function handlePaymentSuccess($data)
    {
        $webhookData = $data['data'] ?? [];
        $externalId = $webhookData['external_id'] ?? null;
        
        if (!$externalId) {
            Log::error('No external_id in webhook data');
            return response()->json(['status' => 'error', 'message' => 'No external_id provided'], 400);
        }

        // Find payment by external_id
        $payment = Payment::where('external_id', $externalId)->first();
        
        if (!$payment) {
            Log::error('Payment not found', ['external_id' => $externalId]);
            return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
        }

        // Update payment status to paid
        $payment->status = 'paid';
        $payment->payment_date = now();
        $payment->save();

        // Create ticket holders based on metadata attendee_emails (fallback to cache, then buyer if missing)
        $emails = [];
        if (isset($webhookData['metadata']['attendee_emails']) && is_array($webhookData['metadata']['attendee_emails'])) {
            $emails = $webhookData['metadata']['attendee_emails'];
        } else {
            $cached = Cache::get('payment_meta:' . $externalId) ?? Cache::get('payment_meta_va:' . ($payment->va_number ?? ''));
            if (is_array($cached['attendee_emails'] ?? null)) {
                $emails = $cached['attendee_emails'];
            } else if ($payment->user_id) {
                $buyer = User::find($payment->user_id);
                if ($buyer) {
                    $emails = array_fill(0, max(1, (int) $payment->quantity), $buyer->email);
                }
            }
        }

        // Prevent duplicate creation for this payment
        $existingCount = TicketHolder::where('qr_code', 'like', 'QR-' . $payment->payment_id . '-%')->count();
        $created = 0;
        if ($existingCount === 0) {
            foreach ($emails as $idx => $email) {
                $user = User::where('email', $email)->first();
                $generatedPassword = null;
                if (! $user) {
                    // Create user account with attendee role and random password
                    $generatedPassword = Str::random(10);
                    $user = User::create([
                        'name' => explode('@', $email)[0],
                        'username' => explode('@', $email)[0] . '_' . Str::lower(Str::random(4)),
                        'email' => $email,
                        'password' => bcrypt($generatedPassword),
                        'role' => 'attendee',
                    ]);

                    // Send email with credentials
                    try {
                        Mail::to($email)->send(new \App\Mail\AttendeeAccountCreated($user, $generatedPassword));
                    } catch (\Throwable $e) {
                        Log::error('Failed to send attendee account email', ['email' => $email, 'error' => $e->getMessage()]);
                    }
                }

                $attendee = Attendee::firstOrCreate(['user_id' => $user->id]);

                TicketHolder::create([
                    'attendee_id' => $attendee->id,
                    'event_id' => $payment->event_id,
                    'qr_code' => $this->generateTicketQr($payment, $idx),
                    'status' => 'active',
                ]);
                $created++;
            }
        }

        Log::info('Payment updated successfully', [
            'payment_id' => $payment->payment_id,
            'external_id' => $externalId,
            'ticket_holders_created' => $created
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Payment processed successfully'
        ], 200);
    }

    /**
     * Handle payment expired event
     */
    private function handlePaymentExpired($data)
    {
        $webhookData = $data['data'] ?? [];
        $externalId = $webhookData['external_id'] ?? null;
        
        if (!$externalId) {
            return response()->json(['status' => 'error', 'message' => 'No external_id provided'], 400);
        }

        $payment = Payment::where('external_id', $externalId)->first();
        
        if ($payment) {
            $payment->status = 'cancelled';
            $payment->save();
            
            Log::info('Payment expired', ['payment_id' => $payment->payment_id]);
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Handle payment cancelled event
     */
    private function handlePaymentCancelled($data)
    {
        $webhookData = $data['data'] ?? [];
        $externalId = $webhookData['external_id'] ?? null;
        
        if (!$externalId) {
            return response()->json(['status' => 'error', 'message' => 'No external_id provided'], 400);
        }

        $payment = Payment::where('external_id', $externalId)->first();
        
        if ($payment) {
            $payment->status = 'cancelled';
            $payment->save();
            
            Log::info('Payment cancelled', ['payment_id' => $payment->payment_id]);
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Generate unique QR code for payment
     */
    private function generateTicketQr(Payment $payment, int $index)
    {
        // Generate unique QR code for each holder
        $random = strtoupper(Str::random(8));
        return "QR-{$payment->payment_id}-{$index}-{$random}";
    }
}
