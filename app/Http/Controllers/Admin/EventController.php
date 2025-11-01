<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCreator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->string('tab')->toString() ?: 'requested';
        $query = Event::with(['creator.user' => function($query) {
            $query->select('id', 'name', 'email');
        }]);

        if ($search = $request->string('q')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('event_name', 'like', "%{$search}%")
                  ->orWhere('event_location', 'like', "%{$search}%");
            });
        }

        if ($tab === 'approved') {
            $query->approved();
        } else {
            $query->requested();
        }

        $events = $query->orderByDesc('event_date')->paginate(10)->withQueryString();

        return view('admin.events.index', compact('events', 'tab'));
    }

    // create/store disabled for admin per new requirements

    public function show(Event $event): View
    {
        $event->load(['creator.user', 'tickets', 'ticket_holders.attendee.user']);
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event): View
    {
        $creators = EventCreator::with('user:id,name,email')->orderBy('id')->get(['id', 'user_id']);
        return view('admin.events.edit', compact('event', 'creators'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'event_name' => ['required', 'string', 'max:255'],
            'event_description' => ['nullable', 'string'],
            'event_location' => ['required', 'string', 'max:255'],
            'event_date' => ['required', 'date'],
            'event_capacity' => ['required', 'integer', 'min:1'],
            'events_creators_id' => ['required', 'integer', 'exists:event_creators,id'],
        ]);

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('status', 'Event updated');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('status', 'Event deleted');
    }

    public function approve(Event $event): RedirectResponse
    {
        try {
            $result = $event->approve();
            if ($result) {
                // Refresh event untuk mendapatkan data terbaru
                $event->refresh();
                return redirect()->route('admin.events.show', $event)
                    ->with('status', 'Event berhasil disetujui!');
            } else {
                return back()->withErrors(['error' => 'Gagal menyimpan perubahan.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function reject(Event $event): RedirectResponse
    {
        try {
            $result = $event->reject();
            if ($result) {
                // Refresh event untuk mendapatkan data terbaru
                $event->refresh();
                return redirect()->route('admin.events.show', $event)
                    ->with('status', 'Event berhasil ditolak!');
            } else {
                return back()->withErrors(['error' => 'Gagal menyimpan perubahan.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}


