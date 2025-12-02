@extends('reviewer.layouts.app')

@section('title', 'Daftar Tugas Review')
@section('content')
<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">

    <h1 class="text-3xl font-bold mb-6 text-gray-800">Daftar Tugas Review</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow rounded-lg overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 uppercase tracking-wider">Judul Usulan</th>
                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-700 uppercase tracking-wider">Deadline</th>
                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-700 uppercase tracking-wider">Status Tugas</th>
                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($usulans as $index => $item)
                    @php
                        $deadlinePassed = $item->deadline && \Carbon\Carbon::parse($item->deadline)->isPast();
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-center text-gray-700">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-blue-600 hover:underline">
                            <a href="{{ route('reviewer.usulan.show', $item->id) }}">
                                {{ Str::limit($item->judul, 60) }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-700">
                            {{ $item->deadline ? \Carbon\Carbon::parse($item->deadline)->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-white font-semibold
                                @if($item->reviewer_status === 'assigned') bg-blue-500
                                @elseif($item->reviewer_status === 'accepted') bg-green-500
                                @elseif($item->reviewer_status === 'declined') bg-red-500
                                @elseif($item->reviewer_status === 'lolos') bg-indigo-500
                                @else bg-gray-400
                                @endif
                            ">
                                {{ ucfirst($item->reviewer_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center space-x-2">
                            @if($item->reviewer_status === 'assigned')
                                @if($deadlinePassed)
                                    <span class="px-3 py-1 bg-red-500 text-white rounded-lg text-sm">Deadline Terlewati</span>
                                @else
                                    <form action="{{ route('reviewer.usulan.accept', $item->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">Terima</button>
                                    </form>
                                    <form action="{{ route('reviewer.usulan.decline', $item->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">Tolak</button>
                                    </form>
                                @endif
                            @elseif($item->reviewer_status === 'accepted')
                                <a href="{{ route('reviewer.usulan.review', $item->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">Review</a>
                            @else
                                <span class="text-gray-600">{{ ucfirst($item->reviewer_status) }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-gray-500 py-6">Belum ada tugas review yang ditugaskan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
