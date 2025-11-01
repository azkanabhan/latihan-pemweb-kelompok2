<div>
    <h3 class="text-lg font-semibold mb-4">Admin Dashboard</h3>
    <ul class="list-disc ml-6 space-y-2">
        <li>Manage users (creators & attendees)</li>
        <li>Manage all events</li>
        <li>Platform stats & reports</li>
    </ul>
</div>


<div class="mt-6">
    <h4 class="font-semibold mb-4 text-xl text-gray-800">Event Requests</h4>

    @if(isset($admin_requested_events) && $admin_requested_events->count())
    <div class="space-y-4">
        <div class="mb-4 text-right">
            <a href="{{ route('admin.events.index', ['tab' => 'requested']) }}"
               class="inline-flex items-center gap-2 px-4 py-2 border-2 border-indigo-300 rounded-lg bg-white hover:bg-indigo-50 text-indigo-700 font-medium transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
                Lihat semua request / Kelola semua event
            </a>
        </div>
        @foreach($admin_requested_events as $ev)
        
        <a href="{{ route('admin.events.show', $ev) }}"
           class="block border-2 border-gray-200 hover:border-indigo-400 p-5 rounded-xl bg-white hover:shadow-lg transition-all duration-200 group cursor-pointer">
            <div class="flex justify-between items-start gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-700 transition-colors">{{ $ev->event_name }}</h3>
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200 flex-shrink-0">
                            Requested
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 mb-2">
                        <span class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $ev->event_location }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $ev->event_date?->format('d M Y, H:i') }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-700 mb-2">
                        <span class="font-medium">Creator:</span>
                        @if($ev->creator && $ev->creator->user)
                            {{ $ev->creator->user->name }}
                        @else
                            <span class="text-red-600 italic">Creator tidak ditemukan</span>
                        @endif
                    </div>
                    <div class="mt-3 text-sm text-gray-700 line-clamp-2">
                        {{ \Illuminate\Support\Str::limit($ev->event_description, 200) }}
                    </div>
                    <div class="mt-3 text-xs text-indigo-600 font-medium flex items-center gap-1 group-hover:gap-2 transition-all">
                        Klik untuk melihat detail
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>

                <div class="flex flex-col gap-2 flex-shrink-0" onclick="event.stopPropagation(); event.preventDefault();">
                    <form method="POST" action="{{ route('admin.events.approve', $ev) }}" class="inline">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Apakah Anda yakin ingin menyetujui event ini?')"
                                class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors flex items-center justify-center gap-2 whitespace-nowrap">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Terima
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.events.reject', $ev) }}" class="inline">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Apakah Anda yakin ingin menolak event ini?')"
                                class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors flex items-center justify-center gap-2 whitespace-nowrap">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Tolak
                        </button>
                    </form>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <p class="text-gray-600 font-medium">Tidak ada request event saat ini.</p>
    </div>
    @endif
</div>
