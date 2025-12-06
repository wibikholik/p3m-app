@extends('admin.layouts.app')

@section('title', 'Rekap Penilaian Usulan')
@section('page-title', 'Rekap Penilaian Usulan')

@section('content')
<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-extrabold mb-6 text-gray-900">{{ $usulan->judul }}</h1>
    <p class="text-sm text-gray-500 mb-6">Status Usulan: <span class="font-semibold text-indigo-600">{{ ucfirst($usulan->status) }}</span></p>

    {{-- PENILAIAN AWAL --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-xl font-semibold mb-4 text-gray-800">Penilaian Awal Reviewer</h3>
        <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase">Reviewer</th>
                    <th class="px-4 py-3 border text-center text-xs font-medium text-gray-500 uppercase">Total Nilai</th>
                    <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase">Rincian & Catatan</th>
                    <th class="px-4 py-3 border text-center text-xs font-medium text-gray-500 uppercase">Status Review</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($totalPerReviewer as $rev)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 border">{{ $rev['reviewer']->name ?? 'Reviewer Tidak Ditemukan' }}</td>
                    <td class="px-4 py-3 border text-center font-bold @if($rev['nilai']>=80) text-green-600 @else text-yellow-600 @endif">{{ number_format($rev['nilai'],2) }}</td>
                    <td class="px-4 py-3 border text-sm text-gray-700">
                        <ul class="list-disc pl-5">
                            @foreach($rev['detail'] as $d)
                                <li>
                                    <strong>{{ $d->komponen?->nama ?? 'Komponen Tidak Ditemukan' }}:</strong> 
                                    {{ $d->nilai }} (<em>{{ $d->catatan ?? 'Tidak Ada Catatan' }}</em>)
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="px-4 py-3 border text-center">
                        @if(isset($rev['reviewer']->pivot) && $rev['reviewer']->pivot->sudah_direview)
                            <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">Sudah Direview</span>
                        @else
                            <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800">Belum Direview</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- PENILAIAN REVISI --}}
    <div class="bg-yellow-50 shadow rounded-lg p-6 mb-6">
        <h3 class="text-xl font-semibold mb-4 text-yellow-800">Penilaian Revisi Reviewer</h3>
        <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
            <thead class="bg-yellow-100">
                <tr>
                    <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase">Reviewer</th>
                    <th class="px-4 py-3 border text-center text-xs font-medium text-gray-500 uppercase">Total Nilai Revisi</th>
                    <th class="px-4 py-3 border text-left text-xs font-medium text-gray-500 uppercase">Rincian & Catatan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($totalRevisiPerReviewer as $rev)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 border">{{ $rev['reviewer']->name ?? 'Reviewer Tidak Ditemukan' }}</td>
                    <td class="px-4 py-3 border text-center font-bold @if($rev['nilai']>=80) text-green-600 @else text-yellow-600 @endif">{{ number_format($rev['nilai'],2) }}</td>
                    <td class="px-4 py-3 border text-sm text-gray-700">
                        <ul class="list-disc pl-5">
                            @foreach($rev['detail'] as $d)
                                <li>
                                    <strong>{{ $d->kriteria?->nama ?? 'Kriteria Tidak Ditemukan' }}:</strong> 
                                    {{ $d->nilai }} (<em>{{ $d->catatan ?? 'Tidak Ada Catatan' }}</em>)
                                </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- FINALISASI --}}
    <div class="mt-6 pt-4 border-t border-gray-200">
        <h4 class="font-bold text-lg mb-3">Tindakan Keputusan Final</h4>
        @if(!$allReviewed)
            <p class="text-red-500 text-sm font-medium">Belum semua reviewer menyelesaikan penilaian. Tindakan finalisasi dinonaktifkan.</p>
        @else
            <div class="space-x-3 flex items-center">
                <form action="{{ route('admin.usulan.finalize', $usulan->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="diterima">
                    <button type="submit" class="px-6 py-3 rounded-lg bg-green-600 text-white font-bold hover:bg-green-700">Tetapkan DITERIMA</button>
                </form>

                <form action="{{ route('admin.usulan.finalize', $usulan->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="ditolak">
                    <button type="submit" class="px-6 py-3 rounded-lg bg-red-600 text-white font-bold hover:bg-red-700" onclick="return confirm('Apakah yakin ingin menolak?');">Tetapkan DITOLAK</button>
                </form>

                <button type="button" onclick="document.getElementById('form-minta-revisi').classList.remove('hidden')" class="px-6 py-3 rounded-lg bg-yellow-600 text-white font-bold hover:bg-yellow-700">Minta Revisi</button>
            </div>

            <div id="form-minta-revisi" class="hidden mt-4 p-4 bg-yellow-100 border border-yellow-300 rounded-lg">
                <form action="{{ route('admin.usulan.finalize', $usulan->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="revisi">
                    <label for="catatan_revisi" class="block text-sm font-medium text-gray-700 mb-2">Instruksi Revisi:</label>
                    <textarea id="catatan_revisi" name="catatan_revisi" rows="4" required class="w-full border-gray-300 rounded-lg shadow-sm"></textarea>
                    <button type="submit" class="mt-3 px-4 py-2 rounded bg-yellow-700 text-white font-semibold hover:bg-yellow-800">Kirim & Menunggu Revisi</button>
                </form>
            </div>
        @endif
    </div>

    {{-- HASIL FINAL --}}
    @if($usulan->nilai_final !== null)
        <div class="bg-indigo-50 border-l-4 border-indigo-500 p-6 shadow-xl rounded-lg mt-6">
            <h3 class="text-xl font-bold text-indigo-800 mb-2">Keputusan Akhir</h3>
            <p class="text-lg text-gray-700">Total Nilai Rata-rata: <strong class="text-2xl text-indigo-700">{{ number_format($usulan->nilai_final,2) }}</strong></p>
            <p class="text-lg text-gray-700">Status Final: <strong class="text-2xl @if($usulan->status=='Diterima') text-green-600 @elseif($usulan->status=='Menunggu Revisi') text-yellow-600 @else text-red-600 @endif">{{ ucfirst($usulan->status) }}</strong></p>
        </div>
    @endif
</div>
@endsection
