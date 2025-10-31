<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Ticket') }}
        </h2>
    </x-slot>

    <style>
        @media print {
            header, nav, .no-print, a[href]:after { display: none !important; }
            body { background: #fff !important; }
            .print-container { box-shadow: none !important; border: 1px solid #e5e7eb; }
        }
        .ticket-gradient { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); }
        .ticket-cut { background-image: radial-gradient(circle at 10px 24px, transparent 12px, #fff 13px); background-size: 20px 48px; }
    </style>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl print-container">
                <div class="ticket-gradient p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold tracking-wide">{{ $ticket->event->event_name ?? 'Event' }}</h1>
                            <p class="mt-1 opacity-90">{{ $ticket->event->event_location ?? 'Location TBA' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm opacity-90">Date & Time</p>
                            <p class="font-semibold">{{ \Carbon\Carbon::parse($ticket->event->event_date)->format('d M Y, H:i') ?? 'TBA' }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-0">
                    <div class="md:col-span-2 p-6">
                        <div class="mb-4">
                            <p class="text-gray-500 text-xs">Ticket Holder</p>
                            <p class="text-gray-900 font-semibold">{{ auth()->user()->email }}</p>
                        </div>

                        <div class="mb-4">
                            <p class="text-gray-500 text-xs">Status</p>
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                                {{ $ticket->status === 'active' ? 'bg-green-100 text-green-800' : ($ticket->status === 'used' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </div>

                        <div class="bg-gray-50 border border-dashed border-gray-300 rounded-lg p-4">
                            <p class="text-xs text-gray-500">QR Payload</p>
                            <p class="font-mono text-sm text-gray-800 break-all">{{ $ticket->qr_code }}</p>
                        </div>
                    </div>
                    <div class="p-6 flex items-center justify-center ticket-cut">
                        <div class="bg-white p-4 border-4 border-gray-900 rounded-xl">
                            <div class="qr-code-container" data-qr-code="{{ $ticket->qr_code }}"></div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center p-6 border-t border-gray-100">
                    <a href="{{ route('tickets.index') }}" class="no-print inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back
                    </a>
                    <button onclick="window.print()" class="no-print inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print Ticket
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const el = document.querySelector('.qr-code-container');
            const data = el.getAttribute('data-qr-code');
            if (data) {
                new QRCode(el, {
                    text: data,
                    width: 220,
                    height: 220,
                    colorDark: '#000000',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H
                });
            }
        });
    </script>
</x-app-layout>


