<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $event->event_name }} - {{ config('app.name', 'TicketFlow') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Custom Scrollbar */
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

        /* Animation utilities for Glow */
        .glow-point {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            z-index: -1;
            pointer-events: none;
        }
    </style>
</head>

<body
    class="bg-[#FDFDFD] text-gray-900 min-h-screen relative overflow-x-hidden selection:bg-purple-500 selection:text-white">

    <div id="eventMeta" class="hidden"
        data-event-id="{{ $event->event_id }}"
        data-user-email="{{ auth()->check() ? e(auth()->user()->email) : '' }}"
        data-is-logged-in="{{ auth()->check() ? '1' : '0' }}"
        data-tickets='@json($event->tickets)'>
    </div>

    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="glow-point bg-purple-500/10 w-[600px] h-[600px] top-0 left-0 -translate-x-1/2 -translate-y-1/2">
        </div>
        <div class="glow-point bg-blue-500/10 w-[600px] h-[600px] bottom-0 right-0 translate-x-1/3 translate-y-1/3">
        </div>
    </div>

    <nav class="fixed w-full z-40 transition-all duration-300 bg-white/80 backdrop-blur-xl border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-4">
                    <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                        <div
                            class="w-8 h-8 rounded-lg bg-gradient-to-tr from-purple-600 to-blue-600 flex items-center justify-center text-white shadow-md group-hover:shadow-purple-500/30 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </div>
                        <span class="font-bold text-gray-900 tracking-tight group-hover:text-purple-600 transition">Back
                            to Events</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                    <a href="{{ url('/dashboard') }}"
                        class="text-gray-600 hover:text-purple-600 font-medium text-sm transition">Dashboard</a>
                    @else
                    <a href="{{ route('login') }}"
                        class="text-gray-600 hover:text-purple-600 font-medium text-sm transition">Log in</a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="bg-gray-900 text-white px-5 py-2 rounded-full text-sm font-semibold hover:bg-gray-800 transition shadow-lg hover:shadow-xl">Register</a>
                    @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="pt-12 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto relative z-10">

        <div class="mb-12">
            <div class="mb-4">
                @php
                $status = $event->availability_status;
                @endphp
                @if($status === 'open')
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span> Tickets Available
                </span>
                @else
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                    <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span> Fully Booked
                </span>
                @endif
            </div>

            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-gray-900 mb-6 leading-tight">
                {{ $event->event_name }}
            </h1>

            <div class="flex flex-wrap items-center gap-6 text-gray-500 font-medium">
                <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm">
                    <div class="p-1.5 bg-purple-50 text-purple-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <span>{{ $event->event_location }}</span>
                </div>

                <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm">
                    <div class="p-1.5 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    @if($event->days && $event->days->count())
                    <div class="flex flex-wrap gap-2">
                        @foreach($event->days as $idx => $day)
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                            {{ $day->label ?? ('Day ' . ($idx+1)) }} · {{ $day->start_at->format('d M Y, H:i') }}
                        </span>
                        @endforeach
                    </div>
                    @else
                    <span>{{ $event->start_date->format('d M Y, H:i') }}</span>
                    @endif
                </div>

                <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm">
                    <div class="p-1.5 bg-pink-50 text-pink-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <span>{{ $event->booked_capacity }} / {{ $event->event_capacity }} Sold</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2 space-y-10">

                @if($event->photos && $event->photos->count() > 0)
                <!-- Photo Gallery with Navigation -->
                <div class="rounded-3xl overflow-hidden border border-gray-100 shadow-lg">
                    <div class="relative bg-gray-100 aspect-video flex items-center justify-center">
                        <img id="mainPhoto"
                            src="{{ asset('storage/' . $event->photos->first()->photo_path) }}"
                            alt="Event photo"
                            class="w-full h-full object-cover" />
                    </div>

                    <!-- Photo Navigation -->
                    @if($event->photos->count() > 1)
                    <div class="flex items-center justify-between p-4 bg-white border-t border-gray-100">
                        <button id="photoPrevBtn"
                            class="p-2 rounded-lg bg-gray-100 hover:bg-purple-100 text-gray-700 hover:text-purple-700 transition"
                            title="Previous photo">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <div class="flex-1 mx-4">
                            <div id="photoThumbnails" class="flex gap-2 overflow-x-auto justify-center">
                                @foreach($event->photos as $idx => $photo)
                                <button class="photo-thumbnail flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition {{ $idx === 0 ? 'border-purple-600' : 'border-gray-200' }}"
                                    data-photo-index="{{ $idx }}"
                                    data-photo-url="{{ asset('storage/' . $photo->photo_path) }}">
                                    <img src="{{ asset('storage/' . $photo->photo_path) }}"
                                        alt="Thumbnail {{ $idx + 1 }}"
                                        class="w-full h-full object-cover" />
                                </button>
                                @endforeach
                            </div>
                        </div>

                        <button id="photoNextBtn"
                            class="p-2 rounded-lg bg-gray-100 hover:bg-purple-100 text-gray-700 hover:text-purple-700 transition"
                            title="Next photo">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>

                    <!-- Photo Counter -->
                    <div class="px-4 py-2 bg-gray-50 text-center text-sm text-gray-600 border-t border-gray-100">
                        <span id="photoCounter">1</span> / {{ $event->photos->count() }} photos
                    </div>
                    @endif
                </div>
                @else
                <div class="aspect-video w-full rounded-3xl overflow-hidden shadow-2xl shadow-purple-500/10 relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500 via-indigo-500 to-blue-500"></div>
                    <div
                        class="absolute inset-0 opacity-20 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:20px_20px]">
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-32 h-32 text-white/30" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                        </svg>
                    </div>
                </div>
                @endif


                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        About Event
                    </h2>
                    <div class="prose prose-lg text-gray-600 leading-relaxed whitespace-pre-wrap">
                        {{ $event->event_description ?? 'No description provided for this event.' }}
                    </div>
                </div>

                @if($event->creator && $event->creator->user)
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
                    <div
                        class="h-14 w-14 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-xl font-bold text-gray-600">
                        {{ strtoupper(substr($event->creator->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Organized by</p>
                        <p class="text-lg font-bold text-gray-900">{{ $event->creator->user->name }}</p>
                    </div>
                </div>
                @endif
            </div>

            <div class="lg:col-span-1">
                <div class="sticky top-24">
                    <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-gray-100 p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Select Tickets</h2>

                        @if($event->tickets->count() > 0)
                        <form id="ticketForm" class="space-y-6">
                            @csrf

                            <div class="space-y-3">
                                <label class="text-sm font-semibold text-gray-700 ml-1">Ticket Type</label>
                                @foreach($event->tickets as $index => $ticket)
                                <label class="relative block cursor-pointer group">
                                    <input type="radio" name="ticket_id" value="{{ $ticket->ticket_id }}" {{ $index === 0 ? 'checked' : '' }} class="peer sr-only">
                                    <div
                                        class="p-4 rounded-xl border-2 border-gray-100 bg-gray-50 peer-checked:border-purple-600 peer-checked:bg-purple-50/50 transition-all hover:border-purple-200">
                                        <div class="flex justify-between items-center mb-1">
                                            <span
                                                class="font-bold text-gray-900 peer-checked:text-purple-900">{{ $ticket->name }}</span>
                                            <div
                                                class="w-5 h-5 rounded-full border border-gray-300 peer-checked:bg-purple-600 peer-checked:border-purple-600 flex items-center justify-center text-white opacity-0 peer-checked:opacity-100 transition-opacity">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="text-xl font-bold text-gray-900">Rp
                                            {{ number_format($ticket->price, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>

                            @if($event->days && $event->days->count() > 1)
                            <div>
                                <label class="text-sm font-semibold text-gray-700 ml-1 mb-2 block">Pilih Hari</label>
                                <div class="relative">
                                    <select id="event_day_id" name="event_day_id"
                                        class="w-full pl-4 pr-10 py-3 rounded-xl border-gray-200 bg-gray-50 focus:border-purple-500 focus:ring-purple-500 transition appearance-none cursor-pointer" required>
                                        <option value="">-- Pilih hari --</option>
                                        @foreach($event->days as $idx => $day)
                                        <option value="{{ $day->id }}">{{ $day->label ?? ('Day ' . ($idx+1)) }} · {{ $day->start_at->format('d M Y, H:i') }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            @else
                            <input type="hidden" name="event_day_id" id="event_day_id" value="{{ optional($event->days->first())->id }}" />
                            @endif

                            <div>
                                <label for="quantity"
                                    class="text-sm font-semibold text-gray-700 ml-1 mb-2 block">Quantity</label>
                                <div class="relative">
                                    <select id="quantity" name="quantity"
                                        class="w-full pl-4 pr-10 py-3 rounded-xl border-gray-200 bg-gray-50 focus:border-purple-500 focus:ring-purple-500 transition appearance-none cursor-pointer"
                                        required>
                                    </select>
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div id="emailContainer" class="space-y-2">
                            </div>

                            <div class="pt-4">
                                @auth
                                @if(auth()->user()->role === 'attendee')
                                <button type="button" id="checkoutBtn"
                                    class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-purple-500/30 transform hover:-translate-y-1 transition duration-200 flex justify-center items-center gap-2">
                                    <span>Proceed to Checkout</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                                @else
                                <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 text-center">
                                    <p class="text-sm text-orange-800 mb-3 font-medium">Logged in as
                                        {{ auth()->user()->role }}
                                    </p>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <input type="hidden" name="redirect"
                                            value="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}">
                                        <button type="submit"
                                            class="text-sm text-orange-600 font-bold hover:text-orange-800 underline">Logout
                                            to buy tickets</button>
                                    </form>
                                </div>
                                @endif
                                @else
                                <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 text-center">
                                    <p class="text-blue-800 mb-4 font-medium">Login to purchase tickets</p>
                                    <div class="flex gap-3">
                                        <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}"
                                            class="flex-1 bg-white text-blue-600 border border-blue-200 hover:bg-blue-50 font-bold py-2 rounded-lg transition">Login</a>
                                        <a href="{{ route('register') }}?redirect={{ urlencode(url()->current()) }}"
                                            class="flex-1 bg-blue-600 text-white hover:bg-blue-700 font-bold py-2 rounded-lg transition shadow-md">Register</a>
                                    </div>
                                </div>
                                @endauth
                            </div>
                        </form>
                        @else
                        <div class="text-center py-8">
                            <div
                                class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 font-medium">No tickets available yet.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-white border-t border-gray-100 mt-12 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center text-gray-500">
            &copy; {{ date('Y') }} Kelompok 2. All rights reserved.
        </div>
    </footer>

    <div id="vaModal"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden transition-opacity duration-300">
        <div class="flex items-center justify-center min-h-full p-4">
            <div
                class="relative bg-white w-full max-w-md rounded-3xl shadow-2xl p-6 m-4 transform transition-all scale-100">
                <div class="text-center mb-6">
                    <div
                        class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 text-green-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Virtual Account Created</h3>
                    <p class="text-gray-500 text-sm mt-1">Complete payment to secure your tickets</p>
                </div>

                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 mb-6">
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-bold mb-2">Virtual Account Number</p>
                    <div class="flex items-center justify-between mb-4">
                        <span id="vaNumber" class="text-3xl font-mono font-bold text-gray-900 tracking-wider"></span>
                        <button id="copyVaBtn" class="p-2 hover:bg-gray-200 rounded-lg text-gray-500 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <div class="border-t border-gray-200 pt-4 flex justify-between items-center">
                        <span class="text-gray-500 text-sm">Amount to Pay</span>
                        <span id="vaAmount" class="text-xl font-bold text-purple-600"></span>
                    </div>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Event</span>
                        <span id="vaEventName" class="font-medium text-gray-900 text-right truncate max-w-[200px]"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Expires in</span>
                        <span id="vaExpiredAt" class="font-medium text-red-500 text-right"></span>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button id="vaCancelBtn"
                        class="flex-1 py-3 border border-gray-200 rounded-xl text-gray-600 font-semibold hover:bg-gray-50 transition">Wait</button>
                    <button id="vaContinueBtn"
                        class="flex-1 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-semibold shadow-lg shadow-purple-500/20 transition">Pay
                        Now</button>
                </div>

                <button id="closeVaModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="confirmModal"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden transition-opacity duration-300">
        <div class="flex items-center justify-center min-h-full p-4">
            <div class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl p-8 m-4">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Confirm Order</h3>

                <div class="space-y-4 mb-8">
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <p class="text-sm text-gray-500 mb-1">Event</p>
                        <p class="font-bold text-gray-900 text-lg">{{ $event->event_name }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ $event->event_location }}</p>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-gray-100" id="modalTicketDetails"></div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100" id="modalDay"></div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100" id="modalQuantity">
                    </div>
                    <div class="py-2 border-b border-gray-100">
                        <div id="modalEmails" class="space-y-1"></div>
                    </div>

                    <div class="flex justify-between items-center pt-2">
                        <span class="text-lg font-bold text-gray-900">Total Payment</span>
                        <span id="modalTotal"
                            class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-blue-600"></span>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button id="cancelBtn"
                        class="flex-1 py-3.5 border border-gray-200 rounded-xl text-gray-700 font-bold hover:bg-gray-50 transition">Cancel</button>
                    <button id="confirmBtn"
                        class="flex-1 py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-purple-500/20 transition">
                        Confirm & Pay
                    </button>
                </div>

                <button id="closeModal" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('ticketForm');
            const quantitySelect = document.getElementById('quantity');
            const emailContainer = document.getElementById('emailContainer');
            const metaEl = document.getElementById('eventMeta');
            const userEmail = metaEl?.dataset.userEmail || null;
            const isLoggedIn = (metaEl?.dataset.isLoggedIn || '0') === '1';
            const tickets = metaEl?.dataset.tickets ? JSON.parse(metaEl.dataset.tickets) : [];
            const eventDaySelect = document.getElementById('event_day_id');
            const modalDay = document.getElementById('modalDay');

            const hasDaySelection = !!eventDaySelect;
            const hasMultipleDays = hasDaySelection && eventDaySelect.tagName === 'SELECT' && Array.from(eventDaySelect.options || []).filter(opt => opt.value).length > 1;

            function getSelectedDay() {
                if (!eventDaySelect) return null;
                if (eventDaySelect.tagName === 'SELECT') {
                    const opt = eventDaySelect.options[eventDaySelect.selectedIndex];
                    if (!opt || !opt.value) return null;
                    return {
                        id: opt.value,
                        label: opt.textContent?.trim() || ''
                    };
                }
                if (eventDaySelect.value) {
                    return {
                        id: eventDaySelect.value,
                        label: ''
                    };
                }
                return null;
            }

            // Modal elements
            const modal = document.getElementById('confirmModal');
            const vaModal = document.getElementById('vaModal');
            const checkoutBtn = document.getElementById('checkoutBtn');
            const closeModalBtn = document.getElementById('closeModal');
            const cancelBtn = document.getElementById('cancelBtn');
            const confirmBtn = document.getElementById('confirmBtn');

            // VA Modal elements
            const closeVaModalBtn = document.getElementById('closeVaModal');
            const vaCancelBtn = document.getElementById('vaCancelBtn');
            const vaContinueBtn = document.getElementById('vaContinueBtn');
            const copyVaBtn = document.getElementById('copyVaBtn');
            const vaNumber = document.getElementById('vaNumber');
            const vaEventName = document.getElementById('vaEventName');
            const vaAmount = document.getElementById('vaAmount');
            const vaExpiredAt = document.getElementById('vaExpiredAt');

            const eventId = metaEl ? Number(metaEl.dataset.eventId) : null;
            let paymentUrl = '';
            let paymentExternalId = '';

            // Populate quantity options
            if (quantitySelect) {
                for (let i = 1; i <= 5; i++) {
                    const opt = document.createElement('option');
                    opt.value = String(i);
                    opt.textContent = String(i);
                    quantitySelect.appendChild(opt);
                }
            }

            function rebuildEmailInputs(qty) {
                if (!emailContainer) return;
                emailContainer.innerHTML = '';
                const label = document.createElement('label');
                label.className = 'block text-sm font-semibold text-gray-700 mb-2';
                label.textContent = 'Attendee Emails';
                emailContainer.appendChild(label);

                for (let i = 0; i < qty; i++) {
                    const input = document.createElement('input');
                    input.type = 'email';
                    input.name = 'attendee_emails[]';
                    input.required = true;
                    input.placeholder = `Attendee #${i + 1} Email`;
                    input.className = 'w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 focus:border-purple-500 focus:ring-purple-500 mb-2 transition';
                    if (i === 0 && userEmail) input.value = userEmail;
                    emailContainer.appendChild(input);
                }
            }

            if (quantitySelect) {
                rebuildEmailInputs(1);
                quantitySelect.addEventListener('change', function() {
                    rebuildEmailInputs(parseInt(quantitySelect.value));
                });
            }

            function updateModalContent() {
                const selectedTicketRadio = document.querySelector('input[name="ticket_id"]:checked');
                if (!selectedTicketRadio) {
                    alert('Please select a ticket type');
                    return false;
                }

                const ticketId = parseInt(selectedTicketRadio.value);
                const ticket = tickets.find(t => t.ticket_id === ticketId || t.id === ticketId);

                if (!ticket) return false;

                const quantity = parseInt(quantitySelect.value);
                const emailInputs = document.querySelectorAll('input[name="attendee_emails[]"]');
                const emails = Array.from(emailInputs).map(input => input.value);
                const ticketPrice = parseFloat(ticket.price) || 0;
                const total = ticketPrice * quantity;

                document.getElementById('modalTicketDetails').innerHTML = `
                    <span class="text-gray-500">Type</span>
                    <span class="font-medium text-gray-900">${ticket.name}</span>
                `;

                document.getElementById('modalQuantity').innerHTML = `
                    <span class="text-gray-500">Qty</span>
                    <span class="font-medium text-gray-900">${quantity} x Rp ${ticketPrice.toLocaleString('id-ID')}</span>
                `;

                if (modalDay && hasMultipleDays) {
                    const selectedDay = getSelectedDay();
                    if (selectedDay) {
                        document.getElementById('modalDay').innerHTML = `
                            <span class="text-gray-500">Day</span>
                            <span class="font-medium text-gray-900">${selectedDay.label}</span>
                        `;
                    }
                }

                let emailsHtml = '<p class="text-sm text-gray-500 mb-1">Sent to:</p>';
                emails.forEach((email) => {
                    emailsHtml += `<p class="text-gray-900 text-sm font-medium truncate">${email}</p>`;
                });
                document.getElementById('modalEmails').innerHTML = emailsHtml;
                document.getElementById('modalTotal').textContent = `Rp ${parseInt(total).toLocaleString('id-ID')}`;

                return true;
            }

            function showModal() {
                if (updateModalContent()) {
                    modal.classList.remove('hidden');
                }
            }

            function hideModal() {
                modal.classList.add('hidden');
            }

            if (checkoutBtn) {
                checkoutBtn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }
                    // Optional: Check attendee Logic here (omitted for brevity, same as original)
                    showModal();
                });
            }

            if (closeModalBtn) closeModalBtn.addEventListener('click', hideModal);
            if (cancelBtn) cancelBtn.addEventListener('click', hideModal);
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) hideModal();
                });
            }

            if (confirmBtn) {
                confirmBtn.addEventListener('click', async function() {
                    const formData = new FormData();
                    formData.append('event_id', eventId);
                    formData.append('ticket_id', document.querySelector('input[name="ticket_id"]:checked').value);
                    formData.append('quantity', quantitySelect.value);
                    document.querySelectorAll('input[name="attendee_emails[]"]').forEach(inp => formData.append('attendee_emails[]', inp.value));

                    if (eventDaySelect && eventDaySelect.value) {
                        formData.append('event_day_id', eventDaySelect.value);
                    }

                    confirmBtn.disabled = true;
                    confirmBtn.textContent = 'Processing...';

                    try {
                        const response = await fetch('/payment/create-va', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        });
                        const data = await response.json();

                        if (data.status === 'success') {
                            modal.classList.add('hidden');
                            paymentUrl = data.data.payment_url;
                            paymentExternalId = data.data.external_id;

                            vaNumber.textContent = data.data.va_number;
                            vaEventName.textContent = data.data.event_name;
                            vaAmount.textContent = 'Rp ' + parseInt(data.data.amount).toLocaleString('id-ID');
                            const expiredDate = new Date(data.data.expired_at);
                            vaExpiredAt.textContent = expiredDate.toLocaleString('id-ID', {
                                dateStyle: 'full',
                                timeStyle: 'short'
                            });

                            vaModal.classList.remove('hidden');
                        } else {
                            alert('Failed: ' + (data.message || 'Unknown error'));
                        }
                    } catch (error) {
                        alert('Error: ' + error.message);
                    } finally {
                        confirmBtn.disabled = false;
                        confirmBtn.textContent = 'Confirm & Pay';
                    }
                });
            }

            // Copy VA
            if (copyVaBtn) {
                copyVaBtn.addEventListener('click', function() {
                    navigator.clipboard.writeText(vaNumber.textContent).then(() => {
                        const originalHtml = copyVaBtn.innerHTML;
                        copyVaBtn.innerHTML = '<span class="text-green-500 font-bold text-sm">Copied!</span>';
                        setTimeout(() => copyVaBtn.innerHTML = originalHtml, 2000);
                    });
                });
            }

            // VA Modal handlers
            if (closeVaModalBtn) closeVaModalBtn.addEventListener('click', () => vaModal.classList.add('hidden'));
            if (vaCancelBtn) vaCancelBtn.addEventListener('click', () => vaModal.classList.add('hidden'));
            if (vaContinueBtn) {
                vaContinueBtn.addEventListener('click', function() {
                    if (paymentUrl) {
                        window.open(paymentUrl, '_blank');
                        startPaymentStatusPolling(vaNumber.textContent);
                    }
                });
            }

            // Polling Logic
            function startPaymentStatusPolling(vaNumber) {
                let pollCount = 0;
                const pollInterval = setInterval(async function() {
                    pollCount++;
                    try {
                        const response = await fetch(`/payment/check-status/${vaNumber}`);
                        const data = await response.json();
                        if (data.status === 'success' && data.data && (data.data.is_paid || data.data.gateway_status === 'paid')) {
                            clearInterval(pollInterval);
                            window.location.href = '{{ url("/payment/success") }}/' + paymentExternalId;
                        }
                        if (pollCount >= 60) clearInterval(pollInterval); // Stop after 5 mins
                    } catch (e) {
                        console.error(e);
                    }
                }, 5000);
            }

            // Photo Gallery Navigation
            const mainPhoto = document.getElementById('mainPhoto');
            if (mainPhoto) {
                const photoPrevBtn = document.getElementById('photoPrevBtn');
                const photoNextBtn = document.getElementById('photoNextBtn');
                const photoCounter = document.getElementById('photoCounter');
                const photoThumbnails = document.querySelectorAll('.photo-thumbnail');
                let currentPhotoIndex = 0;
                const totalPhotos = photoThumbnails.length;

                function updateMainPhoto(index) {
                    const thumbnail = photoThumbnails[index];
                    const photoUrl = thumbnail.dataset.photoUrl;
                    mainPhoto.src = photoUrl;
                    mainPhoto.style.opacity = '0';
                    setTimeout(() => mainPhoto.style.opacity = '1', 50);

                    // Update active thumbnail
                    photoThumbnails.forEach(t => t.classList.remove('border-purple-600'));
                    photoThumbnails.forEach(t => t.classList.add('border-gray-200'));
                    thumbnail.classList.remove('border-gray-200');
                    thumbnail.classList.add('border-purple-600');

                    if (photoCounter) photoCounter.textContent = index + 1;
                    currentPhotoIndex = index;
                }

                if (photoPrevBtn) {
                    photoPrevBtn.addEventListener('click', () => {
                        currentPhotoIndex = (currentPhotoIndex - 1 + totalPhotos) % totalPhotos;
                        updateMainPhoto(currentPhotoIndex);
                    });
                }

                if (photoNextBtn) {
                    photoNextBtn.addEventListener('click', () => {
                        currentPhotoIndex = (currentPhotoIndex + 1) % totalPhotos;
                        updateMainPhoto(currentPhotoIndex);
                    });
                }

                photoThumbnails.forEach((thumbnail, index) => {
                    thumbnail.addEventListener('click', () => {
                        updateMainPhoto(index);
                    });
                });

                // Add smooth transition to main photo
                mainPhoto.style.transition = 'opacity 0.3s ease-in-out';
            }
        });
    </script>
</body>

</html>