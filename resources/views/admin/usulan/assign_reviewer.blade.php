@extends('admin.layouts.app')

@section('title', 'Assign Reviewer')
@section('page-title', 'Assign Reviewer')

@section('content')
<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Assign Reviewer untuk Usulan: {{ $usulan->judul ?? $usulan->id }}</h1>
        <a href="{{ route('admin.usulan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded-lg shadow-md transition">Kembali ke Daftar Usulan</a>
    </div>

    {{-- Daftar reviewer yang sudah ditugaskan --}}
    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200 mb-6">
        <h3 class="px-6 py-3 font-semibold text-gray-700">Reviewer Saat Ini</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Reviewer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan Assign</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($usulan->reviewers as $rev)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $rev->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $rev->pivot->status }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $rev->pivot->deadline ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $rev->pivot->catatan_assign ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-4 text-sm text-gray-500 text-center">Belum ada reviewer ditugaskan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Form assign reviewer baru --}}
    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Assign Reviewer Baru</h3>
        <form action="{{ route('admin.usulan.assignReviewer', $usulan->id) }}" method="POST">
            @csrf
            <div id="reviewer-wrapper">
                <div class="reviewer-item mb-4">
                    <div class="grid grid-cols-12 gap-4 items-end">
                        <div class="col-span-5">
                            <label class="block text-sm font-medium text-gray-700">Pilih Reviewer</label>
                            <select name="reviewers[0][id]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
                                @foreach($reviewers as $rev)
                                    <option value="{{ $rev->id }}">{{ $rev->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-3">
                            <label class="block text-sm font-medium text-gray-700">Deadline</label>
                            <input type="date" name="reviewers[0][deadline]" class="block w-full rounded-md border-gray-300 shadow-sm sm:text-sm p-2">
                        </div>
                        <div class="col-span-4">
                            <label class="block text-sm font-medium text-gray-700">Catatan (opsional)</label>
                            <input type="text" name="reviewers[0][catatan_assign]" class="block w-full rounded-md border-gray-300 shadow-sm sm:text-sm p-2">
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded-lg shadow-sm mt-2 mb-4" id="addReviewerBtn">Tambah Reviewer</button>
            <br>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-md transition">Assign</button>
        </form>
    </div>
</div>

{{-- JS untuk tambah row reviewer dinamis --}}
<script>
let reviewerIndex = 1;
document.getElementById('addReviewerBtn').addEventListener('click', function(){
    const wrapper = document.getElementById('reviewer-wrapper');
    const newItem = document.createElement('div');
    newItem.classList.add('reviewer-item','mb-4');
    newItem.innerHTML = `
        <div class="grid grid-cols-12 gap-4 items-end">
            <div class="col-span-5">
                <select name="reviewers[${reviewerIndex}][id]" class="block w-full rounded-md border-gray-300 shadow-sm sm:text-sm p-2">
                    @foreach($reviewers as $rev)
                        <option value="{{ $rev->id }}">{{ $rev->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-3">
                <input type="date" name="reviewers[${reviewerIndex}][deadline]" class="block w-full rounded-md border-gray-300 shadow-sm sm:text-sm p-2">
            </div>
            <div class="col-span-4">
                <input type="text" name="reviewers[${reviewerIndex}][catatan_assign]" class="block w-full rounded-md border-gray-300 shadow-sm sm:text-sm p-2">
            </div>
        </div>
    `;
    wrapper.appendChild(newItem);
    reviewerIndex++;
});
</script>
@endsection
