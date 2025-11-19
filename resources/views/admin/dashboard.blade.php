@extends('admin.layouts.app')

@section('title', 'Dashboard Admin P3M')

@section('content')

    <!-- Greeting Section -->
    <div class="bg-white overflow-hidden shadow-xl rounded-xl p-8 mb-8 border border-gray-100">
        <div class="text-2xl font-semibold text-gray-900 flex items-center">
            👋 Selamat datang, <span class="text-blue-600 ml-2">{{ Auth::user()->name }}</span>!
        </div>
        <p class="mt-2 text-gray-600">Ini adalah halaman utama dashboard admin untuk memantau aktivitas P3M.</p>
    </div>

    <h2 class="text-3xl font-extrabold text-gray-900 mb-6">Ringkasan Statistik</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Total Reviewer -->
        <div class="bg-white shadow-lg rounded-xl p-6 border-t-4 border-blue-500 transition duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Reviewer</h3>
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-blue-600">
                {{ $totalReviewer ?? 0 }}
            </p>
            <a href="{{ route('admin.users.index', ['role' => 'reviewer']) }}" class="text-xs text-blue-500 hover:text-blue-700 font-medium mt-2 block">Lihat Detail &rarr;</a>
        </div>

        <!-- Total Dosen -->
        <div class="bg-white shadow-lg rounded-xl p-6 border-t-4 border-green-500 transition duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Dosen</h3>
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-2.414-2.414A1 1 0 0015.586 6H7a2 2 0 00-2 2v11a2 2 0 002 2z"></path></svg>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-green-600">
                {{ $totalDosen ?? 0 }}
            </p>
            <a href="{{ route('admin.users.index', ['role' => 'dosen']) }}" class="text-xs text-green-500 hover:text-green-700 font-medium mt-2 block">Lihat Detail &rarr;</a>
        </div>

        <!-- Pengumuman Aktif -->
        <div class="bg-white shadow-lg rounded-xl p-6 border-t-4 border-yellow-500 transition duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pengumuman Aktif</h3>
                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-yellow-600">
                {{ $pengumuman ?? 0 }}
            </p>
            <a href="{{ route('admin.pengumuman.index') }}" class="text-xs text-yellow-500 hover:text-yellow-700 font-medium mt-2 block">Lihat Detail &rarr;</a>
        </div>

        <!-- Total Usulan (Placeholder) -->
        <div class="bg-white shadow-lg rounded-xl p-6 border-t-4 border-purple-500 transition duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Usulan Masuk</h3>
                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-purple-600">
                {{ $totalUsulan ?? 'N/A' }} 
            </p>
             <span class="text-xs text-gray-500 mt-2 block">Data Usulan</span>
        </div>
    </div>
@endsection