<div class="space-y-6">
    <div class="rounded-2xl bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50 border border-indigo-100 p-4 sm:p-5 shadow-sm">
        <h3 class="text-lg font-semibold text-indigo-900">Attendee Dashboard</h3>
        <p class="text-sm text-indigo-700/80 mt-1">Kelola tiket dan jelajahi event menarik.</p>
    </div>

    <!-- My Tickets Section -->
    @php
    $attendee = \App\Models\Attendee::where('user_id', auth()->id())->first();
    $myTickets = $attendee
    ? \App\Models\TicketHolder::with(['event'])
    ->where('attendee_id', $attendee->id)
    ->orderBy('created_at', 'desc')
    ->limit(3)
    ->get()
    : collect();
    @endphp

    @if($myTickets->count() > 0)
    <div class="rounded-2xl border-2 border-gray-100 bg-white shadow-sm">
        <div class="flex items-center justify-between px-4 py-3 sm:px-5 border-b border-gray-100">
            <div>
                <h4 class="text-md font-semibold text-gray-800">My Recent Tickets</h4>
                <p class="text-xs text-gray-500 mt-0.5">3 tiket terbaru yang kamu miliki</p>
            </div>
            <a href="{{ route('tickets.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">View All →</a>
        </div>

        <div class="divide-y divide-gray-100">
            @foreach($myTickets as $ticket)
            <div class="p-4 sm:p-5 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between gap-3">
                    <div class="space-y-1">
                        <h5 class="font-semibold text-gray-900">{{ $ticket->event->event_name ?? 'Unknown Event' }}</h5>
                        <p class="text-sm text-gray-600">
                            {{ $ticket->event->event_location ?? 'Location TBA' }}
                            · {{ $ticket->event->start_date?->format('d M Y') ?? 'TBA' }}
                        </p>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                {{ $ticket->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                            <span class="text-xs text-gray-400">ID: {{ $ticket->ticket_holder_id }}</span>
                        </div>
                    </div>
                    @if($ticket->qr_code)
                    <a href="{{ route('tickets.qrcode', $ticket->ticket_holder_id) }}"
                        class="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h3v3H7V7zm0 7h3v3H7v-3zm7-7h3v3h-3V7zm0 7h3v3h-3v-3z" />
                        </svg>
                        View QR
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="rounded-2xl border border-dashed border-gray-200 bg-white p-6 text-center shadow-sm">
        <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-indigo-50 text-indigo-600 mb-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5l7 7-7 7" />
            </svg>
        </div>
        <p class="text-sm text-gray-700 font-medium">Belum ada tiket yang dimiliki.</p>
        <p class="text-xs text-gray-500 mt-1">Mulai jelajahi event dan beli tiket pertama kamu.</p>
        <a href="{{ route('events.index') }}"
            class="mt-3 inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold shadow hover:bg-indigo-700">
            Browse Events
        </a>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="rounded-2xl border border-gray-100 bg-white shadow-sm p-4 sm:p-5">
        <h4 class="text-md font-semibold text-gray-800 mb-3">Quick Actions</h4>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('events.index') }}"
                class="group flex items-center gap-3 p-3 rounded-xl border border-indigo-100 bg-indigo-50 hover:bg-indigo-100 hover:border-indigo-200 transition">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white text-indigo-600 shadow-sm group-hover:shadow">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <span class="text-sm font-medium text-indigo-800">Browse Events</span>
            </a>
            <a href="{{ route('tickets.index') }}"
                class="group flex items-center gap-3 p-3 rounded-xl border border-emerald-100 bg-emerald-50 hover:bg-emerald-100 hover:border-emerald-200 transition">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white text-emerald-600 shadow-sm group-hover:shadow">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </span>
                <span class="text-sm font-medium text-emerald-800">My Tickets</span>
            </a>
        </div>
    </div>
</div>