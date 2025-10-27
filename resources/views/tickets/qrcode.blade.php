<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ticket QR Code') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <!-- Event Info -->
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">
                            {{ $payment->event->event_name ?? 'Unknown Event' }}
                        </h3>
                        <p class="text-gray-600 mb-4">
                            {{ $payment->event->event_location ?? 'Location TBA' }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($payment->event->event_date)->format('l, d F Y - H:i') ?? 'TBA' }}
                        </p>
                    </div>

                    <!-- QR Code -->
                    <div class="flex justify-center mb-6">
                        <div class="bg-white p-6 border-4 border-gray-900 rounded-lg">
                            <div class="qr-code-container" 
                                 data-qr-code="{{ $payment->qr_code }}"></div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Payment Details</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Amount:</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Quantity:</span>
                                <span class="font-semibold text-gray-900">{{ $payment->quantity }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $payment->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Date:</span>
                                <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code String -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-xs text-blue-600 mb-2">QR Code String:</p>
                        <p class="text-sm font-mono text-blue-900 break-all">{{ $payment->qr_code }}</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between mt-6">
                        <a href="{{ route('tickets.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Tickets
                        </a>
                        <button onclick="window.print()" 
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Print Ticket
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include QRCode.js for generating QR codes -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        // Generate QR Code
        document.addEventListener('DOMContentLoaded', function() {
            const qrCodeContainer = document.querySelector('.qr-code-container');
            const qrCodeData = qrCodeContainer.getAttribute('data-qr-code');
            
            if (qrCodeData) {
                new QRCode(qrCodeContainer, {
                    text: qrCodeData,
                    width: 256,
                    height: 256,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
            }
        });
    </script>
</x-app-layout>
