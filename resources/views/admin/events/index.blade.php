<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Events</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.events.index', ['tab' => 'requested']) }}" class="px-4 py-2 rounded-md border {{ ($tab ?? 'requested')==='requested' ? 'bg-gray-100' : '' }}">Requested</a>
                            <a href="{{ route('admin.events.index', ['tab' => 'approved']) }}" class="px-4 py-2 rounded-md border {{ ($tab ?? 'requested')==='approved' ? 'bg-gray-100' : '' }}">Approved</a>
                        </div>
                        <form method="GET" class="ml-4">
                            <div class="flex gap-2">
                                <input type="hidden" name="tab" value="{{ $tab ?? 'requested' }}">
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama/lokasi" class="w-full border-gray-300 rounded-md shadow-sm">
                                <button class="px-4 py-2 border rounded-md">Cari</button>
                            </div>
                        </form>
                    </div>

                    @if (session('status'))
                        <div class="mb-4 p-3 rounded bg-green-50 text-green-700 border border-green-200">{{ session('status') }}</div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kapasitas</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Creator</th>
                                    <th class="px-4 py-2"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($events as $event)
                                    <tr>
                                        <td class="px-4 py-2">{{ $event->event_name }}</td>
                                        <td class="px-4 py-2">{{ $event->event_date }}</td>
                                        <td class="px-4 py-2">{{ $event->event_location }}</td>
                                        <td class="px-4 py-2">{{ number_format($event->event_capacity) }}</td>
                                        <td class="px-4 py-2">
                                            @if($event->creator && $event->creator->user)
                                                {{ $event->creator->user->name }}
                                            @else
                                                <span class="text-red-600 italic">Creator tidak ditemukan</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-right whitespace-nowrap">
                                            @if(($tab ?? 'requested')==='requested')
                                                <form action="{{ route('admin.events.approve', $event) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button class="text-emerald-700 hover:text-emerald-900 mr-3" onclick="return confirm('Approve event ini?')">Approve</button>
                                                </form>
                                                <form action="{{ route('admin.events.reject', $event) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button class="text-amber-700 hover:text-amber-900 mr-3" onclick="return confirm('Reject event ini?')">Reject</button>
                                                </form>
                                            @endif
                                            <a href="{{ route('admin.events.edit', $event) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-red-600 hover:text-red-800" onclick="return confirm('Hapus event ini?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $events->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


