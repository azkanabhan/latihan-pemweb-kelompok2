<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $event->event_name }} - {{ config('app.name', 'Laravel') }}</title>
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

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <a href="{{ url('/') }}" class="inline-flex items-center text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300 mb-6 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Events
            </a>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <!-- Hero Image -->
                <div class="h-96 bg-gradient-to-br from-purple-400 to-indigo-600 dark:from-purple-600 dark:to-indigo-800 relative">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-48 h-48 text-white opacity-20" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                        </svg>
                    </div>

                    <!-- Status Badge -->
                    <div class="absolute top-6 right-6">
                        @php
                            $status = $event->availability_status;
                        @endphp
                        @if($status === 'open')
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                Open
                            </span>
                        @else
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                Fully Booked
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-8">
                    <!-- Event Title -->
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ $event->event_name }}
                    </h1>

                    <!-- Event Meta -->
                    <div class="flex flex-wrap items-center gap-6 mb-8 text-gray-600 dark:text-gray-400">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $event->event_location }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $event->event_date->format('F d, Y \a\t g:i A') }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $event->booked_capacity }} / {{ $event->event_capacity }} tickets sold
                        </div>
                    </div>

                    <!-- Two Column Layout: Details on Left, Pricing Card on Right -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Left Column: Event Details -->
                        <div class="lg:col-span-2 space-y-8">
                            <!-- Description -->
                            @if($event->event_description)
                                <div class="mb-8">
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">About This Event</h2>
                                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap text-left">
                                        {{ $event->event_description }}
                                    </p>
                                </div>
                            @endif

                            <!-- Event Organizer -->
                            @if($event->creator && $event->creator->user)
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Event Organizer</h2>
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="h-12 w-12 rounded-full bg-gradient-to-br from-purple-400 to-indigo-600 flex items-center justify-center">
                                                <span class="text-white font-semibold text-lg">
                                                    {{ strtoupper(substr($event->creator->user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $event->creator->user->name }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Event Creator
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Right Column: Pricing Card -->
                        <div class="lg:col-span-1">
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 sticky top-8">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Get Your Tickets</h2>

                                @if($event->tickets->count() > 0)
                                    <form id="ticketForm" class="space-y-6">
                                        @csrf

                                        <!-- Ticket Type Selection -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                                Select Ticket Type
                                            </label>
                                            <div class="space-y-3">
                                                @foreach($event->tickets as $index => $ticket)
                                                    <label class="flex items-center justify-between p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:border-purple-500 dark:hover:border-purple-500 transition has-[:checked]:border-purple-500 dark:has-[:checked]:border-purple-500 has-[:checked]:bg-purple-50 dark:has-[:checked]:bg-purple-900/20">
                                                        <div class="flex items-center">
                                                            <input type="radio" name="ticket_id" value="{{ $ticket->ticket_id }}" {{ $index === 0 ? 'checked' : '' }} class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 dark:border-gray-600" required>
                                                            <div class="ml-3">
                                                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $ticket->name }}</p>
                                                                <p class="text-xs text-gray-500 dark:text-gray-400">Available</p>
                                                            </div>
                                                        </div>
                                                        <span class="text-lg font-bold text-gray-900 dark:text-white">
                                                            Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Quantity Selection -->
                                        <div>
                                            <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Quantity
                                            </label>
                                            <select id="quantity" name="quantity" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                                                <option value="1">1</option>
                                            </select>
                                        </div>

                                        <!-- Email Input Container -->
                                        <div id="emailContainer">
                                            <label for="attendee_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Attendee Email
                                            </label>
                                            <input type="email" name="attendee_emails[]" id="attendee_email" 
                                                value="{{ auth()->check() ? auth()->user()->email : '' }}"
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                                placeholder="Enter your email"
                                                required>
                                        </div>

                                        <!-- Checkout Button -->
                                        @auth
                                            @if(auth()->user()->role === 'attendee')
                                                <!-- Checkout button for attendees -->
                                                <button type="button" id="checkoutBtn" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl">
                                                    Checkout
                                                </button>
                                            @else
                                                <!-- Logout prompt for admin/creator to switch to attendee -->
                                                <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4 text-center">
                                                    <p class="text-sm text-orange-800 dark:text-orange-300 mb-3">
                                                        You're logged in as {{ auth()->user()->role }}. Please logout to continue as attendee
                                                    </p>
                                                    <div class="space-y-2">
                                                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                                                            @csrf
                                                            <input type="hidden" name="redirect" value="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}">
                                                            
                                                            <button type="submit" 
                                                                class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded-lg transition cursor-pointer">
                                                                Logout & Continue as Attendee
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <!-- Login/Register Prompt for Guests -->
                                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 text-center">
                                                <p class="text-sm text-blue-800 dark:text-blue-300 mb-3">
                                                    Please login or register to continue with your ticket purchase
                                                </p>
                                                <div class="flex gap-2">
                                                    <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                                                        Login
                                                    </a>
                                                    <a href="{{ route('register') }}?redirect={{ urlencode(url()->current()) }}" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition">
                                                        Register
                                                    </a>
                                                </div>
                                            </div>
                                        @endauth
                                    </form>
                                @else
                                    <p class="text-gray-600 dark:text-gray-400">No tickets available yet.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <p class="text-center text-gray-600 dark:text-gray-400">
                    &copy; {{ date('Y') }} Kelompok 2. All rights reserved.
                </p>
            </div>
        </footer>

        <!-- VA Success Modal -->
        <div id="vaModal" class="fixed flex items-center justify-center inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative bg-white dark:bg-gray-800 w-11/12 md:w-2/3 lg:w-1/2 max-h-[90vh] rounded-lg shadow-lg overflow-y-auto p-5">
                <!-- Modal Content -->
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Payment Virtual Account</h3>
                        <button id="closeVaModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- VA Number Display -->
                    <div class="bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-lg p-6 mb-4 border border-purple-200 dark:border-purple-800">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Virtual Account Number</p>
                        <div class="flex items-center justify-between">
                            <span id="vaNumber" class="text-2xl font-bold text-gray-900 dark:text-white font-mono tracking-wider"></span>
                            <button id="copyVaBtn" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <span>Copy</span>
                            </button>
                        </div>
                    </div>

                    <!-- VA Details -->
                    <div class="space-y-4 mb-6">
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Event</p>
                            <p id="vaEventName" class="text-lg font-semibold text-gray-900 dark:text-white"></p>
                        </div>

                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Amount</p>
                            <p id="vaAmount" class="text-2xl font-bold text-purple-600 dark:text-purple-400"></p>
                        </div>

                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Valid Until</p>
                            <p id="vaExpiredAt" class="text-gray-700 dark:text-gray-300"></p>
                        </div>

                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Important</p>
                                    <p class="text-sm text-yellow-700 dark:text-yellow-400 mt-1">Please complete your payment before the expiration time. Your booking will be cancelled if payment is not received.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex space-x-3 mt-6">
                        <button id="vaCancelBtn" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            Back to Event
                        </button>
                        <button id="vaContinueBtn" class="flex-1 px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-lg transition shadow-lg hover:shadow-xl">
                            Make Payment
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div id="confirmModal" class="fixed flex items-center justify-center inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative bg-white dark:bg-gray-800 w-11/12 md:w-2/3 lg:w-1/2 max-h-[90vh] rounded-lg shadow-lg overflow-y-auto p-5">
                <!-- Modal Content -->
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Confirm Your Order</h3>
                        <button id="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Order Summary -->
                    <div class="space-y-4 mb-6">
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Event</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $event->event_name }}</p>
                        </div>

                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Date & Location</p>
                            <p class="text-gray-700 dark:text-gray-300">{{ $event->event_date->format('F d, Y \a\t g:i A') }}</p>
                            <p class="text-gray-700 dark:text-gray-300">{{ $event->event_location }}</p>
                        </div>

                        <div id="modalTicketDetails" class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <!-- Ticket details will be populated by JavaScript -->
                        </div>

                        <div id="modalQuantity" class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <!-- Quantity details will be populated by JavaScript -->
                        </div>

                        <div id="modalEmails" class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <!-- Email attendees will be populated by JavaScript -->
                        </div>

                        <div class="flex justify-between items-center pt-2">
                            <span class="text-xl font-bold text-gray-900 dark:text-white">Total</span>
                            <span id="modalTotal" class="text-2xl font-bold text-purple-600 dark:text-purple-400">Rp 0</span>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex space-x-3 mt-6">
                        <button id="cancelBtn" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            Cancel
                        </button>
                        <button id="confirmBtn" class="flex-1 px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-lg transition shadow-lg hover:shadow-xl">
                            Confirm Payment
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('ticketForm');
                const quantitySelect = document.getElementById('quantity');
                const userEmail = @json(auth()->check() ? auth()->user()->email : null);
                const isLoggedIn = @json(auth()->check());
                const tickets = @json($event->tickets);

                // Modal elements
                const modal = document.getElementById('confirmModal');
                const vaModal = document.getElementById('vaModal');
                const checkoutBtn = document.getElementById('checkoutBtn'); // May be null for admin/creator
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

                const eventId = {{ $event->event_id }};
                let paymentUrl = '';

                // No need for updateEmailInputs since we only have 1 email field now

                function updateModalContent() {
                    // Get selected ticket
                    const selectedTicketRadio = document.querySelector('input[name="ticket_id"]:checked');
                    if (!selectedTicketRadio) {
                        alert('Please select a ticket type');
                        return false;
                    }

                    const ticketId = parseInt(selectedTicketRadio.value);
                    // Try to find by ticket_id or id (Laravel might serialize primary key as "id")
                    const ticket = tickets.find(t => t.ticket_id === ticketId || t.id === ticketId);

                    if (!ticket) {
                        console.error('Ticket not found. Looking for ticket_id:', ticketId);
                        console.error('Available tickets:', tickets);
                        alert('Ticket not found. Please try again.');
                        return false;
                    }

                    // Get quantity
                    const quantity = parseInt(quantitySelect.value);

                    // Get attendee emails
                    const emailInputs = document.querySelectorAll('input[name="attendee_emails[]"]');
                    const emails = Array.from(emailInputs).map(input => input.value);

                    // Calculate total (ensure price is a number)
                    const ticketPrice = parseFloat(ticket.price) || 0;
                    const total = ticketPrice * quantity;

                    // Populate modal
                    document.getElementById('modalTicketDetails').innerHTML = `
                        <p class="text-sm text-gray-500 dark:text-gray-400">Ticket</p>
                        <p class="text-gray-700 dark:text-gray-300">${ticket.name}</p>
                        <p class="text-gray-700 dark:text-gray-300">Rp ${ticketPrice.toLocaleString('id-ID')} × ${quantity}</p>
                    `;

                    document.getElementById('modalQuantity').innerHTML = `
                        <p class="text-sm text-gray-500 dark:text-gray-400">Quantity</p>
                        <p class="text-gray-700 dark:text-gray-300">${quantity} ticket(s)</p>
                    `;

                    let emailsHtml = '<p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Attendee Emails</p>';
                    emails.forEach((email, index) => {
                        emailsHtml += `<p class="text-gray-700 dark:text-gray-300 text-sm">${index + 1}. ${email}</p>`;
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

                // Event listeners (only if checkoutBtn exists)
                if (checkoutBtn) {
                    checkoutBtn.addEventListener('click', function(e) {
                        e.preventDefault();

                        // Validate form
                        if (!form.checkValidity()) {
                            form.reportValidity();
                            return;
                        }

                        showModal();
                    });
                }

                closeModalBtn.addEventListener('click', hideModal);
                cancelBtn.addEventListener('click', hideModal);

                // Close modal when clicking outside
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        hideModal();
                    }
                });

                confirmBtn.addEventListener('click', async function() {
                    const formData = new FormData();
                    formData.append('event_id', eventId);
                    formData.append('ticket_id', document.querySelector('input[name="ticket_id"]:checked').value);
                    formData.append('quantity', '1');

                    const attendeeEmail = document.getElementById('attendee_email').value;
                    formData.append('attendee_emails[]', attendeeEmail);

                    // Show loading state
                    confirmBtn.disabled = true;
                    confirmBtn.textContent = 'Processing...';

                    try {
                        const response = await fetch('{{ route("payment.create-va") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (data.status === 'success') {
                            // Hide confirmation modal
                            modal.classList.add('hidden');

                            // Save payment URL
                            paymentUrl = data.data.payment_url;

                            // Populate VA modal
                            vaNumber.textContent = data.data.va_number;
                            vaEventName.textContent = data.data.event_name;
                            vaAmount.textContent = 'Rp ' + parseInt(data.data.amount).toLocaleString('id-ID');

                            // Format expired date
                            const expiredDate = new Date(data.data.expired_at);
                            vaExpiredAt.textContent = expiredDate.toLocaleString('id-ID', {
                                dateStyle: 'full',
                                timeStyle: 'short'
                            });

                            // Show VA modal
                            vaModal.classList.remove('hidden');
                        } else {
                            console.error('Payment Error:', data);
                            const errorMsg = data.message || data.error || 'Unknown error occurred';
                            alert('Failed to create virtual account:\n' + errorMsg);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        console.error('Error details:', error.message);
                        alert('An error occurred while processing your request:\n' + error.message);
                    } finally {
                        confirmBtn.disabled = false;
                        confirmBtn.textContent = 'Confirm Payment';
                    }
                });

                // Copy VA number functionality
                copyVaBtn.addEventListener('click', function() {
                    const vaNum = vaNumber.textContent;
                    navigator.clipboard.writeText(vaNum).then(() => {
                        const originalText = copyVaBtn.innerHTML;
                        copyVaBtn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg><span>Copied!</span>';
                        setTimeout(() => {
                            copyVaBtn.innerHTML = originalText;
                        }, 2000);
                    });
                });

                // VA Modal close handlers
                closeVaModalBtn.addEventListener('click', function() {
                    vaModal.classList.add('hidden');
                });

                vaCancelBtn.addEventListener('click', function() {
                    vaModal.classList.add('hidden');
                });

                vaContinueBtn.addEventListener('click', function() {
                    if (paymentUrl) {
                        window.open(paymentUrl, '_blank');

                        // Start polling payment status every 5 seconds
                        const vaNum = vaNumber.textContent;
                        startPaymentStatusPolling(vaNum);
                    } else {
                        alert('Payment URL is not available');
                    }
                });

                // Function to poll payment status
                function startPaymentStatusPolling(vaNumber) {
                    let pollCount = 0;
                    const maxPolls = 120; // 120 polls * 5 seconds = 10 minutes max

                    const pollInterval = setInterval(async function() {
                        pollCount++;

                        try {
                            const response = await fetch(`{{ url('/payment/check-status') }}/${vaNumber}`);
                            const data = await response.json();

                            if (data.status === 'success' && data.data) {
                                const isPaid = data.data.is_paid;
                                const gatewayStatus = data.data.gateway_status;

                                console.log(`Poll #${pollCount}: Status ${gatewayStatus}, Paid: ${isPaid}`);

                                if (isPaid || gatewayStatus === 'paid') {
                                    // Payment successful
                                    clearInterval(pollInterval);

                                    // Show success message
                                    alert('Payment successful! Your ticket is now active.');

                                    // Close modal and redirect to dashboard or reload
                                    vaModal.classList.add('hidden');
                                    if (isLoggedIn) {
                                        window.location.href = '{{ route("dashboard") }}';
                                    } else {
                                        window.location.href = '/';
                                    }
                                } else if (gatewayStatus === 'expired' || gatewayStatus === 'cancelled') {
                                    // Payment expired or cancelled
                                    clearInterval(pollInterval);
                                    alert('Payment has expired or was cancelled. Please create a new payment.');
                                }
                            }

                            // Stop polling after max attempts
                            if (pollCount >= maxPolls) {
                                clearInterval(pollInterval);
                                console.log('Polling stopped: Maximum attempts reached');
                            }

                        } catch (error) {
                            console.error('Error polling payment status:', error);
                            // Continue polling on error
                        }
                    }, 5000); // Poll every 5 seconds

                    // Store interval ID to allow manual stop if needed
                    window.paymentPollInterval = pollInterval;
                }

                // No need for initial update or quantity change listener
            });
        </script>
    </body>
</html>

