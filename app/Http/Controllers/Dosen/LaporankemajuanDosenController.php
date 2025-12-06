<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanKemajuan;
use Illuminate\Support\Facades\Auth;

class LaporanKemajuanDosenController extends Controller
{
    public function index()
    {
        $reviewerId = Auth::id();

        $laporans = LaporanKemajuan::with(['usulan', 'usulan.pengusul'])
            ->where('status', 'Terkirim') // sesuai data di DB
            ->whereHas('usulan.reviewers', function ($q) use ($reviewerId) {
                // pakai relasi belongsToMany ke User
                $q->where('users.id', $reviewerId);
            })
            ->get();

        return view('reviewer.laporanKemajuan.index', compact('laporans'));
    }

    public function show($id)
    {
        $reviewerId = Auth::id();

        $laporan = LaporanKemajuan::with(['usulan', 'usulan.pengusul'])
            ->where('status', 'Terkirim')
            ->whereHas('usulan.reviewers', function ($q) use ($reviewerId) {
                $q->where('users.id', $reviewerId);
            })
            ->findOrFail($id);

        return view('reviewer.laporanKemajuan.show', compact('laporan'));
    }

    public function nilai(Request $request, $id)
    {
        $reviewerId = Auth::id();

        $laporan = LaporanKemajuan::whereHas('usulan.reviewers', function ($q) use ($reviewerId) {
                $q->where('users.id', $reviewerId);
            })
            ->findOrFail($id);

        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'catatan_reviewer' => 'nullable|string|min:5',
            'status_review' => 'required|in:Disetujui,Ditolak,Perbaikan',
        ]);

        $laporan->nilai_reviewer = $request->nilai;
        $laporan->catatan_reviewer = $request->catatan_reviewer;
        $laporan->status = $request->status_review;
        $laporan->reviewer_id = $reviewerId;
        $laporan->save();

        return redirect()->route('reviewer.laporan-kemajuan.index')
            ->with('success', 'Laporan kemajuan berhasil dinilai.');
    }
}
