<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Masukkan kode OTP yang kami kirimkan ke email {{ $email ?? '' }}.
    </div>

    <form method="POST" action="{{ route('password.otp.verify') }}">
        @csrf

        <input type="hidden" name="email" value="{{ $email }}" />

        <div>
            <x-input-label for="code" :value="__('Kode OTP')" />
            <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" required autofocus />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                Verifikasi
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>




