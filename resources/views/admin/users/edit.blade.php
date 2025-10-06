<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit User</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-lg font-medium mb-4">Form</h2>

                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Username</label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            @error('username')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Password Baru</label>
                                <input type="password" name="password" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" placeholder="Kosongkan jika tidak diubah">
                                @error('password')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                <option value="creator" @selected(old('role', $user->role)==='creator')>Creator</option>
                                <option value="attendee" @selected(old('role', $user->role)==='attendee')>Attendee</option>
                            </select>
                            @error('role')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-md border">Batal</a>
                            <button class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


