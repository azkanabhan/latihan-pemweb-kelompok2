<x-app-layout>
    <div class="container mx-auto py-8 px-6">
        {{-- Judul Event --}}
        <div class="mb-8">
            <h2 class="text-3xl font-bold mb-6 text-gray-800">
                Detail Event:
                <span class="text-indigo-700">{{ $event->event_name }}</span>
            </h2>
            
            {{-- Informasi Event dengan Card yang Menarik --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                {{-- Lokasi Card --}}
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 border-2 border-purple-200 rounded-xl p-5 shadow-md hover:shadow-lg transition-shadow">
                    <div class="flex items-start gap-4">
                        <div class="bg-purple-500 rounded-xl p-3 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-purple-600 uppercase tracking-wide mb-1">Lokasi</p>
                            <p class="text-gray-800 font-semibold text-lg leading-tight">{{ $event->event_location }}</p>
                        </div>
                    </div>
                </div>

                {{-- Tanggal Card --}}
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-200 rounded-xl p-5 shadow-md hover:shadow-lg transition-shadow">
                    <div class="flex items-start gap-4">
                        <div class="bg-blue-500 rounded-xl p-3 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-1">Tanggal</p>
                            <div class="space-y-1">
                                <p class="text-gray-800 font-semibold text-sm">
                                    <span class="text-blue-600">Mulai:</span> {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y, H:i') }}
                                </p>
                                <p class="text-gray-800 font-semibold text-sm">
                                    <span class="text-blue-600">Selesai:</span> {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kapasitas Card --}}
                <div class="bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-200 rounded-xl p-5 shadow-md hover:shadow-lg transition-shadow">
                    <div class="flex items-start gap-4">
                        <div class="bg-green-500 rounded-xl p-3 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-green-600 uppercase tracking-wide mb-1">Kapasitas</p>
                            <div class="flex items-baseline gap-2">
                                <p class="text-gray-800 font-bold text-2xl">{{ $event->event_capacity }}</p>
                                <p class="text-gray-600 text-sm">orang</p>
                            </div>
                            @php
                                $bookedPercentage = $totalParticipants > 0 ? ($totalParticipants / $event->event_capacity) * 100 : 0;
                            @endphp
                            <div class="mt-2">
                                <div class="w-full bg-green-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: {{ min($bookedPercentage, 100) }}%"></div>
                                </div>
                                <p class="text-xs text-gray-600 mt-1">
                                    Terisi: <span class="font-semibold">{{ $totalParticipants ?? 0 }}</span> / {{ $event->event_capacity }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Informasi Harga Tiket --}}
        <div class="mb-6 bg-white border border-gray-300 rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold mb-4 text-gray-800">Harga Tiket</h3>
            @if($event->tickets->isEmpty())
                <div class="text-gray-500 italic">Belum ada tiket yang tersedia untuk event ini.</div>
            @else
                <div class="space-y-2">
                    @foreach($event->tickets as $ticket)
                        <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-0">
                            <span class="font-medium text-gray-700">{{ $ticket->name }}</span>
                            <span class="font-semibold text-indigo-600">Rp {{ number_format($ticket->price, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Statistik Peserta --}}
        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-100 text-sm font-medium mb-1">Total Peserta</p>
                        <p class="text-3xl font-bold">{{ $totalParticipants ?? 0 }}</p>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium mb-1">Peserta Aktif</p>
                        <p class="text-3xl font-bold">{{ $activeParticipants ?? 0 }}</p>
                        <p class="text-green-100 text-xs mt-1">Status: Active</p>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium mb-1">Sudah Hadir</p>
                        <p class="text-3xl font-bold">{{ $attendedParticipants ?? 0 }}</p>
                        <p class="text-blue-100 text-xs mt-1">Status: Used</p>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daftar Peserta yang Telah Membeli Tiket --}}
        <div class="bg-white border border-gray-300 rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Daftar Peserta</h3>
                <div class="text-sm text-gray-600">
                    Total: <span class="font-semibold">{{ $totalParticipants ?? 0 }}</span> peserta
                </div>
            </div>

            {{-- Pagination Per Page Selector --}}
            @if($event->ticket_holders()->count() > 10)
            <div class="mb-4 flex items-center gap-2">
                <label class="text-sm text-gray-700 font-medium">Tampilkan:</label>
                <select id="perPageSelect" class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="10" {{ request('per_page') == 10 || !request('per_page') ? 'selected' : '' }}>10 baris</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 baris</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 baris</option>
                    <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                </select>
            </div>
            @endif

            {{-- Jika belum ada peserta --}}
            @if($ticketHolders->isEmpty())
                <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 p-4 rounded-md shadow-sm">
                    Belum ada peserta yang membeli tiket untuk event ini.
                </div>
            @else
                {{-- Tabel peserta --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse">
                        <thead>
                            <tr class="bg-indigo-100 border-b border-gray-300">
                                <th class="px-5 py-3 text-left font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-700 uppercase tracking-wider">Nama</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                                <th class="px-5 py-3 text-center font-semibold text-gray-700 uppercase tracking-wider">Usia</th>
                                <th class="px-5 py-3 text-center font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-700 uppercase tracking-wider">Tanggal Daftar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ticketHolders as $index => $ticketHolder)
                                <tr class="even:bg-gray-50 hover:bg-indigo-50 transition-colors duration-150">
                                    <td class="px-5 py-3 border-b border-gray-200 text-gray-800">
                                        {{ $ticketHolders->firstItem() + $index }}
                                    </td>
                                    <td class="px-5 py-3 border-b border-gray-200 text-gray-800">
                                        {{ $ticketHolder->attendee->user->name ?? '-' }}
                                    </td>
                                    <td class="px-5 py-3 border-b border-gray-200 text-gray-800">
                                        {{ $ticketHolder->attendee->user->email ?? '-' }}
                                    </td>
                                    <td class="px-5 py-3 border-b border-gray-200 text-center text-gray-800">
                                        {{ $ticketHolder->attendee->age ?? '-' }}
                                    </td>
                                    <td class="px-5 py-3 border-b border-gray-200 text-center">
                                        @if($ticketHolder->status == 'active')
                                            <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800 font-medium">Aktif</span>
                                        @elseif($ticketHolder->status == 'used')
                                            <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800 font-medium">Digunakan</span>
                                        @else
                                            <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-800 font-medium">Dibatalkan</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 border-b border-gray-200 text-gray-800">
                                        {{ $ticketHolder->created_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                @if($ticketHolders->hasPages())
                <div class="mt-4">
                    {{ $ticketHolders->appends(request()->query())->links() }}
                </div>
                @endif
            @endif
        </div>

        {{-- Review dan Rating Section --}}
        <div class="mt-8 bg-white border border-gray-300 rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Review & Rating</h3>
                <div class="text-sm text-gray-600">
                    Total: <span class="font-semibold">{{ $event->reviews->count() }}</span> review
                    @if($event->reviews->count() > 0)
                        | Rating Rata-rata: 
                        <span class="font-semibold text-yellow-600">
                            {{ number_format($event->reviews->avg('rating'), 1) }} ⭐
                        </span>
                    @endif
                </div>
            </div>

            @if($event->reviews->isEmpty())
                <div class="bg-gray-50 border-l-4 border-gray-400 text-gray-700 p-4 rounded-md">
                    <p class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Belum ada review untuk event ini.
                    </p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($event->reviews as $review)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <span class="text-indigo-600 font-semibold">
                                            {{ strtoupper(substr($review->attendee->user->name ?? 'User', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $review->attendee->user->name ?? 'Anonymous' }}</p>
                                        <p class="text-xs text-gray-500">{{ $review->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-sm font-semibold text-gray-700">{{ $review->rating }}/5</span>
                                </div>
                            </div>
                            <p class="text-gray-700 mt-2 leading-relaxed">{{ $review->body }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Tombol kembali --}}
        <div class="mt-8">
            <a href="{{ route('dashboard') }}"
               class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2.5 rounded-lg shadow-md border border-indigo-700 transition-all duration-150">
               ← Kembali ke Dashboard
            </a>
        </div>
    </div>

    <script>
        // Handle per page selector change
        document.getElementById('perPageSelect')?.addEventListener('change', function() {
            const perPage = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', perPage);
            url.searchParams.delete('page'); // Reset to first page when changing per page
            window.location.href = url.toString();
        });
    </script>
</x-app-layout>

