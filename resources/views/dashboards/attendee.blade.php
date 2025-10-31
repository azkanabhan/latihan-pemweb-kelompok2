<div>
    <h3 class="text-lg font-semibold mb-4">Attendee Dashboard</h3>
    
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
        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-md font-semibold text-gray-700">My Recent Tickets</h4>
                <a href="{{ route('tickets.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    View All →
                </a>
            </div>
            
            <div class="grid grid-cols-1 gap-4">
                @foreach($myTickets as $ticket)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h5 class="font-semibold text-gray-900 mb-1">
                                    {{ $ticket->event->event_name ?? 'Unknown Event' }}
                                </h5>
                                <p class="text-sm text-gray-600 mb-2">
                                    {{ $ticket->event->event_location ?? 'Location TBA' }}
                                                · {{ \Carbon\Carbon::parse($ticket->event->event_date)->format('d M Y') ?? 'TBA' }}
                                </p>
                                <div class="flex items-center text-sm text-gray-600">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $ticket->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </div>
                            </div>
                            @if($ticket->qr_code)
                                <a href="{{ route('tickets.qrcode', $ticket->ticket_holder_id) }}" 
                                   class="ml-4 text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    View QR
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Quick Actions -->
    <div class="mt-6">
        <h4 class="text-md font-semibold text-gray-700 mb-3">Quick Actions</h4>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('events.index') }}" 
               class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span class="text-sm font-medium text-blue-700">Browse Events</span>
            </a>
            <a href="{{ route('tickets.index') }}" 
               class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
                <span class="text-sm font-medium text-green-700">My Tickets</span>
            </a>
        </div>
    </div>
</div>


