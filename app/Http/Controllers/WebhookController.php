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
                case 'payment.rejected':
                    return $this->handlePaymentRejected($data);
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

        // Check if status changed to paid (prevent duplicate processing)
        if ($payment->status !== 'paid') {
            $payment->status = 'paid';
            $payment->payment_date = now();
            $payment->save();

            // Retrieve cached attendee emails; fallback to buyer only if cache missing
            $meta = Cache::get('payment_meta:' . $externalId) ?? Cache::get('payment_meta_va:' . ($payment->va_number ?? ''));
            $emails = is_array($meta['attendee_emails'] ?? null) ? $meta['attendee_emails'] : [];
            
            // Fallback: use metadata from webhook if available
            if (empty($emails) && isset($webhookData['metadata']['attendee_emails']) && is_array($webhookData['metadata']['attendee_emails'])) {
                $emails = $webhookData['metadata']['attendee_emails'];
            }
            
            // Final fallback: use buyer email
            if (empty($emails) && $payment->user_id) {
                $buyer = User::find($payment->user_id);
                if ($buyer) {
                    $emails = array_fill(0, max(1, (int) $payment->quantity), $buyer->email);
                }
            }

            // Prevent duplicate creation if already created for this payment
            $existingCount = TicketHolder::where('qr_code', 'like', 'QR-' . $payment->payment_id . '-%')->count();
            if ($existingCount === 0 && !empty($emails)) {
                foreach ($emails as $email) {
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
                            Log::error('Failed to send attendee account email', ['email' => $email, 'error' => $e->getMessage()]);
                        }
                    }
                    $attendee = Attendee::firstOrCreate(['user_id' => $user->id]);
                    TicketHolder::create([
                        'attendee_id' => $attendee->id,
                        'event_id' => $payment->event_id,
                        'qr_code' => $this->generateTicketQr($payment),
                        'status' => 'active',
                    ]);
                }
            }

            Log::info('Payment marked paid and ticket holders created via webhook', [
                'payment_id' => $payment->payment_id,
                'external_id' => $externalId,
                'va_number' => $payment->va_number
            ]);
        }

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
            Log::error('No external_id in payment expired webhook data');
            return response()->json(['status' => 'error', 'message' => 'No external_id provided'], 400);
        }

        $payment = Payment::where('external_id', $externalId)->first();
        
        if (!$payment) {
            Log::error('Payment not found for expired event', ['external_id' => $externalId]);
            return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
        }

        // Only update if status is still pending
        if ($payment->status === 'pending') {
            $payment->status = 'cancelled';
            $payment->save();
            
            Log::info('Payment expired', [
                'payment_id' => $payment->payment_id,
                'external_id' => $externalId,
                'va_number' => $payment->va_number
            ]);
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
            Log::error('No external_id in payment cancelled webhook data');
            return response()->json(['status' => 'error', 'message' => 'No external_id provided'], 400);
        }

        $payment = Payment::where('external_id', $externalId)->first();
        
        if (!$payment) {
            Log::error('Payment not found for cancelled event', ['external_id' => $externalId]);
            return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
        }

        // Only update if status is still pending
        if ($payment->status === 'pending') {
            $payment->status = 'cancelled';
            $payment->save();
            
            Log::info('Payment cancelled', [
                'payment_id' => $payment->payment_id,
                'external_id' => $externalId,
                'va_number' => $payment->va_number
            ]);
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Handle payment rejected event
     */
    private function handlePaymentRejected($data)
    {
        $webhookData = $data['data'] ?? [];
        $externalId = $webhookData['external_id'] ?? null;
        
        if (!$externalId) {
            Log::error('No external_id in payment rejected webhook data');
            return response()->json(['status' => 'error', 'message' => 'No external_id provided'], 400);
        }

        $payment = Payment::where('external_id', $externalId)->first();
        
        if (!$payment) {
            Log::error('Payment not found for rejected event', ['external_id' => $externalId]);
            return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
        }

        // Only update if status is still pending
        if ($payment->status === 'pending') {
            $payment->status = 'cancelled';
            $payment->save();
            
            Log::info('Payment rejected', [
                'payment_id' => $payment->payment_id,
                'external_id' => $externalId,
                'va_number' => $payment->va_number
            ]);
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Generate unique QR code for payment
     */
    private function generateTicketQr(Payment $payment)
    {
        // Generate unique QR code for each holder
        $random = strtoupper(Str::random(8));
        return "QR-{$payment->payment_id}-{$random}";
    }
}
