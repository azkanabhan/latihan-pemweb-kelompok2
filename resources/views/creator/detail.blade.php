<x-app-layout>
    <div class="container mx-auto py-8 px-6">
        {{-- Judul Event --}}
        <div class="mb-6">
            <h2 class="text-3xl font-bold mb-2 text-gray-800">
                Detail Event: 
                <span class="text-indigo-700">{{ $event->event_name }}</span>
            </h2>
            <div class="text-gray-600 text-sm">
                <p><strong>Lokasi:</strong> {{ $event->event_location }}</p>
                <p><strong>Tanggal:</strong> {{ $event->event_date?->format('d F Y, H:i') }}</p>
                <p><strong>Kapasitas:</strong> {{ $event->event_capacity }} orang</p>
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

        {{-- Daftar Peserta yang Telah Membeli Tiket --}}
        <div class="bg-white border border-gray-300 rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Peserta yang Terdaftar</h3>
                <div class="text-sm text-gray-600">
                    Total: <span class="font-semibold">{{ $event->ticket_holders()->count() }}</span> peserta
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

