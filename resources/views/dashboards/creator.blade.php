<div>
    <h3 class="text-lg text-center font-semibold mb-4">Creator Dashboard</h3>
    <h4 class="font-semibold mb-2">Daftar Event Saya</h4>

    @if(isset($creator_events) && $creator_events->count())
    <div class="space-y-4">
        @foreach($creator_events as $ev)
        <div class="border p-4 rounded">
            <div class="flex justify-between items-start">
                <div>
                    <h5 class="text-lg font-bold">{{ $ev->event_name }}</h5>
                    <div class="text-sm text-gray-600">{{ $ev->event_location }} · {{ $ev->event_date?->format('Y-m-d H:i') }}</div>
                </div>
                <div class="text-sm">
                    <span class="px-2 py-1 rounded text-white text-xs {{ $ev->status === 'approved' ? 'bg-green-600' : ($ev->status === 'rejected' ? 'bg-red-600' : 'bg-yellow-600') }}">{{ ucfirst($ev->status) }}</span>
                </div>
            </div>

            <div class="mt-3 text-sm text-gray-700">
                <p>{{ \Illuminate\Support\Str::limit($ev->event_description, 200) }}</p>
                <p class="mt-2">Kapasitas: {{ $ev->event_capacity }}</p>
                @if($ev->approved_at)
                <p class="text-green-700">Disetujui: {{ $ev->approved_at->format('Y-m-d H:i') }}</p>
                @endif
                @if($ev->rejected_at)
                <p class="text-red-700">Ditolak: {{ $ev->rejected_at->format('Y-m-d H:i') }}</p>
                @endif
                
                <a href="{{ route('creator.events.participants', ['id' => $ev->event_id]) }}"
                class="inline-block mt-3 px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                Lihat Peserta
                </a>

            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-sm text-gray-600">Belum ada event. Buat event baru menggunakan form di atas.</div>
    @endif
</div>

@if(config('app.debug'))
@php
$authId = auth()->id();
$creatorIds = \App\Models\EventCreator::where('user_id', $authId)->pluck('id')->toArray();
$idsForQuery = array_values(array_unique(array_merge($creatorIds, [$authId])));
$found = \App\Models\Event::whereIn('events_creators_id', $idsForQuery)->pluck('event_id')->toArray();
@endphp

<div class="mt-4 p-3 border rounded bg-gray-50 text-sm">
    <strong>DEBUG (visible because APP_DEBUG=true)</strong>
    <div>auth()->id(): {{ $authId }}</div>
    <div>EventCreator ids for this user: {{ json_encode($creatorIds) }}</div>
    <div>Ids used for query (creator ids + fallback auth id): {{ json_encode($idsForQuery) }}</div>
    <div>Events matched by that query (ids): {{ json_encode($found) }} (count: {{ count($found) }})</div>
</div>
@endif



@if(session('success'))
<div class="mt-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
@endif

<!-- Floating action button -->
<button id="openEventModal" title="Tambah Event" type="button" class="fixed right-6 bottom-6 z-40 flex items-center gap-2 px-4 py-3 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
    </svg>
    <span class="text-sm font-medium">Tambah Event</span>
</button>

<!-- Modal (hidden by default) -->
<div id="eventModal" class="hidden fixed inset-0 z-50 items-center justify-center">
    <div class="absolute inset-0 bg-black/50" id="eventModalOverlay"></div>

    <div class="relative bg-white rounded-lg shadow-xl max-w-xl w-full mx-4 p-6 z-10">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Tambah Event</h3>
            <button type="button" id="closeEventModal" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>

        <form method="POST" action="{{ route('creator.events.store') }}" class="space-y-3">
            @csrf

            <div>
                <label class="block text-sm font-medium">Nama Event</label>
                <input type="text" name="event_name" value="{{ old('event_name') }}" class="mt-1 block w-full border rounded p-2" />
                @error('event_name') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Deskripsi</label>
                <textarea name="event_description" class="mt-1 block w-full border rounded p-2">{{ old('event_description') }}</textarea>
                @error('event_description') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Lokasi</label>
                <input type="text" name="event_location" value="{{ old('event_location') }}" class="mt-1 block w-full border rounded p-2" />
                @error('event_location') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Tanggal & Waktu</label>
                <input type="datetime-local" name="event_date" value="{{ old('event_date') }}" class="mt-1 block w-full border rounded p-2" />
                @error('event_date') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Kapasitas</label>
                <input type="number" name="event_capacity" value="{{ old('event_capacity', 1) }}" class="mt-1 block w-full border rounded p-2" min="1" />
                @error('event_capacity') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" id="cancelEventModal" class="px-4 py-2 border rounded">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="validationFlag" data-errors="{{ $errors->any() ? '1' : '0' }}" style="display:none"></div>

<script>
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
        }

        function hide() {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
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
            /* noop */ }
    })();
</script>