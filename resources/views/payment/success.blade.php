<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Pembayaran Berhasil - {{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 dark:bg-gray-900 min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-4">
                        <a href="{{ url('/') }}" class="text-2xl font-bold text-gray-900 dark:text-white">Kelompok 2</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium transition">
                                Log in
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Success Message Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-green-400 to-emerald-500 dark:from-green-600 dark:to-emerald-700 px-8 py-12 text-center">
                    <!-- Success Icon -->
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-4">
                        <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">Pembayaran Berhasil!</h1>
                    <p class="text-green-100 text-lg">Terima kasih telah melakukan pembayaran</p>
                </div>

                <div class="p-8">
                    <!-- Payment Details -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Detail Pembayaran</h2>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Status Pembayaran</span>
                                <span class="px-3 py-1 rounded-full text-sm font-semibold 
                                    @if($payment->status === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @endif">
                                    @if($payment->status === 'paid') Lunas
                                    @elseif($payment->status === 'pending') Menunggu Pembayaran
                                    @else Dibatalkan
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">ID Pembayaran</span>
                                <span class="text-gray-900 dark:text-white font-mono text-sm">{{ $payment->external_id }}</span>
                            </div>
                            @if($payment->va_number)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Nomor Virtual Account</span>
                                <span class="text-gray-900 dark:text-white font-mono">{{ $payment->va_number }}</span>
                            </div>
                            @endif
                            @if($payment->payment_date)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Tanggal Pembayaran</span>
                                <span class="text-gray-900 dark:text-white">{{ $payment->payment_date->format('d F Y, H:i') }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Jumlah</span>
                                <span class="text-gray-900 dark:text-white font-bold text-lg">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Event Details -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Detail Event</h2>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 space-y-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $event->event_name }}</h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">{{ $event->event_description }}</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 mt-0.5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Lokasi</p>
                                        <p class="text-gray-900 dark:text-white">{{ $event->event_location }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 mt-0.5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal & Waktu</p>
                                        <p class="text-gray-900 dark:text-white">{{ $event->event_date->format('d F Y, H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ticket Details -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Detail Tiket</h2>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Jenis Tiket</span>
                                <span class="text-gray-900 dark:text-white font-semibold">{{ $ticket->name }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Jumlah</span>
                                <span class="text-gray-900 dark:text-white font-semibold">{{ $payment->quantity }} tiket</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Harga per Tiket</span>
                                <span class="text-gray-900 dark:text-white">Rp {{ number_format($ticket->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        @auth
                            <a href="{{ route('tickets.index') }}" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold text-center transition">
                                Lihat Tiket Saya
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold text-center transition">
                                Login untuk Melihat Tiket
                            </a>
                        @endauth
                        <a href="{{ route('events.show', $event->event_id) }}" class="flex-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white px-6 py-3 rounded-lg font-semibold text-center transition">
                            Kembali ke Event
                        </a>
                        <a href="{{ route('events.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white px-6 py-3 rounded-lg font-semibold text-center transition">
                            Lihat Semua Event
                        </a>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-200 mb-1">Informasi Penting</h3>
                        <p class="text-sm text-blue-800 dark:text-blue-300">
                            @if($payment->status === 'paid')
                                Pembayaran Anda telah berhasil diproses. Tiket Anda akan dikirim ke email yang terdaftar. Jika Anda belum login, silakan login untuk melihat tiket Anda di dashboard.
                            @else
                                Pembayaran Anda sedang diproses. Silakan tunggu konfirmasi atau cek status pembayaran Anda di dashboard.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

