<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Find Your Next Experience</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .glow-point {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            z-index: -1;
        }
    </style>
</head>

<body
    class="bg-[#FDFDFD] text-gray-900 min-h-screen relative overflow-x-hidden selection:bg-purple-500 selection:text-white">

    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="glow-point bg-purple-500/20 w-[600px] h-[600px] top-0 left-0 -translate-x-1/3 -translate-y-1/3">
        </div>
        <div class="glow-point bg-blue-500/20 w-[700px] h-[700px] bottom-0 right-0 translate-x-1/3 translate-y-1/3">
        </div>
        <div class="glow-point bg-pink-500/25 w-[400px] h-[400px] top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
        </div>
    </div>

    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/70 backdrop-blur-xl border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-xl bg-gradient-to-tr from-purple-600 to-blue-600 flex items-center justify-center shadow-lg shadow-purple-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-lg font-bold tracking-tight text-gray-900">Ticket<span
                            class="text-purple-600">Flow</span></span>
                </div>

                <div class="hidden md:flex items-center space-x-6">
                    @auth
                    <a href="{{ url('/dashboard') }}"
                        class="text-sm font-medium text-gray-600 hover:text-purple-700 transition">Dashboard</a>
                    @else
                    <a href="{{ route('login') }}"
                        class="text-sm font-medium text-gray-600 hover:text-purple-700 transition">Log in</a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="group relative px-5 py-2 rounded-full bg-gray-900 text-white font-semibold text-xs transition overflow-hidden shadow-md hover:shadow-xl hover:bg-gray-800">
                        <span class="relative z-10">Get Started</span>
                    </a>
                    @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div
        class="relative pt-24 pb-12 lg:pt-32 lg:pb-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center z-10 flex flex-col justify-center items-center">
        <span
            class="inline-block py-1 px-3 rounded-full bg-purple-50 border border-purple-100 text-purple-700 text-[10px] font-bold tracking-wider mb-4 uppercase">
            🚀 TUGAS PEMROGRAMAN WEB KELOMPOK 2
        </span>

        <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-6 leading-tight text-gray-900">
            Live is better.<br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 via-pink-500 to-blue-600">
                Book the moment.
            </span>
        </h1>

        <p class="mt-2 text-lg text-gray-500 max-w-xl mx-auto mb-10 font-light leading-relaxed">
            Platform ticketing paling modern untuk mengelola dan memesan tiket event kampus, konser, hingga workshop
            teknologi.
        </p>

        <a href="#events" class="group relative inline-flex items-center justify-center px-8 py-3 bg-white rounded-full transition-all duration-500 ease-out
            border-2 border-gray-100 
            hover:border-purple-500 hover:ring-4 hover:ring-purple-500/30 
            hover:shadow-xl hover:shadow-purple-500/20 
            hover:-translate-y-1">

            <span class="text-lg font-bold text-gray-700 group-hover:text-purple-700 transition-colors duration-500">
                Jelajahi Event
            </span>
        </a>

    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

            <div
                class="group p-6 rounded-3xl bg-white border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.05)] hover:-translate-y-1 transition-all duration-300">
                <div
                    class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">Instant
                    Access</h3>
                <p class="text-sm text-gray-500 leading-snug">
                    Dapatkan tiket elektronik langsung ke emailmu dalam hitungan detik.
                </p>
            </div>

            <div
                class="group p-6 rounded-3xl bg-white border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.05)] hover:-translate-y-1 transition-all duration-300">
                <div
                    class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">100% Secure
                </h3>
                <p class="text-sm text-gray-500 leading-snug">
                    Transaksi aman dengan gateway pembayaran terpercaya.
                </p>
            </div>

            <div
                class="group p-6 rounded-3xl bg-white border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.05)] hover:-translate-y-1 transition-all duration-300">
                <div
                    class="w-12 h-12 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-pink-600 transition-colors">Best
                    Experience</h3>
                <p class="text-sm text-gray-500 leading-snug">
                    Kurasi event terbaik dari konser hingga workshop teknologi.
                </p>
            </div>

            <div
                class="group p-6 rounded-3xl bg-white border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.05)] hover:-translate-y-1 transition-all duration-300">
                <div
                    class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 8V4h6v4H6zm14 12V8h-6v12h6zM6 20v-8h6v8H6z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-emerald-600 transition-colors">Easy
                    Check-in</h3>
                <p class="text-sm text-gray-500 leading-snug">
                    Cukup tunjukkan QR Code tiketmu di lokasi untuk masuk.
                </p>
            </div>

        </div>
    </div>

    <div id="events" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">All Events</h2>
                <div class="h-1 w-12 bg-purple-600 rounded-full mt-1"></div>
            </div>
        </div>

        @if($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($events as $event)
            @php
            $status = $event->availability_status;
            $lowestPrice = $event->tickets->min('price') ?? 0;
            $gradients = [
            'from-pink-500 to-rose-500',
            'from-purple-500 to-indigo-500',
            'from-blue-500 to-cyan-500',
            'from-emerald-500 to-teal-500'
            ];
            $randomGradient = $gradients[$loop->index % 4];
            @endphp

            <div
                class="group relative flex flex-col h-full bg-white border border-gray-100 rounded-3xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300">

                <!-- Image/Photo Area (Background + Photo Carousel) -->
                <div class="relative h-56 overflow-hidden">
                    <!-- Gradient Background (shown when no photos) -->
                    @if(!($event->photos && $event->photos->count() > 0))
                    <div
                        class="absolute inset-0 bg-gradient-to-br {{ $randomGradient }} opacity-90 group-hover:scale-110 transition-transform duration-700 ease-out">
                    </div>
                    <div
                        class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]">
                    </div>
                    @endif

                    <!-- Photo Carousel (shown when photos exist) -->
                    @if($event->photos && $event->photos->count() > 0)
                    <div class="swiper event-carousel w-full h-full" data-event-id="{{ $event->event_id }}">
                        <div class="swiper-wrapper">
                            @foreach($event->photos as $photo)
                            <div class="swiper-slide">
                                <img src="{{ asset('storage/' . $photo->photo_path) }}"
                                    alt="Event photo"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out" />
                            </div>
                            @endforeach
                        </div>
                        <!-- Autoplay pagination -->
                        <div class="swiper-pagination absolute bottom-3 left-0 right-0 flex justify-center gap-2"></div>
                    </div>
                    @endif
                </div>

                <div
                    class="absolute top-4 left-4 bg-white/95 backdrop-blur-md shadow-lg rounded-2xl p-3 text-center min-w-[70px]">
                    <p class="text-xs text-purple-600 font-bold uppercase tracking-wider">
                        {{ $event->start_date->format('M') }}
                    </p>
                    <p class="text-2xl font-bold text-gray-900 leading-none">{{ $event->start_date->format('d') }}
                    </p>
                </div>

                <div class="absolute top-4 right-4">
                    @if($status === 'open')
                    <span
                        class="px-3 py-1 rounded-full text-xs font-bold bg-white/90 text-green-700 shadow-sm flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Open
                    </span>
                    @else
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/90 text-red-600 shadow-sm">
                        Sold Out
                    </span>
                    @endif
                </div>

                <div class="p-6 flex flex-col flex-grow bg-white">
                    <div class="flex items-center text-xs font-bold text-gray-400 mb-3 uppercase tracking-wide">
                        <svg class="w-4 h-4 text-purple-500 mr-1.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $event->event_location }}
                    </div>

                    <h3
                        class="text-xl font-bold text-gray-900 mb-2 leading-tight group-hover:text-purple-700 transition-colors line-clamp-2">
                        <a href="{{ route('events.show', $event) }}">
                            <span class="absolute inset-0"></span>
                            {{ $event->event_name }}
                        </a>
                    </h3>

                    <div class="mt-auto pt-4 flex items-end justify-between border-t border-gray-50">
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Mulai dari</p>
                            <p class="text-lg font-bold text-purple-600">Rp
                                {{ number_format($lowestPrice, 0, ',', '.') }}
                            </p>
                        </div>
                        <div
                            class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-20 bg-white rounded-3xl border border-gray-100 border-dashed shadow-sm">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-50 mb-6">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No Events Found</h3>
            <p class="text-gray-500">Belum ada event yang tersedia saat ini.</p>
        </div>
        @endif
    </div>

    <footer class="border-t border-gray-100 bg-white relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-center md:text-left">
                    <span class="text-lg font-bold text-gray-900">Kelompok 2</span>
                    <p class="text-sm text-gray-500 mt-1">Project Pemrograman Web &copy; {{ date('Y') }}</p>
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-500 hover:text-purple-700 transition">About</a>
                    <a href="#" class="text-gray-500 hover:text-purple-700 transition">Privacy</a>
                    <a href="#" class="text-gray-500 hover:text-purple-700 transition">Terms</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Initialize all event carousels
        document.querySelectorAll('.event-carousel').forEach(carouselEl => {
            new Swiper(carouselEl, {
                loop: true,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: carouselEl.querySelector('.swiper-pagination'),
                    clickable: true,
                    bulletClass: 'w-2 h-2 rounded-full bg-white/60 cursor-pointer',
                    bulletActiveClass: 'bg-white',
                },
                effect: 'slide',
                speed: 800,
            });
        });
    </script>

</body>

</html>