<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Event</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-lg font-medium mb-4">Form</h2>

                    <form method="POST" action="{{ route('admin.events.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" name="event_name" value="{{ old('event_name') }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            @error('event_name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="event_description" rows="4" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('event_description') }}</textarea>
                            @error('event_description')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                                <input type="datetime-local" name="event_date" value="{{ old('event_date') }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                @error('event_date')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kapasitas</label>
                                <input type="number" name="event_capacity" value="{{ old('event_capacity') }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                @error('event_capacity')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lokasi</label>
                            <input type="text" name="event_location" value="{{ old('event_location') }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            @error('event_location')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Creator</label>
                            <input list="creator-list" name="events_creators_id" value="{{ old('events_creators_id') }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" placeholder="Pilih creator">
                            <datalist id="creator-list">
                                @foreach($creators as $creator)
                                    <option value="{{ $creator->id }}">{{ $creator->user->name ?? 'User #'.$creator->user_id }} ({{ $creator->user->email ?? '' }})</option>
                                @endforeach
                            </datalist>
                            @error('events_creators_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            <p class="text-xs text-gray-500 mt-1">Ketik nama/email untuk rekomendasi, nilai yang disimpan adalah ID creator.</p>
                        </div>

                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.events.index') }}" class="px-4 py-2 rounded-md border">Batal</a>
                            <button class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


