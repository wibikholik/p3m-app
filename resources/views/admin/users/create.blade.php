@extends('admin.layouts.app')

@section('title', 'Tambah Pengguna - Admin P3M')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h2 class="text-3xl font-extrabold text-gray-900">Tambah Pengguna Baru</h2>
    <a href="{{ route('admin.users.index') }}" 
       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 transition duration-300">
        &larr; Kembali
    </a>
</div>

<div class="bg-white shadow-xl rounded-xl p-8 border border-gray-200">

    {{-- Notifikasi Error Validasi --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg shadow-sm" role="alert">
            <p class="font-bold mb-1">Terjadi Kesalahan:</p>
            <ul class="list-disc pl-5 mt-1 text-sm">
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
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                <input type="text" name="name" id="name"
                    value="{{ old('name') }}" required
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-3 text-gray-800 transition duration-150"
                    placeholder="Masukkan Nama Lengkap">
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" id="email"
                    value="{{ old('email') }}" required
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-3 text-gray-800 transition duration-150"
                    placeholder="Masukkan Email Aktif">
            </div>

            {{-- Role --}}
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select name="role" id="role"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-3 text-gray-800 transition duration-150" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" @selected(old('role') == 'admin')>Admin</option>
                    <option value="dosen" @selected(old('role') == 'dosen')>Dosen</option>
                    <option value="reviewer" @selected(old('role') == 'reviewer')>Reviewer</option>
                </select>
            </div>
            {{-- Spacer --}}
            <div></div> 
        </div>

        <hr class="my-8 border-gray-200">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" name="password" id="password" required autocomplete="new-password"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-3 text-gray-800 transition duration-150"
                    placeholder="Masukkan Password">
            </div>

            {{-- Konfirmasi Password --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-3 text-gray-800 transition duration-150"
                    placeholder="Ulangi Password">
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-end space-x-3 mt-8 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.users.index') }}" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 transition duration-300">
                Batal
            </a>
            <button type="submit" 
                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan Pengguna
            </button>
        </div>
    </form>
</div>
@endsection