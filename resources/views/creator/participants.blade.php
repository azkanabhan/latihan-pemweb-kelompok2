<x-app-layout>
    <div class="container mx-auto py-6 px-4">
        <h2 class="text-2xl font-bold mb-6">
            Peserta untuk Event: {{ $event->event_name }}
        </h2>

        @if ($event->attendees->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 p-4 rounded">
                Belum ada peserta yang mendaftar untuk event ini.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="border px-4 py-2 text-left text-gray-700 dark:text-gray-200">Nama</th>
                            <th class="border px-4 py-2 text-left text-gray-700 dark:text-gray-200">Email</th>
                            <th class="border px-4 py-2 text-center text-gray-700 dark:text-gray-200">Usia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($event->attendees as $attendee)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="border px-4 py-2 text-gray-800 dark:text-gray-100">
                                    {{ $attendee->username ?? '-' }}
                                </td>
                                <td class="border px-4 py-2 text-gray-800 dark:text-gray-100">
                                    {{ $attendee->email ?? '-' }}
                                </td>
                                <td class="border px-4 py-2 text-center text-gray-800 dark:text-gray-100">
                                    {{ $attendee->age ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Tombol permanen tanpa efek hover --}}
        <div class="mt-6">
            <a href="{{ route('creator.dashboard') }}"
               class="inline-block bg-indigo-600 text-white font-semibold px-4 py-2 rounded shadow-md border border-indigo-700 cursor-pointer select-none">
               ← Kembali ke Dashboard
            </a>
        </div>
    </div>
</x-app-layout>
