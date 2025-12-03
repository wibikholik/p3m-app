@extends('layouts.app')

@section('content')
    <div class="container py-6">
        <a href="{{ route('home') }}" class="text-sm text-blue-600">&larr; Kembali ke Beranda</a>
        <h1 class="mt-4 mb-2">{{ $pengumuman->judul }}</h1>
        <p class="text-sm text-muted">Kategori: {{ $pengumuman->kategori->nama_kategori ?? 'Umum' }} | Status: {{ $pengumuman->status }}</p>

        @if ($pengumuman->gambar)
            <div class="my-4">
                <img src="{{ asset('storage/' . $pengumuman->gambar) }}" alt="Gambar" class="img-fluid">
            </div>
        @endif

        <div class="mt-4">
            {!! $pengumuman->isi !!}
        </div>
    </div>
@endsection
