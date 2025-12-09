<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\TicketHolder;
use App\Models\EventCreator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QRCodeController extends Controller
{
    /**
     * Display the QR code scanner/writer page
     */
    public function index()
    {
        return view('creator.qrcode-scanner');
    }

    /**
     * Process QR code scan/input and update ticket status
     */
    public function scan(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string|max:255',
        ]);

        $qrCode = trim($request->input('qr_code'));

        // Find ticket holder by QR code
        $ticketHolder = TicketHolder::with('event.creator')
            ->where('qr_code', $qrCode)
            ->first();

        if (!$ticketHolder) {
            return redirect()
                ->route('creator.qrcode.index')
                ->with('error', 'QR Code tidak ditemukan.');
        }

        // Verify that the event belongs to the authenticated creator
        $userId = Auth::id();
        $creator = EventCreator::getByUserId($userId);

        if (!$creator) {
            return redirect()
                ->route('creator.qrcode.index')
                ->with('error', 'Anda bukan creator yang terdaftar.');
        }

        if ($ticketHolder->event->events_creators_id !== $creator->id) {
            return redirect()
                ->route('creator.qrcode.index')
                ->with('error', 'QR Code ini bukan milik event Anda.');
        }

        // Check if ticket is already used
        if ($ticketHolder->status === 'used') {
            return redirect()
                ->route('creator.qrcode.index')
                ->with('warning', 'Tiket ini sudah digunakan sebelumnya.');
        }

        // Check if ticket is canceled
        if ($ticketHolder->status === 'canceled') {
            return redirect()
                ->route('creator.qrcode.index')
                ->with('error', 'Tiket ini telah dibatalkan.');
        }

        // Update ticket status to 'used'
        $ticketHolder->status = 'used';
        $ticketHolder->save();

        return redirect()
            ->route('creator.qrcode.index')
            ->with('success', 'Tiket berhasil divalidasi');
    }
}

