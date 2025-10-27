<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

        // Update payment status
        $payment->status = 'active';
        $payment->payment_date = now();
        $payment->qr_code = $this->generateQrCode($payment);
        $payment->save();

        Log::info('Payment updated successfully', [
            'payment_id' => $payment->payment_id,
            'external_id' => $externalId,
            'qr_code' => $payment->qr_code
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
    private function generateQrCode(Payment $payment)
    {
        // Generate unique QR code
        // Format: QR-{payment_id}-{random_string}
        $random = strtoupper(Str::random(8));
        return "QR-{$payment->payment_id}-{$random}";
    }
}
