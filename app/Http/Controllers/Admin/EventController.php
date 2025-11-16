<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCreator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Spatie\GoogleCalendar\Event as GoogleCalendarEvent;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->string('tab')->toString() ?: 'requested';
        $search = $request->string('q')->toString() ?: null;

        $events = Event::getEventsWithFilters($tab, $search, 10);

        return view('admin.events.index', compact('events', 'tab'));
    }

    // create/store disabled for admin per new requirements

    public function show(Event $event): View
    {
        $event = Event::getEventForAdmin($event->event_id);
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event): View
    {
        $creators = EventCreator::getAllCreatorsWithUser();
        return view('admin.events.edit', compact('event', 'creators'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        // 3. PERBAIKI VALIDASI (MENGGANTI event_date)
        $validated = $request->validate([
            'event_name' => ['required', 'string', 'max:255'],
            'event_description' => ['nullable', 'string'],
            'event_location' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'], // <-- DIUBAH
            'end_date' => ['required', 'date', 'after_or_equal:start_date'], // <-- DIUBAH
            'event_capacity' => ['required', 'integer', 'min:1'],
            'events_creators_id' => ['required', 'integer', 'exists:event_creators,id'],
        ]);

        $event->update($validated);

        // 4. TAMBAHKAN LOGIKA UPDATE GOOGLE CALENDAR
        // Jika event yang di-update statusnya "approved" DAN punya GCal ID,
        // kita update juga di Google Calendar.
        if ($event->status === 'approved' && $event->google_calendar_event_id) {
            try {
                $calendarEvent = GoogleCalendarEvent::find($event->google_calendar_event_id);
                if ($calendarEvent) {
                    $calendarEvent->name = $event->event_name;
                    $calendarEvent->description = $event->event_description;
                    $calendarEvent->startDateTime = $event->start_date;
                    $calendarEvent->endDateTime = $event->end_date;
                    $calendarEvent->location = $event->event_location;
                    $calendarEvent->save();
                }
            } catch (\Exception $e) {
                Log::error('Gagal update event GCal saat admin update: ' . $e->getMessage());
                // Jangan gagalkan redirect, cukup log errornya
            }
        }

        return redirect()->route('admin.events.index')->with('status', 'Event updated');
    }

    public function destroy(Event $event): RedirectResponse
    {
        // 5. TAMBAHKAN LOGIKA DELETE GOOGLE CALENDAR
        if ($event->google_calendar_event_id) {
            try {
                $calendarEvent = GoogleCalendarEvent::find($event->google_calendar_event_id);
                if ($calendarEvent) {
                    $calendarEvent->delete();
                }
            } catch (\Exception $e) {
                Log::error('Gagal menghapus event GCal saat destroy: ' . $e->getMessage());
            }
        }

        $event->delete();
        return redirect()->route('admin.events.index')->with('status', 'Event deleted');
    }

    public function approve(Event $event): RedirectResponse
    {
        try {
            $result = $event->approve();
            if ($result) {

                // 6. TAMBAHKAN LOGIKA CREATE/UPDATE GOOGLE CALENDAR
                try {
                    if ($event->google_calendar_event_id) {
                        // Event sudah ada, kita update
                        $calendarEvent = GoogleCalendarEvent::find($event->google_calendar_event_id);
                        $calendarEvent->name = $event->event_name;
                        $calendarEvent->description = $event->event_description;
                        $calendarEvent->startDateTime = $event->start_date;
                        $calendarEvent->endDateTime = $event->end_date;
                        $calendarEvent->location = $event->event_location;
                        $calendarEvent->save();
                    } else {
                        // Event baru, kita buat
                        $calendarEvent = GoogleCalendarEvent::create([
                            'name' => $event->event_name,
                            'description' => $event->event_description,
                            'startDateTime' => $event->start_date,
                            'endDateTime' => $event->end_date,
                            'location' => $event->event_location,
                        ]);

                        // Simpan Google Event ID ke database
                        $event->google_calendar_event_id = $calendarEvent->id;
                        $event->save();
                    }
                } catch (\Exception $e) {
                    // Log error tapi jangan gagalkan proses
                    Log::error('Gagal sinkronisasi Google Calendar saat approve: ' . $e->getMessage());
                }

                $event->refresh();
                return redirect()->route('admin.events.show', $event)
                    // Ubah pesannya sedikit
                    ->with('status', 'Event berhasil disetujui dan disinkronkan ke Google Calendar!');
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

                // 7. TAMBAHKAN LOGIKA DELETE GOOGLE CALENDAR
                if ($event->google_calendar_event_id) {
                    try {
                        $calendarEvent = GoogleCalendarEvent::find($event->google_calendar_event_id);
                        if ($calendarEvent) {
                            $calendarEvent->delete();
                        }
                        // Hapus ID dari database
                        $event->google_calendar_event_id = null;
                        $event->save();
                    } catch (\Exception $e) {
                        Log::error('Gagal menghapus event GCal saat reject: ' . $e->getMessage());
                    }
                }

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