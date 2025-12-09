<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('QR Code Scanner') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Toast Notifications --}}
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-lg flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>{{ session('warning') }}</span>
                        </div>
                    @endif

                    {{-- Main Content --}}
                    <div class="space-y-6">
                        <div class="text-center mb-6">
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">Scan QR Code Tiket</h3>
                            <p class="text-gray-600">Arahkan kamera ke QR code tiket untuk validasi otomatis</p>
                        </div>

                        {{-- Camera Scanner Section (Primary) --}}
                        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 border-2 border-indigo-300 rounded-xl p-6 shadow-lg">
                            <div class="flex items-center justify-between mb-4">
                                <label class="block text-lg font-bold text-gray-800">
                                    <span class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Scanner Kamera
                                    </span>
                                </label>
                                <button 
                                    type="button" 
                                    id="toggleCameraBtn"
                                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors shadow-md"
                                >
                                    <span id="cameraBtnText">Mulai Scan</span>
                                </button>
                            </div>
                            
                            <div id="cameraContainer" class="hidden">
                                <div class="relative">
                                    <video id="video" class="w-full rounded-lg border-4 border-indigo-400 shadow-2xl" autoplay playsinline></video>
                                    <div id="scanOverlay" class="absolute inset-0 pointer-events-none">
                                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                            <div class="w-64 h-64 border-4 border-green-400 rounded-lg shadow-lg animate-pulse" style="box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);"></div>
                                        </div>
                                    </div>
                                    <div id="scanSuccess" class="hidden absolute inset-0 bg-green-500 bg-opacity-75 flex items-center justify-center rounded-lg">
                                        <div class="text-center text-white">
                                            <svg class="h-16 w-16 mx-auto mb-2 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <p class="text-xl font-bold">QR Code Terdeteksi!</p>
                                            <p class="text-sm mt-1">Memproses validasi...</p>
                                        </div>
                                    </div>
                                </div>
                                <canvas id="canvas" class="hidden"></canvas>
                                <div class="mt-4 text-center">
                                    <p class="text-sm font-medium text-gray-700 mb-2">
                                        <span class="inline-flex items-center gap-1">
                                            <span id="scanStatus" class="text-indigo-600">Arahkan kamera ke QR code tiket</span>
                                        </span>
                                    </p>
                                    <button 
                                        type="button" 
                                        id="stopCameraBtn"
                                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-md"
                                    >
                                        Hentikan Scanner
                                    </button>
                                </div>
                            </div>
                            
                            <div id="cameraPlaceholder" class="text-center py-12 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <p class="text-gray-600 font-medium">Klik "Mulai Scan" untuk mengaktifkan kamera</p>
                            </div>
                        </div>

                        {{-- QR Code Scanner Form (Hidden for auto-submit) --}}
                        <form method="POST" action="{{ route('creator.qrcode.scan') }}" id="qrCodeForm" class="hidden">
                            @csrf
                            <input type="hidden" id="qr_code" name="qr_code" value="" required />
                        </form>

                        {{-- PDF Upload Section --}}
                        <div class="bg-gradient-to-br from-amber-50 to-orange-50 border-2 border-amber-300 rounded-xl p-6 shadow-md">
                            <div class="flex items-center justify-between mb-4">
                                <label class="block text-lg font-bold text-gray-800">
                                    <span class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        Scan dari PDF Tiket
                                    </span>
                                </label>
                            </div>
                            
                            <div id="pdfUploadArea" class="border-2 border-dashed border-amber-400 rounded-lg p-8 text-center bg-white hover:bg-amber-50 transition-colors cursor-pointer">
                                <input type="file" id="pdfFileInput" accept=".pdf" class="hidden" />
                                <div id="pdfUploadContent">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-amber-500 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <p class="text-gray-700 font-medium mb-1">Klik atau seret file PDF tiket ke sini</p>
                                    <p class="text-sm text-gray-500">Format: PDF (maks. 5MB)</p>
                                </div>
                                <div id="pdfProcessing" class="hidden">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-amber-600"></div>
                                        <p class="text-gray-700 font-medium">Memproses PDF...</p>
                                        <p id="pdfStatus" class="text-sm text-gray-500">Membaca file PDF</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="pdfPreview" class="hidden mt-4">
                                <div class="bg-white rounded-lg border border-amber-200 p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="text-sm font-semibold text-gray-700">File PDF:</p>
                                        <button type="button" id="clearPdfBtn" class="text-red-600 hover:text-red-700 text-sm">
                                            Hapus
                                        </button>
                                    </div>
                                    <p id="pdfFileName" class="text-sm text-gray-600"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Manual Input Section (Alternative) --}}
                        <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-6">
                            <div class="flex items-center justify-between mb-3">
                                <label for="qr_code_manual" class="block text-sm font-semibold text-gray-700">
                                    <span class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                        </svg>
                                        Atau Masukkan QR Code Manual
                                    </span>
                                </label>
                            </div>
                            <div class="flex gap-2">
                                <input 
                                    type="text" 
                                    id="qr_code_manual" 
                                    value="{{ old('qr_code') }}"
                                    placeholder="QR-XXXXX-XXXXX"
                                    class="flex-1 border-2 border-gray-300 rounded-lg px-4 py-3 text-lg font-mono focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none"
                                />
                                <button 
                                    type="button"
                                    id="submitManualBtn"
                                    class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors shadow-md"
                                >
                                    Validasi
                                </button>
                            </div>
                            @error('qr_code')
                                <div class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Information Section --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="text-sm text-blue-800">
                                    <p class="font-semibold mb-1">Informasi:</p>
                                    <ul class="list-disc list-inside space-y-1 text-blue-700">
                                        <li>Hanya QR code dari event yang Anda miliki yang dapat divalidasi</li>
                                        <li>Status tiket akan diubah menjadi "used" setelah divalidasi</li>
                                        <li>Tiket yang sudah digunakan tidak dapat divalidasi lagi</li>
                                        <li>Anda dapat scan QR code dengan 3 cara: Kamera langsung, Upload PDF tiket, atau Input manual</li>
                                        <li>PDF tiket akan dipindai secara otomatis untuk mencari QR code di semua halaman</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- QR Code Scanner Library --}}
    <script src="https://cdn.jsdelivr.net/npm/qr-scanner@1.4.2/qr-scanner.umd.min.js"></script>
    {{-- PDF.js Library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    {{-- jsQR Library for static image QR scanning --}}
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleCameraBtn = document.getElementById('toggleCameraBtn');
            const cameraBtnText = document.getElementById('cameraBtnText');
            const cameraContainer = document.getElementById('cameraContainer');
            const cameraPlaceholder = document.getElementById('cameraPlaceholder');
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const qrCodeInput = document.getElementById('qr_code');
            const qrCodeManualInput = document.getElementById('qr_code_manual');
            const stopCameraBtn = document.getElementById('stopCameraBtn');
            const qrCodeForm = document.getElementById('qrCodeForm');
            const scanStatus = document.getElementById('scanStatus');
            const scanSuccess = document.getElementById('scanSuccess');
            const submitManualBtn = document.getElementById('submitManualBtn');
            
            let qrScanner = null;
            let isCameraActive = false;
            let isProcessing = false;
            let isPdfProcessing = false;

            // Auto-start camera on page load (only if not processing PDF)
            // Delay increased to allow user to interact with PDF upload first
            let cameraAutoStartTimeout = null;
            if (QrScanner.hasCamera()) {
                cameraAutoStartTimeout = setTimeout(() => {
                    if (!isPdfProcessing && !isCameraActive) {
                        startCamera();
                    }
                }, 2000); // Increased delay to 2 seconds
            }

            // Toggle camera
            toggleCameraBtn.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent event bubbling
                
                // Cancel auto-start camera if user manually clicks
                if (cameraAutoStartTimeout) {
                    clearTimeout(cameraAutoStartTimeout);
                    cameraAutoStartTimeout = null;
                }
                
                if (!isCameraActive) {
                    // Make sure PDF is not processing
                    if (isPdfProcessing) {
                        alert('Tunggu hingga proses PDF selesai.');
                        return;
                    }
                    startCamera();
                } else {
                    stopCamera();
                }
            });

            // Stop camera button
            stopCameraBtn.addEventListener('click', function() {
                stopCamera();
            });

            // Manual submit button
            submitManualBtn.addEventListener('click', function() {
                const manualCode = qrCodeManualInput.value.trim();
                if (manualCode) {
                    qrCodeInput.value = manualCode;
                    qrCodeForm.submit();
                } else {
                    alert('Silakan masukkan QR code terlebih dahulu.');
                }
            });

            // Enter key on manual input
            qrCodeManualInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    submitManualBtn.click();
                }
            });

            function startCamera() {
                if (!QrScanner.hasCamera()) {
                    alert('Kamera tidak tersedia di perangkat Anda.');
                    return;
                }

                if (isProcessing) {
                    return;
                }

                qrScanner = new QrScanner(
                    video,
                    result => {
                        // Prevent multiple submissions
                        if (isProcessing) {
                            return;
                        }

                        isProcessing = true;
                        const qrCode = result.data.trim();
                        
                        // Show success overlay
                        scanSuccess.classList.remove('hidden');
                        scanStatus.textContent = 'QR Code terdeteksi! Memproses...';
                        scanStatus.classList.remove('text-indigo-600');
                        scanStatus.classList.add('text-green-600', 'font-bold');

                        // Stop camera
                        if (qrScanner) {
                            qrScanner.stop();
                        }

                        // Set QR code value and submit
                        qrCodeInput.value = qrCode;
                        
                        // Small delay to show success message
                        setTimeout(() => {
                            qrCodeForm.submit();
                        }, 500);
                    },
                    {
                        returnDetailedScanResult: true,
                        highlightScanRegion: true,
                        highlightCodeOutline: true,
                        preferredCamera: 'environment', // Use back camera on mobile
                    }
                );

                qrScanner.start().then(() => {
                    isCameraActive = true;
                    cameraContainer.classList.remove('hidden');
                    cameraPlaceholder.classList.add('hidden');
                    cameraBtnText.textContent = 'Nonaktifkan Scanner';
                    toggleCameraBtn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                    toggleCameraBtn.classList.add('bg-red-600', 'hover:bg-red-700');
                    scanStatus.textContent = 'Mencari QR code...';
                    scanStatus.classList.add('text-indigo-600');
                }).catch(err => {
                    console.error('Error starting camera:', err);
                    alert('Gagal mengakses kamera. Pastikan Anda memberikan izin akses kamera.');
                    cameraPlaceholder.classList.remove('hidden');
                    cameraContainer.classList.add('hidden');
                });
            }

            function stopCamera() {
                if (qrScanner) {
                    qrScanner.stop();
                    qrScanner.destroy();
                    qrScanner = null;
                }
                isCameraActive = false;
                isProcessing = false;
                cameraContainer.classList.add('hidden');
                cameraPlaceholder.classList.remove('hidden');
                cameraBtnText.textContent = 'Mulai Scan';
                toggleCameraBtn.classList.remove('bg-red-600', 'hover:bg-red-700');
                toggleCameraBtn.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                scanSuccess.classList.add('hidden');
                scanStatus.textContent = 'Arahkan kamera ke QR code tiket';
                scanStatus.classList.remove('text-green-600', 'font-bold');
                scanStatus.classList.add('text-indigo-600');
            }

            // Cleanup on page unload
            window.addEventListener('beforeunload', function() {
                stopCamera();
            });

            // Handle page visibility change (when user switches tabs)
            document.addEventListener('visibilitychange', function() {
                if (document.hidden && isCameraActive) {
                    // Optionally stop camera when page is hidden
                    // stopCamera();
                }
            });

            // ========== PDF Upload and QR Code Scanning ==========
            const pdfFileInput = document.getElementById('pdfFileInput');
            const pdfUploadArea = document.getElementById('pdfUploadArea');
            const pdfUploadContent = document.getElementById('pdfUploadContent');
            const pdfProcessing = document.getElementById('pdfProcessing');
            const pdfPreview = document.getElementById('pdfPreview');
            const pdfFileName = document.getElementById('pdfFileName');
            const clearPdfBtn = document.getElementById('clearPdfBtn');
            const pdfStatus = document.getElementById('pdfStatus');

            // Configure PDF.js worker
            if (typeof pdfjsLib !== 'undefined') {
                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
            }

            // Click to upload
            pdfUploadArea.addEventListener('click', (e) => {
                e.stopPropagation(); // Prevent event bubbling
                if (!pdfProcessing.classList.contains('hidden')) return;
                
                // Cancel auto-start camera if user clicks PDF upload
                if (cameraAutoStartTimeout) {
                    clearTimeout(cameraAutoStartTimeout);
                    cameraAutoStartTimeout = null;
                }
                
                // Stop camera if active
                if (isCameraActive) {
                    stopCamera();
                }
                
                pdfFileInput.click();
            });

            // Drag and drop
            pdfUploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                e.stopPropagation(); // Prevent event bubbling
                pdfUploadArea.classList.add('border-amber-600', 'bg-amber-100');
                
                // Cancel auto-start camera if user drags file
                if (cameraAutoStartTimeout) {
                    clearTimeout(cameraAutoStartTimeout);
                    cameraAutoStartTimeout = null;
                }
                
                // Stop camera if active
                if (isCameraActive) {
                    stopCamera();
                }
            });

            pdfUploadArea.addEventListener('dragleave', (e) => {
                e.stopPropagation(); // Prevent event bubbling
                pdfUploadArea.classList.remove('border-amber-600', 'bg-amber-100');
            });

            pdfUploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                e.stopPropagation(); // Prevent event bubbling
                pdfUploadArea.classList.remove('border-amber-600', 'bg-amber-100');
                
                // Cancel auto-start camera
                if (cameraAutoStartTimeout) {
                    clearTimeout(cameraAutoStartTimeout);
                    cameraAutoStartTimeout = null;
                }
                
                // Stop camera if active
                if (isCameraActive) {
                    stopCamera();
                }
                
                const files = e.dataTransfer.files;
                if (files.length > 0 && files[0].type === 'application/pdf') {
                    handlePdfFile(files[0]);
                } else {
                    alert('Silakan upload file PDF yang valid.');
                }
            });

            // File input change
            pdfFileInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    // Cancel auto-start camera
                    if (cameraAutoStartTimeout) {
                        clearTimeout(cameraAutoStartTimeout);
                        cameraAutoStartTimeout = null;
                    }
                    
                    // Stop camera if active
                    if (isCameraActive) {
                        stopCamera();
                    }
                    
                    handlePdfFile(e.target.files[0]);
                }
            });

            // Clear PDF button
            clearPdfBtn.addEventListener('click', () => {
                pdfFileInput.value = '';
                pdfUploadContent.classList.remove('hidden');
                pdfProcessing.classList.add('hidden');
                pdfPreview.classList.add('hidden');
                isPdfProcessing = false;
                
                // Optionally restart camera after clearing PDF
                if (QrScanner.hasCamera() && !isCameraActive) {
                    setTimeout(() => {
                        if (!isPdfProcessing) {
                            startCamera();
                        }
                    }, 300);
                }
            });

            async function handlePdfFile(file) {
                // Stop camera if active
                if (isCameraActive) {
                    stopCamera();
                }
                
                // Set PDF processing flag
                isPdfProcessing = true;

                // Validate file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 5MB.');
                    isPdfProcessing = false;
                    return;
                }

                // Show processing state
                pdfUploadContent.classList.add('hidden');
                pdfProcessing.classList.remove('hidden');
                pdfPreview.classList.remove('hidden');
                pdfFileName.textContent = file.name;
                pdfStatus.textContent = 'Membaca file PDF...';

                try {
                    // Read file as array buffer
                    const arrayBuffer = await file.arrayBuffer();
                    pdfStatus.textContent = 'Memproses PDF...';

                    // Load PDF
                    const loadingTask = pdfjsLib.getDocument({ data: arrayBuffer });
                    const pdf = await loadingTask.promise;
                    const numPages = pdf.numPages;

                    pdfStatus.textContent = `Memindai ${numPages} halaman untuk QR code...`;

                    // Process each page
                    for (let pageNum = 1; pageNum <= numPages; pageNum++) {
                        pdfStatus.textContent = `Memindai halaman ${pageNum} dari ${numPages}...`;
                        
                        const page = await pdf.getPage(pageNum);
                        const viewport = page.getViewport({ scale: 2.0 }); // Higher scale for better quality

                        // Create canvas to render PDF page
                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        // Render PDF page to canvas
                        await page.render({
                            canvasContext: context,
                            viewport: viewport
                        }).promise;

                        // Try to scan QR code from this page
                        const qrCode = await scanQrCodeFromCanvas(canvas);
                        
                        if (qrCode) {
                            pdfStatus.textContent = 'QR Code ditemukan! Memvalidasi...';
                            
                        // Found QR code, submit form
                        qrCodeInput.value = qrCode;
                        isPdfProcessing = false; // Reset flag before submit
                        
                        // Small delay to show success message
                        setTimeout(() => {
                            qrCodeForm.submit();
                        }, 500);
                        
                        return; // Exit function
                        }
                    }

                    // No QR code found
                    pdfStatus.textContent = 'QR code tidak ditemukan dalam PDF.';
                    pdfStatus.classList.add('text-red-600');
                    pdfStatus.classList.remove('text-gray-500');
                    
                    // Reset after 3 seconds
                    setTimeout(() => {
                        pdfUploadContent.classList.remove('hidden');
                        pdfProcessing.classList.add('hidden');
                        pdfStatus.textContent = 'Membaca file PDF';
                        pdfStatus.classList.remove('text-red-600');
                        pdfStatus.classList.add('text-gray-500');
                        isPdfProcessing = false;
                    }, 3000);

                } catch (error) {
                    console.error('Error processing PDF:', error);
                    alert('Gagal memproses PDF. Pastikan file PDF valid dan tidak rusak.');
                    pdfUploadContent.classList.remove('hidden');
                    pdfProcessing.classList.add('hidden');
                    pdfPreview.classList.add('hidden');
                    isPdfProcessing = false;
                }
            }

            async function scanQrCodeFromCanvas(canvas) {
                return new Promise((resolve) => {
                    try {
                        // Get image data from canvas
                        const ctx = canvas.getContext('2d');
                        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                        
                        // Use jsQR to scan QR code from image data
                        if (typeof jsQR !== 'undefined') {
                            const code = jsQR(imageData.data, imageData.width, imageData.height, {
                                inversionAttempts: 'dontInvert',
                            });
                            
                            if (code && code.data) {
                                resolve(code.data);
                            } else {
                                resolve(null);
                            }
                        } else {
                            // Fallback: try using QrScanner with image element
                            const img = new Image();
                            img.onload = async () => {
                                try {
                                    const result = await QrScanner.scanImage(img, {
                                        returnDetailedScanResult: true
                                    });
                                    resolve(result ? result.data : null);
                                } catch (error) {
                                    resolve(null);
                                }
                            };
                            img.onerror = () => resolve(null);
                            img.src = canvas.toDataURL('image/png');
                        }
                    } catch (error) {
                        console.error('Error scanning QR code:', error);
                        resolve(null);
                    }
                });
            }
        });
    </script>
</x-app-layout>

