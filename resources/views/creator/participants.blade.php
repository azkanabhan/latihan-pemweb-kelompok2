<x-app-layout>
    <div class="container mx-auto py-8 px-6">
        {{-- Judul --}}
        <h2 class="text-3xl font-bold mb-6 text-gray-800">
            Peserta untuk Event: 
            <span class="text-indigo-700">{{ $event->event_name }}</span>
        </h2>

        {{-- Jika belum ada peserta --}}
        @if ($event->attendees->isEmpty())
            <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 p-4 rounded-md shadow-sm">
                Belum ada peserta yang mendaftar untuk event ini.
            </div>
        @else
            {{-- Tabel peserta --}}
            <div class="overflow-x-auto bg-white border border-gray-300 rounded-lg shadow-md">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-indigo-100 border-b border-gray-300">
                            <th class="px-5 py-3 text-left font-semibold text-gray-700 uppercase tracking-wider">Nama</th>
                            <th class="px-5 py-3 text-left font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                            <th class="px-5 py-3 text-center font-semibold text-gray-700 uppercase tracking-wider">Usia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($event->attendees as $attendee)
                            <tr class="even:bg-gray-50 hover:bg-indigo-50 transition-colors duration-150">
                                <td class="px-5 py-3 border-b border-gray-200 text-gray-800">
                                    {{ $attendee->user->name ?? '-' }}
                                </td>
                                <td class="px-5 py-3 border-b border-gray-200 text-gray-800">
                                    {{ $attendee->user->email ?? '-' }}
                                </td>
                                <td class="px-5 py-3 border-b border-gray-200 text-center text-gray-800">
                                    {{ $attendee->age ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Total peserta --}}
            <div class="mt-4 text-gray-700 text-sm">
                Total Peserta: 
                <span class="font-semibold">{{ $event->attendees->count() }}</span> orang
            </div>
        @endif

        {{-- Tombol kembali --}}
        <div class="mt-8">
            <a href="{{ route('dashboard') }}"
               class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2.5 rounded-lg shadow-md border border-indigo-700 transition-all duration-150">
               ← Kembali ke Dashboard
            </a>
        </div>
    </div>
</x-app-layout>
