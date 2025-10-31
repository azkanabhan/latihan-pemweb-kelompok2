<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\TicketHolder;
use App\Models\Attendee;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $attendee = Attendee::where('user_id', $userId)->first();
        $attendeeId = $attendee ? $attendee->id : 0;

        $tickets = TicketHolder::with(['event'])
            ->where('attendee_id', $attendeeId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tickets.index', compact('tickets'));
    }

    public function showQrCode($ticketHolderId)
    {
        $userId = auth()->id();
        $attendee = Attendee::where('user_id', $userId)->firstOrFail();
        $ticket = TicketHolder::with(['event'])
            ->where('ticket_holder_id', $ticketHolderId)
            ->where('attendee_id', $attendee->id)
            ->firstOrFail();

        return view('tickets.qrcode', compact('ticket'));
    }
}


