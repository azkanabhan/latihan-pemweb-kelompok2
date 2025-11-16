<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }} - Explore Amazing Events</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 dark:bg-gray-900 min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kelompok 2</h1>
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
                        @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-gray-900 dark:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-800 dark:hover:bg-gray-600 transition">
                                Register
                            </a>
                        @endif
                    @endauth
                    </div>
                </div>
            </div>
                </nav>

        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 dark:from-purple-800 dark:to-indigo-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="text-center">
                    <h2 class="text-4xl font-bold text-white mb-4">Tugas Pemrograman Web</h2>
                    <p class="text-xl text-purple-100 mb-8">Website penyedia layanan ticketing event</p>
                </div>
            </div>
        </div>

        <!-- Events Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            @if($events->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($events as $event)
                        @php
                            $bookedCapacity = $event->booked_capacity;
                            $maxCapacity = $event->event_capacity;
                            $status = $event->availability_status;
                            $statusColor = $status === 'open' ? 'green' : 'red';
                            $lowestPrice = $event->tickets->min('price') ?? 0;
                        @endphp

                        <a href="{{ route('events.show', $event) }}" class="group bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200 dark:border-gray-700 hover:border-purple-500 dark:hover:border-purple-500">
                            <!-- Image Placeholder -->
                            <div class="h-48 bg-gradient-to-br from-purple-400 to-indigo-600 dark:from-purple-600 dark:to-indigo-800 relative overflow-hidden">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-24 h-24 text-white opacity-20" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                                    </svg>
                                </div>

                                <!-- Status Badge -->
                                <div class="absolute top-4 right-4">
                                    @if($status === 'open')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                            Open
                                </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                            Fully Booked
                            </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Content -->
                            <div class="p-6">
                                <!-- Event Name -->
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition">
                                    {{ $event->event_name }}
                                </h3>

                                <!-- Event Location -->
                                <div class="flex items-center text-gray-600 dark:text-gray-400 mb-3">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-sm">{{ $event->event_location }}</span>
                                </div>

                                <!-- Event Date -->
                                <div class="flex items-center text-gray-600 dark:text-gray-400 mb-3">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm">{{ $event->start_date->format('M d') }} - {{ $event->end_date->format('M d, Y') }}</span>
                                </div>

                                <!-- Price and Capacity -->
                                <div class="flex items-center justify-between pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <div>
                                        <span class="text-2xl font-bold text-gray-900 dark:text-white">
                                            Rp {{ number_format($lowestPrice, 0, ',', '.') }}
                            </span>
                                        @if($event->tickets->count() > 1)
                                            <span class="text-sm text-gray-500 dark:text-gray-400">/ ticket</span>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Capacity</div>
                                        <div class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            {{ $bookedCapacity }} / {{ $maxCapacity }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">No Events Available</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">There are currently no events to display.</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <p class="text-center text-gray-600 dark:text-gray-400">
                    &copy; {{ date('Y') }} Kelompok 2. All rights reserved.
                </p>
            </div>
        </footer>
    </body>
</html>
