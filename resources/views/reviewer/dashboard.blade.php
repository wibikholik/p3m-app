@extends('reviewer.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="py-10">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                Selamat datang, {{ Auth::user()->name }}! 👋<br>
                Ini adalah halaman dashboard khusus Reviewer.
            </div>
        </div>
    </div>
</div>

<h2 class="text-2xl font-bold text-gray-800 mb-6 mt-8">Dashboard</h2>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    {{-- Contoh Kartu Statistik --}}
    <div class="bg-blue-500 text-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold">Proposal Saya</h3>
        <p class="text-2xl mt-2">5</p>
    </div>

    <div class="bg-green-500 text-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold">Penelitian Aktif</h3>
        <p class="text-2xl mt-2">2</p>
    </div>
</div>
@endsection
