<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Masukkan password baru untuk {{ $email }}.
    </div>

    <form method="POST" action="{{ route('password.otp.reset') }}">
        @csrf

        <input type="hidden" name="email" value="{{ $email }}" />

        <div>
            <x-input-label for="password" :value="__('Password Baru')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                Ubah Password
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>




