@extends('admin.layouts.app')

@section('title', 'Dashboard Admin P3M')

@section('content')
<div class="py-10">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                Selamat datang, {{ Auth::user()->name }}! 👋<br>
                Ini adalah halaman dashboard khusus admin.
            </div>
        </div>
    </div>

    <h2 class="text-2xl font-bold text-gray-800 mb-6 mt-8">Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Reviewer -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700">Total Reviewer</h3>
            <p class="mt-2 text-2xl font-bold text-blue-600">
                {{ $totalReviewer ?? 0 }}
            </p>
        </div>

        <!-- Total Dosen -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700">Total Dosen</h3>
            <p class="mt-2 text-2xl font-bold text-green-600">
                {{ $totalDosen ?? 0 }}
            </p>
        </div>

        <!-- Pengumuman Aktif -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700">Pengumuman Aktif</h3>
            <p class="mt-2 text-2xl font-bold text-yellow-600">
                {{ $pengumuman ?? 0 }}
            </p>
        </div>

        <!-- Total Usulan (opsional) -->
        {{-- 
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700">Total Usulan</h3>
            <p class="mt-2 text-2xl font-bold text-purple-600">
                {{ $totalUsulan ?? 0 }}
            </p>
        </div>
        --}}
    </div>
</div>
@endsection
