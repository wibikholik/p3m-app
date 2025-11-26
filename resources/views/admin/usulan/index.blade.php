@extends('admin.layouts.app')

@section('title', 'Daftar Usulan')
@section('page-title', 'Daftar Usulan')

@section('content')
<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">

    {{-- Header Section --}}
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Usulan</h1>
            <p class="mt-2 text-sm text-gray-700">Kelola seluruh usulan dari dosen ke P3M.</p>
        </div>
        
        {{-- Filter Status (Penting untuk Seleksi Admin) --}}
        <div class="mt-4 sm:mt-0">
            <form id="filterForm" action="{{ route('admin.usulan.index') }}" method="GET">
                <select name="status" onchange="document.getElementById('filterForm').submit()"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                    <option value="">Semua Status</option>
                    <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan (Perlu Verifikasi)</option>
                    <option value="lolos_administrasi" {{ request('status') == 'lolos_administrasi' ? 'selected' : '' }}>Lolos Administrasi</option>
                    <option value="sedang_di_review" {{ request('status') == 'sedang_di_review' ? 'selected' : '' }}>Dalam Review</option>
                    <option value="didanai" {{ request('status') == 'didanai' ? 'selected' : '' }}>Didanai/Kontrak</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    {{-- Tambahkan status lain sesuai kebutuhan --}}
                </select>
            </form>
        </div>

    </div>

    {{-- Table Section --}}
    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
        @if(isset($usulan) && $usulan->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Usulan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori Program</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skema</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengusul</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($usulan as $index => $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">

                                {{-- No --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    {{ $index + 1 }}
                                </td>

                                {{-- Judul Usulan --}}
                                <td class="px-6 py-4 max-w-xs">
                                    <div class="text-sm font-semibold text-gray-900 hover:text-blue-600 transition-colors">
                                        <a href="{{ route('admin.usulan.show', $item->id) }}">
                                            {{ Str::limit($item->judul, 60) }}
                                        </a>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Tahun: {{ $item->created_at->year }}
                                    </div>
                                </td>

                                {{-- Program  --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-800 font-medium">
                                        {{ $item->pengumuman->judul ?? 'Program Dihapus' }}
                                    </div>
                                </td>
                                {{-- Kategori program --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-800 font-medium">
                                        {{ $item->pengumuman->kategori->nama_kategori ?? 'N/A' }}
                                    </div>
                                </td>
                                {{-- skema --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-800 font-medium">
                                        {{ $item->skema }}
                                    </div>
                                </td>
                                
                                {{-- Pengusul --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $item->pengusul->name ?? 'Tidak ditemukan' }}
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $status = strtolower($item->status);
                                        $statusClasses = [
                                            'diajukan' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'lolos_administrasi' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'diterima' => 'bg-green-100 text-green-800 border-green-200',
                                            'ditolak'  => 'bg-red-100 text-red-800 border-red-200',
                                            'revisi'   => 'bg-orange-100 text-orange-800 border-orange-200',
                                            'sedang_di_review' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            'didanai' => 'bg-green-500 text-white border-green-700',
                                        ];
                                        $class = $statusClasses[$status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $class }}">
                                        {{ str_replace('_', ' ', ucfirst($item->status)) }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end items-center space-x-2">
                                        
                                        {{-- Tombol Detail (Selalu Ada) --}}
                                        <a href="{{ route('admin.usulan.show', $item->id) }}"
                                            class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded-lg hover:bg-blue-100 transition shadow-sm">
                                            Detail
                                        </a>

                                        {{-- Tombol Proses Saat Ini (Dinamis) --}}
                                        @php
                                            $currentStatus = strtolower($item->status);
                                            $buttonText = 'Lihat Data';
                                            $buttonClass = 'bg-gray-500 hover:bg-gray-600';
                                            $targetRoute = route('admin.usulan.show', $item->id);

                                            switch ($currentStatus) {
                                                case 'diajukan':
                                                    $buttonText = 'Verifikasi Admin';
                                                    $buttonClass = 'bg-green-600 hover:bg-green-700';
                                                    break;
                                                case 'lolos_administrasi':
                                                    $buttonText = 'Tugaskan Reviewer';
                                                    $buttonClass = 'bg-indigo-600 hover:bg-indigo-700';
                                                    $targetRoute = route('admin.usulan.assignReviewer.page', $item->id);
                                                    // TODO: Route ini akan diarahkan ke halaman penugasan reviewer
                                                    break;
                                                case 'sedang_di_review':
                                                    $buttonText = 'Monitoring Review';
                                                    $buttonClass = 'bg-purple-600 hover:bg-purple-700';
                                                    break;
                                                case 'revisi':
                                                    $buttonText = 'Verifikasi Revisi';
                                                    $buttonClass = 'bg-orange-600 hover:bg-orange-700';
                                                    break;
                                                case 'diterima':
                                                    $buttonText = 'Proses Kontrak';
                                                    $buttonClass = 'bg-teal-600 hover:bg-teal-700';
                                                    break;
                                                case 'ditolak':
                                                    $buttonText = 'Lihat Catatan';
                                                    $buttonClass = 'bg-gray-700 hover:bg-gray-800';
                                                    break;
                                            }
                                        @endphp
                                        
                                        <a href="{{ $targetRoute }}"
                                            class="text-white {{ $buttonClass }} px-3 py-1 rounded-lg shadow-md transition">
                                            {{ $buttonText }}
                                        </a>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            {{-- Tambahkan jika Anda menggunakan metode paginate() di controller --}}
            {{-- <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                {{ $usulan->links() }}
            </div> --}}

        @else

            {{-- Empty State --}}
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada usulan</h3>
                <p class="mt-1 text-sm text-gray-500">Tidak ada data yang sesuai dengan filter saat ini.</p>
            </div>

        @endif
    </div>
</div>
@endsection