<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Payment::with(['event'])
            ->where('user_id', auth()->id())
            ->whereIn('status', ['active', 'used'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tickets.index', compact('tickets'));
    }

    public function showQrCode($paymentId)
    {
        $payment = Payment::with(['event'])
            ->where('payment_id', $paymentId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('tickets.qrcode', compact('payment'));
    }
}
