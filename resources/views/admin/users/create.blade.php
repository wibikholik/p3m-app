@extends('admin.layouts.app')

@section('title', 'Tambah Pengguna - Admin P3M')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Tambah Pengguna Baru</h2>
</div>

<div class="bg-white shadow rounded-lg p-6">

    {{-- Tampilkan Error Validasi --}}
    @if ($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Oops!</strong>
            <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Nama --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="name" id="name"
                    value="{{ old('name') }}" required
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm 
                           placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email"
                    value="{{ old('email') }}" required
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm 
                           placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            {{-- Role --}}
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role" id="role"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 
                           focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" @selected(old('role') == 'admin')>Admin</option>
                    <option value="dosen" @selected(old('role') == 'dosen')>Dosen</option>
                    <option value="reviewer" @selected(old('role') == 'reviewer')>Reviewer</option>
                </select>
            </div>
        </div>

        <hr class="my-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required autocomplete="new-password"
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm 
                           placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            {{-- Konfirmasi Password --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm 
                           placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-end space-x-4 mt-8">
            <a href="{{ route('admin.users.index') }}" 
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded transition duration-300">
                Batal
            </a>
            <button type="submit" 
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
