<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Masukkan email Anda. Kami akan mengirimkan kode OTP untuk reset password.
    </div>

    <form method="POST" action="{{ route('password.otp.send') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                Kirim OTP
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>




