<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Foto Event</h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">{{ $event->event_name }}</h3>
            <p class="text-sm text-gray-500">{{ $event->event_location }} • {{ $event->start_date->format('d M Y') }}</p>
          </div>

          <form method="POST" action="{{ route('admin.events.update-photos', $event) }}" class="space-y-6" enctype="multipart/form-data">
            @csrf

            <!-- Foto yang Sudah Ada -->
            @if($event->photos && $event->photos->count() > 0)
            <div>
              <h4 class="text-md font-semibold mb-4 text-gray-700">Foto yang Sudah Ada</h4>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($event->photos as $photo)
                <div class="relative group">
                  <div class="relative bg-gray-200 rounded-lg overflow-hidden aspect-video">
                    <img src="{{ asset('storage/' . $photo->photo_path) }}"
                      alt="Event photo"
                      class="w-full h-full object-cover" />
                    <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-30 transition-opacity"></div>
                  </div>
                  <label class="absolute top-1 right-1 bg-red-600 text-white p-1.5 rounded opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
                    title="Hapus foto">
                    <input type="checkbox" name="delete_photos[]" value="{{ $photo->id }}" class="hidden" />
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </label>
                  <p class="text-xs text-gray-500 mt-1 text-center">Klik X untuk hapus</p>
                </div>
                @endforeach
              </div>
            </div>
            @endif

            <!-- Upload Foto Baru -->
            <div class="border-t border-gray-200 pt-6">
              <h4 class="text-md font-semibold mb-4 text-gray-700">Tambah Foto Baru</h4>

              <div class="mb-3 p-4 bg-white rounded-lg border-2 border-dashed border-amber-300 hover:border-amber-400 transition-colors cursor-pointer" id="photoDropZone">
                <input type="file" name="photos[]" id="photoInput" multiple accept="image/*" class="hidden" />
                <div class="text-center py-6">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-amber-500 mb-2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4" />
                  </svg>
                  <p class="text-sm font-medium text-gray-700">Klik atau seret foto ke sini</p>
                  <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG (Rasio 16:9, max 5MB)</p>
                </div>
              </div>

              <div id="photoPreviewContainer" class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4"></div>

              @error('photos')
              <div class="text-red-600 text-sm mt-2 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ $message }}
              </div>
              @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between border-t border-gray-200 pt-6">
              <a href="{{ route('admin.events.show', $event) }}"
                class="px-4 py-2 border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">
                Batal
              </a>
              <button type="submit"
                class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                Simpan Perubahan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    const photoDropZone = document.getElementById('photoDropZone');
    const photoInput = document.getElementById('photoInput');
    const photoPreviewContainer = document.getElementById('photoPreviewContainer');
    let selectedFiles = [];

    photoDropZone.addEventListener('click', () => photoInput.click());

    photoDropZone.addEventListener('dragover', (e) => {
      e.preventDefault();
      photoDropZone.classList.add('border-amber-500', 'bg-amber-100');
    });

    photoDropZone.addEventListener('dragleave', () => {
      photoDropZone.classList.remove('border-amber-500', 'bg-amber-100');
    });

    photoDropZone.addEventListener('drop', (e) => {
      e.preventDefault();
      photoDropZone.classList.remove('border-amber-500', 'bg-amber-100');
      handleFiles(e.dataTransfer.files);
    });

    photoInput.addEventListener('change', (e) => {
      handleFiles(e.target.files);
    });

    function handleFiles(files) {
      const newFiles = Array.from(files).filter(file => {
        if (!file.type.startsWith('image/')) {
          alert('Hanya file gambar yang diterima!');
          return false;
        }
        if (file.size > 5 * 1024 * 1024) {
          alert(`File ${file.name} terlalu besar (max 5MB)`);
          return false;
        }
        return true;
      });

      // Append ke selectedFiles, bukan replace
      selectedFiles = selectedFiles.concat(newFiles);
      updatePhotoPreview();
    }

    function updatePhotoPreview() {
      photoPreviewContainer.innerHTML = '';
      selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = (e) => {
          const div = document.createElement('div');
          div.className = 'relative group';
          div.innerHTML = `
                        <div class="relative bg-gray-200 rounded-lg overflow-hidden aspect-video">
                            <img src="${e.target.result}" class="w-full h-full object-cover" />
                            <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-30 transition-opacity"></div>
                        </div>
                        <button type="button" class="absolute top-1 right-1 bg-red-600 text-white p-1 rounded opacity-0 group-hover:opacity-100 transition-opacity" data-index="${index}" title="Hapus foto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    `;
          photoPreviewContainer.appendChild(div);

          const removeBtn = div.querySelector('button');
          removeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            selectedFiles.splice(index, 1);
            updatePhotoPreview();
          });
        };
        reader.readAsDataURL(file);
      });
    }

    // Before form submit, sync files
    document.querySelector('form').addEventListener('submit', function() {
      const dt = new DataTransfer();
      selectedFiles.forEach(file => {
        dt.items.add(file);
      });
      photoInput.files = dt.files;
    });
  </script>
</x-app-layout>