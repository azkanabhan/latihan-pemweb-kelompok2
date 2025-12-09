<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-yellow-50 text-yellow-600">
                ⭐
            </span>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Beri Review & Rating') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-slate-50 via-white to-white">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            {{-- Event Info Card --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-200 mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        {{ $ticket->event->event_name ?? 'Unknown Event' }}
                    </h3>
                    <p class="text-sm text-gray-600 flex items-center gap-1 mb-1">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13 21.314l-4.657-4.657A8 8 0 1117.657 16.657z" />
                        </svg>
                        {{ $ticket->event->event_location ?? 'Location TBA' }}
                    </p>
                    <p class="text-sm text-gray-600 flex items-center gap-1">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ \Carbon\Carbon::parse($ticket->event->start_date)->format('d M Y, H:i') ?? 'TBA' }}
                    </p>
                </div>
            </div>

            {{-- Review Form --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-200">
                <div class="p-6">
                    <form method="POST" action="{{ route('tickets.review.store', $ticket->ticket_holder_id) }}" id="reviewForm">
                        @csrf

                        {{-- Rating Section --}}
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <span class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                    Rating
                                </span>
                            </label>
                            <div class="flex items-center gap-2 mb-2">
                                <div id="starRating" class="flex gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button" 
                                                class="star-btn text-3xl text-gray-300 hover:text-yellow-400 transition-colors focus:outline-none"
                                                data-rating="{{ $i }}">
                                            ★
                                        </button>
                                    @endfor
                                </div>
                                <span id="ratingText" class="text-sm text-gray-600 ml-2">Pilih rating</span>
                            </div>
                            <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating', '') }}" required>
                            @error('rating')
                                <div class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Review Text Section --}}
                        <div class="mb-6">
                            <label for="body" class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Review
                                </span>
                            </label>
                            <textarea 
                                id="body" 
                                name="body" 
                                rows="6"
                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none resize-none"
                                placeholder="Bagikan pengalaman Anda tentang event ini... (minimal 10 karakter)"
                                required
                            >{{ old('body', '') }}</textarea>
                            <div class="mt-1 flex justify-between items-center">
                                <p class="text-xs text-gray-500">Minimal 10 karakter, maksimal 1000 karakter</p>
                                <span id="charCount" class="text-xs text-gray-500">0 / 1000</span>
                            </div>
                            @error('body')
                                <div class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('tickets.index') }}"
                                class="px-6 py-2.5 border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-2.5 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-600 shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Kirim Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const starButtons = document.querySelectorAll('.star-btn');
            const ratingInput = document.getElementById('ratingInput');
            const ratingText = document.getElementById('ratingText');
            const bodyTextarea = document.getElementById('body');
            const charCount = document.getElementById('charCount');

            let selectedRating = 0;

            // Star rating functionality
            starButtons.forEach((btn, index) => {
                btn.addEventListener('click', function() {
                    const rating = parseInt(this.dataset.rating);
                    selectedRating = rating;
                    ratingInput.value = rating;
                    
                    // Update star display
                    starButtons.forEach((star, idx) => {
                        if (idx < rating) {
                            star.classList.remove('text-gray-300');
                            star.classList.add('text-yellow-400');
                        } else {
                            star.classList.remove('text-yellow-400');
                            star.classList.add('text-gray-300');
                        }
                    });

                    // Update rating text
                    const ratingLabels = {
                        1: 'Sangat Buruk',
                        2: 'Buruk',
                        3: 'Cukup',
                        4: 'Baik',
                        5: 'Sangat Baik'
                    };
                    ratingText.textContent = ratingLabels[rating] || 'Pilih rating';
                });

                btn.addEventListener('mouseenter', function() {
                    const rating = parseInt(this.dataset.rating);
                    starButtons.forEach((star, idx) => {
                        if (idx < rating) {
                            star.classList.remove('text-gray-300');
                            star.classList.add('text-yellow-300');
                        }
                    });
                });

                btn.addEventListener('mouseleave', function() {
                    starButtons.forEach((star, idx) => {
                        if (idx < selectedRating) {
                            star.classList.remove('text-gray-300', 'text-yellow-300');
                            star.classList.add('text-yellow-400');
                        } else {
                            star.classList.remove('text-yellow-300', 'text-yellow-400');
                            star.classList.add('text-gray-300');
                        }
                    });
                });
            });

            // Character count for review text
            bodyTextarea.addEventListener('input', function() {
                const length = this.value.length;
                charCount.textContent = `${length} / 1000`;
                
                if (length > 1000) {
                    charCount.classList.add('text-red-600');
                    charCount.classList.remove('text-gray-500');
                } else {
                    charCount.classList.remove('text-red-600');
                    charCount.classList.add('text-gray-500');
                }
            });

            // Initialize character count
            if (bodyTextarea.value) {
                bodyTextarea.dispatchEvent(new Event('input'));
            }

            // Form validation
            document.getElementById('reviewForm').addEventListener('submit', function(e) {
                if (!ratingInput.value) {
                    e.preventDefault();
                    alert('Silakan pilih rating terlebih dahulu.');
                    return false;
                }
            });
        });
    </script>
</x-app-layout>

