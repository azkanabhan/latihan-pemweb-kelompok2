<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\TicketHolder;
use App\Models\Attendee;
use App\Models\ReviewRating;
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

        // Get existing reviews for each event to check if user already reviewed
        $eventIds = $tickets->pluck('event_id')->unique();
        $existingReviews = ReviewRating::where('attendee_id', $attendeeId)
            ->whereIn('event_id', $eventIds)
            ->pluck('event_id')
            ->toArray();

        return view('tickets.index', compact('tickets', 'existingReviews'));
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

    /**
     * Show form to create review for an event
     */
    public function createReview($ticketHolderId)
    {
        $userId = auth()->id();
        $attendee = Attendee::where('user_id', $userId)->firstOrFail();
        
        $ticket = TicketHolder::with(['event'])
            ->where('ticket_holder_id', $ticketHolderId)
            ->where('attendee_id', $attendee->id)
            ->firstOrFail();

        // Check if ticket is used
        if ($ticket->status !== 'used') {
            return redirect()
                ->route('tickets.index')
                ->with('error', 'Hanya tiket yang sudah digunakan yang dapat diberi review.');
        }

        // Check if user already reviewed this event
        $existingReview = ReviewRating::where('attendee_id', $attendee->id)
            ->where('event_id', $ticket->event_id)
            ->first();

        if ($existingReview) {
            return redirect()
                ->route('tickets.index')
                ->with('warning', 'Anda sudah memberikan review untuk event ini.');
        }

        return view('tickets.review', compact('ticket'));
    }

    /**
     * Store review for an event
     */
    public function storeReview(Request $request, $ticketHolderId)
    {
        $userId = auth()->id();
        $attendee = Attendee::where('user_id', $userId)->firstOrFail();
        
        $ticket = TicketHolder::with(['event'])
            ->where('ticket_holder_id', $ticketHolderId)
            ->where('attendee_id', $attendee->id)
            ->firstOrFail();

        // Check if ticket is used
        if ($ticket->status !== 'used') {
            return redirect()
                ->route('tickets.index')
                ->with('error', 'Hanya tiket yang sudah digunakan yang dapat diberi review.');
        }

        // Check if user already reviewed this event
        $existingReview = ReviewRating::where('attendee_id', $attendee->id)
            ->where('event_id', $ticket->event_id)
            ->first();

        if ($existingReview) {
            return redirect()
                ->route('tickets.index')
                ->with('warning', 'Anda sudah memberikan review untuk event ini.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'body' => 'required|string|min:10|max:1000',
        ], [
            'rating.required' => 'Rating wajib diisi.',
            'rating.integer' => 'Rating harus berupa angka.',
            'rating.min' => 'Rating minimal 1 bintang.',
            'rating.max' => 'Rating maksimal 5 bintang.',
            'body.required' => 'Review wajib diisi.',
            'body.min' => 'Review minimal 10 karakter.',
            'body.max' => 'Review maksimal 1000 karakter.',
        ]);

        ReviewRating::create([
            'attendee_id' => $attendee->id,
            'event_id' => $ticket->event_id,
            'rating' => $validated['rating'],
            'body' => $validated['body'],
        ]);

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Review berhasil dikirim. Terima kasih atas feedback Anda!');
    }
}


