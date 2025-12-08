<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                🎟️
            </span>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Tickets') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-slate-50 via-white to-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($tickets->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($tickets as $ticket)
                <div class="bg-white overflow-hidden shadow-sm hover:shadow-lg transition-shadow rounded-2xl border border-gray-200">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                    {{ $ticket->event->event_name ?? 'Unknown Event' }}
                                </h3>
                                <p class="text-sm text-gray-600 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13 21.314l-4.657-4.657A8 8 0 1117.657 16.657z" />
                                    </svg>
                                    {{ $ticket->event->event_location ?? 'Location TBA' }}
                                </p>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        {{ $ticket->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </div>

                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($ticket->event->event_date)->format('d M Y, H:i') ?? 'TBA' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $ticket->event->event_name ?? '' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Holder: {{ auth()->user()->email }}</span>
                            </div>
                        </div>

                        @if($ticket->qr_code)
                        <a href="{{ route('tickets.qrcode', $ticket->ticket_holder_id) }}"
                            class="block w-full mt-4 bg-indigo-600 hover:bg-indigo-700 text-white text-center py-2 px-4 rounded-lg transition">
                            View QR Code
                        </a>
                        @else
                        <div class="block w-full mt-4 bg-gray-200 text-gray-600 text-center py-2 px-4 rounded-lg">
                            No QR Code
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-8 text-center border border-dashed border-gray-200">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No tickets found</h3>
                <p class="mt-2 text-sm text-gray-500">You haven't purchased any tickets yet.</p>
                <div class="mt-6">
                    <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 shadow">
                        Browse Events
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>