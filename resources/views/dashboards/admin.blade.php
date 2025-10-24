<div>
    <h3 class="text-lg font-semibold mb-4">Admin Dashboard</h3>
    <ul class="list-disc ml-6 space-y-2">
        <li>Manage users (creators & attendees)</li>
        <li>Manage all events</li>
        <li>Platform stats & reports</li>
    </ul>
</div>


<div class="mt-6">
    <h4 class="font-semibold mb-3">Event Requests</h4>

    @if(isset($admin_requested_events) && $admin_requested_events->count())
    <div class="space-y-3">
        <div class="mb-3 text-right">
            <a href="{{ route('admin.events.index', ['tab' => 'requested']) }}" class="px-3 py-1 border rounded bg-white hover:bg-gray-50">Lihat semua request / Kelola semua event</a>
        </div>
        @foreach($admin_requested_events as $ev)
        <div class="border p-3 rounded">
            <div class="flex justify-between items-start">
                <div>
                    <a href="{{ route('admin.events.show', $ev) }}" class="text-lg font-bold text-blue-700">{{ $ev->event_name }}</a>
                    <div class="text-sm text-gray-600">{{ $ev->event_location }} · {{ $ev->event_date?->format('Y-m-d H:i') }}</div>
                    <div class="text-sm text-gray-700">Creator: {{ $ev->creator?->user?->name ?? '—' }} (ID: {{ $ev->events_creators_id }})</div>
                </div>

                <div class="space-x-2">
                    <form method="POST" action="{{ route('admin.events.approve', $ev) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded">Terima</button>
                    </form>

                    <form method="POST" action="{{ route('admin.events.reject', $ev) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">Tolak</button>
                    </form>
                </div>
            </div>

            <div class="mt-2 text-sm text-gray-700">
                {{ \Illuminate\Support\Str::limit($ev->event_description, 250) }}
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-sm text-gray-600">Tidak ada request event saat ini.</div>
    @endif
</div>