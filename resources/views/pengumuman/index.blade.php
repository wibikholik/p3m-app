@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mt-6 mb-4">Daftar Pengumuman</h1>

        @forelse($pengumuman as $item)
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="card-title">{{ $item->judul }}</h3>
                    <p class="text-muted">Kategori: {{ $item->kategori->nama_kategori ?? 'Umum' }}</p>
                    <p>{{ \\Illuminate\\Support\\Str::limit(strip_tags($item->isi), 200) }}</p>
                    <a href="{{ route('pengumuman.show', $item->id) }}" class="btn btn-primary">Baca selengkapnya</a>
                </div>
            </div>
        @empty
            <p>Tidak ada pengumuman.</p>
        @endforelse

        <div class="mt-4">
            {{ $pengumuman->links() }}
        </div>
    </div>
@endsection
