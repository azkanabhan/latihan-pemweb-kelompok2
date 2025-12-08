<div>
    <!-- Toast Notifications -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <!-- Toast trigger data attributes -->
    <div id="toastTrigger" class="hidden"
        data-success="{{ session('success') ? '1' : '0' }}"
        data-success-message="{{ e(session('success') ?? '') }}"
        data-error="{{ session('error') ? '1' : '0' }}"
        data-error-message="{{ e(session('error') ?? '') }}"></div>

    <h3 class="text-lg text-center font-semibold mb-4">Creator Dashboard</h3>
    <h4 class="font-semibold mb-4">Daftar Event Saya</h4>

    {{-- Event berdasarkan status: Requested, Approved, Rejected --}}

    {{-- Event yang Requested --}}
    <div class="mb-6">
        <h5 class="text-md font-semibold mb-3 text-yellow-700 flex items-center gap-2">
            <span class="px-2 py-1 rounded bg-yellow-600 text-white text-xs">Requested</span>
            <span
                class="text-gray-600 text-sm">({{ isset($creator_events_requested) ? $creator_events_requested->count() : 0 }})</span>
        </h5>
        @if(isset($creator_events_requested) && $creator_events_requested->count())
        <div class="space-y-4">
            @foreach($creator_events_requested as $ev)
            <div class="border border-yellow-300 p-4 rounded bg-yellow-50">
                <div class="flex justify-between items-start">
                    <div>
                        <h5 class="text-lg font-bold">{{ $ev->event_name }}</h5>
                        <div class="text-sm text-gray-600">{{ $ev->event_location }} ·
                            {{ $ev->start_date?->format('Y-m-d H:i') }}
                        </div>
                    </div>
                    <div class="text-sm">
                        <span class="px-2 py-1 rounded text-white text-xs bg-yellow-600">Requested</span>
                    </div>
                </div>

                <div class="mt-3 text-sm text-gray-700">
                    <p>{{ \Illuminate\Support\Str::limit($ev->event_description, 200) }}</p>
                    <p class="mt-2">Kapasitas: {{ $ev->event_capacity }} (Terisi: {{ $ev->ticket_holders_count ?? 0 }})
                    </p>
                    <p class="mt-2 text-gray-500">Menunggu persetujuan admin...</p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-sm text-gray-500 italic p-3 bg-gray-50 rounded">Tidak ada event dengan status requested.</div>
        @endif
    </div>

    {{-- Event yang Approved --}}
    <div class="mb-6">
        <h5 class="text-md font-semibold mb-3 text-green-700 flex items-center gap-2">
            <span class="px-2 py-1 rounded bg-green-600 text-white text-xs">Approved</span>
            <span
                class="text-gray-600 text-sm">({{ isset($creator_events_approved) ? $creator_events_approved->count() : 0 }})</span>
        </h5>
        @if(isset($creator_events_approved) && $creator_events_approved->count())
        <div class="space-y-4">
            @foreach($creator_events_approved as $ev)
            <div class="border border-green-300 p-4 rounded bg-green-50">
                <div class="flex justify-between items-start">
                    <div>
                        <h5 class="text-lg font-bold">{{ $ev->event_name }}</h5>
                        <div class="text-sm text-gray-600">{{ $ev->event_location }} ·
                            {{ $ev->start_date?->format('Y-m-d H:i') }}
                        </div>
                    </div>
                    <div class="text-sm">
                        <span class="px-2 py-1 rounded text-white text-xs bg-green-600">Approved</span>
                    </div>
                </div>

                <div class="mt-3 text-sm text-gray-700">
                    <p>{{ \Illuminate\Support\Str::limit($ev->event_description, 200) }}</p>
                    <p class="mt-2">Kapasitas: {{ $ev->event_capacity }} (Terisi: {{ $ev->ticket_holders_count ?? 0 }})
                    </p>
                    @if($ev->approved_at)
                    <p class="text-green-700 mt-2">✓ Disetujui: {{ $ev->approved_at->format('Y-m-d H:i') }}</p>
                    @endif

                    <a href="{{ route('creator.events.detail', ['id' => $ev->event_id]) }}"
                        class="inline-block mt-3 px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                        Lihat Detail
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-sm text-gray-500 italic p-3 bg-gray-50 rounded">Tidak ada event dengan status approved.</div>
        @endif
    </div>

    {{-- Event yang Rejected --}}
    <div class="mb-6">
        <h5 class="text-md font-semibold mb-3 text-red-700 flex items-center gap-2">
            <span class="px-2 py-1 rounded bg-red-600 text-white text-xs">Rejected</span>
            <span
                class="text-gray-600 text-sm">({{ isset($creator_events_rejected) ? $creator_events_rejected->count() : 0 }})</span>
        </h5>
        @if(isset($creator_events_rejected) && $creator_events_rejected->count())
        <div class="space-y-4">
            @foreach($creator_events_rejected as $ev)
            <div class="border border-red-300 p-4 rounded bg-red-50">
                <div class="flex justify-between items-start">
                    <div>
                        <h5 class="text-lg font-bold">{{ $ev->event_name }}</h5>
                        <div class="text-sm text-gray-600">{{ $ev->event_location }} ·
                            {{ $ev->start_date?->format('Y-m-d H:i') }}
                        </div>
                    </div>
                    <div class="text-sm">
                        <span class="px-2 py-1 rounded text-white text-xs bg-red-600">Rejected</span>
                    </div>
                </div>

                <div class="mt-3 text-sm text-gray-700">
                    <p>{{ \Illuminate\Support\Str::limit($ev->event_description, 200) }}</p>
                    <p class="mt-2">Kapasitas: {{ $ev->event_capacity }} (Terisi: {{ $ev->ticket_holders_count ?? 0 }})
                    </p>
                    @if($ev->rejected_at)
                    <p class="text-red-700 mt-2">✗ Ditolak: {{ $ev->rejected_at->format('Y-m-d H:i') }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-sm text-gray-500 italic p-3 bg-gray-50 rounded">Tidak ada event dengan status rejected.</div>
        @endif
    </div>

    {{-- Pesan jika tidak ada event sama sekali --}}
    @if(
    (!isset($creator_events_requested) || $creator_events_requested->count() === 0) &&
    (!isset($creator_events_approved) || $creator_events_approved->count() === 0) &&
    (!isset($creator_events_rejected) || $creator_events_rejected->count() === 0)
    )
    <div class="text-sm text-gray-600 text-center py-8 bg-gray-50 rounded">Belum ada event. Buat event baru menggunakan
        tombol di bawah.</div>
    @endif
</div>

{{-- debug blocks removed (moved to log/view-composer) --}}



@if(session('success'))
<div class="mt-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
@endif

<button id="openEventModal" title="Tambah Event" type="button"
    class="fixed right-6 bottom-6 z-40 flex items-center gap-2 px-4 py-3 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path fill-rule="evenodd"
            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
            clip-rule="evenodd" />
    </svg>
    <span class="text-sm font-medium">Tambah Event</span>
</button>

<div id="eventModal" class="hidden fixed inset-0 z-50 items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" id="eventModalOverlay"></div>

    <div
        class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col z-10 overflow-hidden transform transition-all scale-95 opacity-0">
        <div
            class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-5 flex items-center justify-between rounded-t-2xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white">Tambah Event Baru</h3>
            </div>
            <button type="button" id="closeEventModal"
                class="text-white/90 hover:text-white hover:bg-white/20 rounded-lg p-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto custom-scrollbar">
            <form method="POST" action="{{ route('creator.events.store') }}" id="eventForm" class="p-6 space-y-5" enctype="multipart/form-data">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Nama Event
                        </span>
                    </label>
                    <input type="text" name="event_name" value="{{ old('event_name') }}"
                        class="mt-1 block w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none"
                        placeholder="Contoh: Konser Musik Jazz" />
                    @error('event_name') <div class="text-red-600 text-sm mt-1 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </div> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            Deskripsi
                        </span>
                    </label>
                    <textarea name="event_description" rows="4"
                        class="mt-1 block w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none resize-none"
                        placeholder="Jelaskan tentang event Anda...">{{ old('event_description') }}</textarea>
                    @error('event_description') <div class="text-red-600 text-sm mt-1 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </div> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <span class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Lokasi
                            </span>
                        </label>
                        <input type="text" name="event_location" value="{{ old('event_location') }}"
                            class="mt-1 block w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none"
                            placeholder="Contoh: Jakarta Convention Center" />
                        @error('event_location') <div class="text-red-600 text-sm mt-1 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <span class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Kapasitas
                            </span>
                        </label>
                        <input type="number" name="event_capacity" value="{{ old('event_capacity', 1) }}"
                            class="mt-1 block w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none"
                            min="1" placeholder="100" />
                        @error('event_capacity') <div class="text-red-600 text-sm mt-1 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </div> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Hari Event (multi-day)
                        </span>
                    </label>
                </div>

                <div class="border-2 border-indigo-100 rounded-xl p-4 bg-white">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Daftar Hari
                        </div>
                        <button type="button" id="addDayBtn"
                            class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-indigo-700 bg-indigo-100 hover:bg-indigo-200 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Hari
                        </button>
                    </div>
                    <div id="daysContainer" class="space-y-3" data-count="{{ count(old('days') ?? [['start_at' => '', 'end_at' => '', 'label' => 'Hari 1']]) }}">
                        @php $oldDays = old('days') ?? []; @endphp
                        @if(count($oldDays) === 0)
                        <!-- Empty container, will be populated by JS -->
                        @else
                        @foreach($oldDays as $idx => $day)
                        <div class="day-row grid grid-cols-1 md:grid-cols-3 gap-3 border border-gray-200 rounded-lg p-3 bg-gray-50" data-index="{{ $idx }}">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Mulai</label>
                                <input type="datetime-local" name="days[{{ $idx }}][start_at]" value="{{ $day['start_at'] ?? '' }}"
                                    class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" required />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Selesai</label>
                                <input type="datetime-local" name="days[{{ $idx }}][end_at]" value="{{ $day['end_at'] ?? '' }}"
                                    class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" required />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Label (opsional)</label>
                                <div class="flex gap-2">
                                    <input type="text" name="days[{{ $idx }}][label]" value="{{ $day['label'] ?? '' }}"
                                        class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none"
                                        placeholder="Hari 1" />
                                    <button type="button" class="remove-day-btn w-10 h-10 flex items-center justify-center text-red-600 hover:bg-red-50 rounded-lg transition"
                                        title="Hapus hari">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <p class="text-xs text-gray-600 mt-2">Tambahkan hari jika event berlangsung lebih dari satu hari. Tiket bisa diikat ke hari tertentu.</p>
                </div>

                <div class="border-2 border-indigo-100 rounded-xl p-4 bg-gradient-to-br from-indigo-50 to-purple-50">
                    <div class="flex justify-between items-center mb-3">
                        <label class="block text-sm font-semibold text-gray-700">
                            <span class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                </svg>
                                Tiket
                            </span>
                        </label>
                        <button type="button" id="addTicketBtn"
                            class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-indigo-700 bg-indigo-100 hover:bg-indigo-200 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Tiket
                        </button>
                    </div>

                    <div id="ticketsContainer" class="space-y-3" data-count="{{ old('tickets') ? count(old('tickets')) : 1 }}">
                        @if(old('tickets') && count(old('tickets')) > 0)
                        @foreach(old('tickets') as $index => $ticket)
                        <div
                            class="ticket-row flex gap-3 items-start border-2 border-white rounded-lg p-4 bg-white shadow-sm">
                            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Tiket</label>
                                    <input type="text" name="tickets[{{ $index }}][name]"
                                        value="{{ $ticket['name'] ?? '' }}"
                                        placeholder="Nama tiket (contoh: Regular, VIP)"
                                        class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none"
                                        required />
                                    @error("tickets.{$index}.name")
                                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Harga</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-gray-500 text-sm">Rp</span>
                                        <input type="number" name="tickets[{{ $index }}][price]"
                                            value="{{ $ticket['price'] ?? '' }}" placeholder="100000" min="0"
                                            step="1000"
                                            class="w-full border-2 border-gray-200 rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none"
                                            required />
                                    </div>
                                    @error("tickets.{$index}.price")
                                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Hari (opsional)</label>
                                    <select name="tickets[{{ $index }}][event_day_index]" class="ticket-day-select w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                                        <option value="">Ikut default</option>
                                    </select>
                                </div>
                            </div>
                            @if($index > 0)
                            <button type="button"
                                class="remove-ticket-btn flex-shrink-0 w-8 h-8 flex items-center justify-center text-red-600 hover:bg-red-50 rounded-lg transition-colors mt-8"
                                title="Hapus tiket">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            @endif
                        </div>
                        @endforeach
                        @else
                        <div
                            class="ticket-row flex gap-3 items-start border-2 border-white rounded-lg p-4 bg-white shadow-sm">
                            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Tiket</label>
                                    <input type="text" name="tickets[0][name]" value=""
                                        placeholder="Nama tiket (contoh: Regular, VIP)"
                                        class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none"
                                        required />
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Harga</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-gray-500 text-sm">Rp</span>
                                        <input type="number" name="tickets[0][price]" value="" placeholder="100000"
                                            min="0" step="1000"
                                            class="w-full border-2 border-gray-200 rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none"
                                            required />
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Hari (opsional)</label>
                                    <select name="tickets[0][event_day_index]" class="ticket-day-select w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                                        <option value="">Ikut default</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    @error('tickets')
                    <div class="text-red-600 text-sm mt-2 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </div>
                    @enderror
                    <p class="text-xs text-gray-600 mt-3 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Minimal harus ada satu tiket. Anda bisa menambahkan beberapa tiket dengan harga berbeda.
                    </p>
                </div>

                <!-- Photo Upload Section -->
                <div class="border-2 border-amber-100 rounded-xl p-4 bg-gradient-to-br from-amber-50 to-orange-50">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        <span class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Foto Event (Opsional)
                        </span>
                    </label>

                    <div class="mb-3 p-4 bg-white rounded-lg border-2 border-dashed border-amber-300 hover:border-amber-400 transition-colors cursor-pointer" id="photoDropZone">
                        <input type="file" name="photos[]" id="photoInput" multiple accept="image/*" class="hidden" />
                        <div class="text-center py-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-amber-500 mb-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            <p class="text-sm font-medium text-gray-700">Klik atau seret foto ke sini</p>
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG (Rasio 16:9, max 5MB)</p>
                        </div>
                    </div>

                    <div id="photoPreviewContainer" class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-3"></div>

                    @error('photos')
                    <div class="text-red-600 text-sm mt-2 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </div>
                    @enderror

                    <p class="text-xs text-gray-600 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Foto akan ditampilkan di halaman welcome dengan auto-slide dan di detail event dengan tombol next/prev.
                    </p>
                </div>

                <div class="border-t border-gray-200 bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                    <button type="button" id="cancelEventModal"
                        class="px-5 py-2.5 border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit" form="eventForm"
                        class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-700 hover:to-purple-700 shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                        <span class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Event
                        </span>
                    </button>
                </div>
            </form>
        </div>

    </div>

    <div id="validationFlag" data-errors="{{ $errors->any() ? '1' : '0' }}" style="display:none"></div>

    <style>
        /* Custom Scrollbar untuk Modal */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #4f46e5 0%, #7c3aed 100%);
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #4338ca 0%, #6d28d9 100%);
        }

        /* Smooth scroll behavior */
        .custom-scrollbar {
            scroll-behavior: smooth;
        }

        /* Toast Styles */
        .toast {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            animation: slideIn 0.3s ease-out forwards;
            min-width: 300px;
            max-width: 500px;
        }

        .toast.success {
            background-color: #10b981;
            color: white;
        }

        .toast.error {
            background-color: #ef4444;
            color: white;
        }

        .toast.info {
            background-color: #3b82f6;
            color: white;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .toast.removing {
            animation: slideOut 0.3s ease-out forwards;
        }
    </style>

    <script>
        // Toast notification system
        function showToast(message, type = 'info', duration = 5000) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;

            const icons = {
                success: '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>',
                error: '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>',
                info: '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
            };

            toast.innerHTML = `
                ${icons[type] || icons.info}
                <span>${message}</span>
            `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('removing');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }

        // Show toast from session data on page load
        document.addEventListener('DOMContentLoaded', function() {
            const trigger = document.getElementById('toastTrigger');
            if (trigger.dataset.success === '1' && trigger.dataset.successMessage) {
                showToast(trigger.dataset.successMessage, 'success');
            }
            if (trigger.dataset.error === '1' && trigger.dataset.errorMessage) {
                showToast(trigger.dataset.errorMessage, 'error');
            }
        });

        // Modal management
        (function() {
            const openBtn = document.getElementById('openEventModal');
            const modal = document.getElementById('eventModal');
            const overlay = document.getElementById('eventModalOverlay');
            const closeBtn = document.getElementById('closeEventModal');
            const cancelBtn = document.getElementById('cancelEventModal');

            function show() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
                // Trigger animation
                setTimeout(() => {
                    modal.querySelector('.relative').style.transform = 'scale(1)';
                    modal.querySelector('.relative').style.opacity = '1';
                }, 10);
            }

            function hide() {
                const modalContent = modal.querySelector('.relative');
                modalContent.style.transform = 'scale(0.95)';
                modalContent.style.opacity = '0';
                setTimeout(() => {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                    // Reset transform for next show
                    modalContent.style.transform = '';
                    modalContent.style.opacity = '';
                }, 150);
            }

            openBtn?.addEventListener('click', show);
            closeBtn?.addEventListener('click', hide);
            cancelBtn?.addEventListener('click', hide);
            overlay?.addEventListener('click', hide);

            // If there are validation errors, open the modal so user sees them
            try {
                const flag = document.getElementById('validationFlag');
                if (flag && flag.dataset && flag.dataset.errors === '1') {
                    show();
                }
            } catch (e) {
                /* noop */
            }
        })();

        // Day & Ticket management
        (function() {
            const daysContainer = document.getElementById('daysContainer');
            const addDayBtn = document.getElementById('addDayBtn');
            const ticketsContainer = document.getElementById('ticketsContainer');
            const addTicketBtn = document.getElementById('addTicketBtn');

            let dayIndex = parseInt(daysContainer?.dataset?.count || '1', 10) || 1;
            let ticketIndex = parseInt(ticketsContainer?.dataset?.count || '1', 10) || 1;

            function getDayOptionsHtml() {
                const rows = daysContainer.querySelectorAll('.day-row');
                let html = '<option value="">Ikut default</option>';
                rows.forEach((row, idx) => {
                    const labelInput = row.querySelector('input[name*="[label]"]');
                    const startInput = row.querySelector('input[name*="[start_at]"]');
                    const label = labelInput?.value?.trim() || `Hari ${idx + 1}`;
                    const dateText = startInput?.value ? ` - ${startInput.value}` : '';
                    html += `<option value="${idx}">${label}${dateText}</option>`;
                });
                return html;
            }

            function refreshTicketDaySelects() {
                const options = getDayOptionsHtml();
                ticketsContainer.querySelectorAll('.ticket-day-select').forEach(sel => {
                    const current = sel.value;
                    sel.innerHTML = options;
                    if (current) sel.value = current;
                });
            }

            function addDayRow() {
                const row = document.createElement('div');
                row.className = 'day-row grid grid-cols-1 md:grid-cols-3 gap-3 border border-gray-200 rounded-lg p-3 bg-gray-50';
                row.dataset.index = dayIndex;
                row.innerHTML = `
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Mulai</label>
                        <input type="datetime-local" name="days[${dayIndex}][start_at]" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" required />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Selesai</label>
                        <input type="datetime-local" name="days[${dayIndex}][end_at]" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" required />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Label (opsional)</label>
                        <div class="flex gap-2">
                            <input type="text" name="days[${dayIndex}][label]" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" placeholder="Hari ${dayIndex + 1}" />
                            <button type="button" class="remove-day-btn w-10 h-10 flex items-center justify-center text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus hari">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                `;
                daysContainer.appendChild(row);
                dayIndex++;
                attachDayRemove(row);
                refreshTicketDaySelects();
            }

            function attachDayRemove(row) {
                const btn = row.querySelector('.remove-day-btn');
                btn?.addEventListener('click', () => {
                    const rows = daysContainer.querySelectorAll('.day-row');
                    if (rows.length > 1) {
                        row.remove();
                        refreshTicketDaySelects();
                    } else {
                        alert('Minimal satu hari.');
                    }
                });
            }

            daysContainer.querySelectorAll('.day-row').forEach(r => attachDayRemove(r));
            addDayBtn?.addEventListener('click', addDayRow);

            // Initialize: if no day rows exist, add one
            if (daysContainer.querySelectorAll('.day-row').length === 0) {
                addDayRow();
            }

            function addTicketRow() {
                const row = document.createElement('div');
                row.className = 'ticket-row flex gap-3 items-start border-2 border-white rounded-lg p-4 bg-white shadow-sm';
                row.innerHTML = `
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Tiket</label>
                        <input type="text" name="tickets[${ticketIndex}][name]" 
                               value="" 
                               placeholder="Nama tiket (contoh: Regular, VIP)" 
                               class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none" required />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Harga</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="tickets[${ticketIndex}][price]" 
                                   value="" 
                                   placeholder="100000" 
                                   min="0" step="1000"
                                   class="w-full border-2 border-gray-200 rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none" required />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Hari (opsional)</label>
                        <select name="tickets[${ticketIndex}][event_day_index]" class="ticket-day-select w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                            ${getDayOptionsHtml()}
                        </select>
                    </div>
                </div>
                <button type="button" class="remove-ticket-btn flex-shrink-0 w-8 h-8 flex items-center justify-center text-red-600 hover:bg-red-50 rounded-lg transition-colors mt-8" title="Hapus tiket">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                `;
                ticketsContainer.appendChild(row);
                ticketIndex++;

                row.querySelector('.remove-ticket-btn').addEventListener('click', function() {
                    const ticketRows = ticketsContainer.querySelectorAll('.ticket-row');
                    if (ticketRows.length > 1) {
                        row.remove();
                    } else {
                        alert('Minimal harus ada satu tiket.');
                    }
                });
            }

            function removeTicketRow(btn) {
                const ticketRows = ticketsContainer.querySelectorAll('.ticket-row');
                if (ticketRows.length > 1) {
                    btn.closest('.ticket-row').remove();
                } else {
                    alert('Minimal harus ada satu tiket.');
                }
            }

            addTicketBtn?.addEventListener('click', addTicketRow);
            document.querySelectorAll('.remove-ticket-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    removeTicketRow(this);
                });
            });

            // initialize day options for existing selects
            refreshTicketDaySelects();
            // update options when day inputs change
            daysContainer.addEventListener('input', refreshTicketDaySelects);

            // Photo Upload Handling
            const photoDropZone = document.getElementById('photoDropZone');
            const photoInput = document.getElementById('photoInput');
            const photoPreviewContainer = document.getElementById('photoPreviewContainer');
            let selectedFiles = [];

            photoDropZone.addEventListener('click', () => photoInput.click());

            photoDropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                photoDropZone.classList.add('border-amber-500', 'bg-amber-100');
            });

            photoDropZone.addEventListener('dragleave', () => {
                photoDropZone.classList.remove('border-amber-500', 'bg-amber-100');
            });

            photoDropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                photoDropZone.classList.remove('border-amber-500', 'bg-amber-100');
                handleFiles(e.dataTransfer.files);
            });

            photoInput.addEventListener('change', (e) => {
                handleFiles(e.target.files);
            });

            function handleFiles(files) {
                const newFiles = Array.from(files).filter(file => {
                    if (!file.type.startsWith('image/')) {
                        alert('Hanya file gambar yang diterima!');
                        return false;
                    }
                    if (file.size > 5 * 1024 * 1024) {
                        alert(`File ${file.name} terlalu besar (max 5MB)`);
                        return false;
                    }
                    return true;
                });

                // Append ke selectedFiles, bukan replace
                selectedFiles = selectedFiles.concat(newFiles);
                updatePhotoPreview();
            }

            function updatePhotoPreview() {
                photoPreviewContainer.innerHTML = '';
                selectedFiles.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const div = document.createElement('div');
                        div.className = 'relative group';
                        div.innerHTML = `
                            <div class="relative bg-gray-200 rounded-lg overflow-hidden aspect-video">
                                <img src="${e.target.result}" class="w-full h-full object-cover" />
                                <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-30 transition-opacity"></div>
                            </div>
                            <button type="button" class="absolute top-1 right-1 bg-red-600 text-white p-1 rounded opacity-0 group-hover:opacity-100 transition-opacity" data-index="${index}" title="Hapus foto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        `;
                        photoPreviewContainer.appendChild(div);

                        const removeBtn = div.querySelector('button');
                        removeBtn.addEventListener('click', (e) => {
                            e.preventDefault();
                            selectedFiles.splice(index, 1);
                            updatePhotoPreview();
                        });
                    };
                    reader.readAsDataURL(file);
                });
            }

            // Before form submit, sync selected files to photoInput
            document.getElementById('eventForm').addEventListener('submit', function(e) {
                // Create a single DataTransfer with all selected files
                const dt = new DataTransfer();
                selectedFiles.forEach(file => {
                    dt.items.add(file);
                });
                // Assign all files at once
                photoInput.files = dt.files;
            });
        })();
    </script>