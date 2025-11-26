@extends('reviewer.layouts.app')

@section('title', 'Daftar Tugas Review')
@section('content')
<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">

    <h1 class="text-2xl font-bold mb-4">Daftar Tugas Review</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($usulans->count())
        <table class="min-w-full border border-gray-200 rounded overflow-hidden">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 border">No</th>
                    <th class="px-4 py-2 border">Judul Usulan</th>
                    <th class="px-4 py-2 border">Deadline</th>
                    <th class="px-4 py-2 border">Status Tugas</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usulans as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border text-center">{{ $index + 1 }}</td>
                        <td class="px-4 py-2 border">
                            <a href="{{ route('reviewer.usulan.show', $item->id) }}" class="text-blue-600 hover:underline">
                                {{ Str::limit($item->judul, 60) }}
                            </a>
                        </td>
                        <td class="px-4 py-2 border text-center">{{ $item->deadline ? date('d M Y', strtotime($item->deadline)) : '-' }}</td>
                        <td class="px-4 py-2 border text-center">{{ ucfirst($item->reviewer_status) }}</td>
                        <td class="px-4 py-2 border text-center">
                            @if($item->reviewer_status == 'assigned')
                                <form action="{{ route('reviewer.usulan.accept', $item->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Terima</button>
                                </form>
                                <form action="{{ route('reviewer.usulan.decline', $item->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Tolak</button>
                                </form>
                            @else
                                <span class="text-gray-600">{{ ucfirst($item->reviewer_status) }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-gray-500">Belum ada tugas review yang ditugaskan.</p>
    @endif
</div>
@endsection
