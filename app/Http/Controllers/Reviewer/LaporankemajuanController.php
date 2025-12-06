<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanKemajuan;
use Illuminate\Support\Facades\Auth;

class LaporanKemajuanController extends Controller
{
    /**
     * Index: tampilkan semua laporan kemajuan yang dikirim
     * dan ditugaskan ke reviewer yang sedang login
     */
    public function index()
    {
        $laporans = LaporanKemajuan::where('status', 'Dikirim')
            ->whereHas('usulan.reviewers', function($q) {
                $q->where('reviewer_id', Auth::id());
            })
            ->with('usulan.pengusul') // supaya bisa menampilkan nama pengusul
            ->get();

        return view('reviewer.laporanKemajuan.index', compact('laporans'));
    }

    /**
     * Show detail laporan kemajuan
     */
    public function show($id)
    {
        $laporan = LaporanKemajuan::with('usulan.pengusul', 'usulan.reviewers')
            ->findOrFail($id);

        // cek apakah reviewer yang login memang termasuk reviewer usulan ini
        if (!$laporan->usulan->reviewers->contains(Auth::id())) {
            abort(403, 'Anda tidak punya akses ke laporan ini.');
        }

        return view('reviewer.laporanKemajuan.show', compact('laporan'));
    }

    /**
     * Beri nilai / komentar reviewer
     */
    public function nilai(Request $request, $id)
    {
        $laporan = LaporanKemajuan::with('usulan.reviewers')->findOrFail($id);

        // cek reviewer
        if (!$laporan->usulan->reviewers->contains(Auth::id())) {
            abort(403, 'Anda tidak punya akses untuk menilai laporan ini.');
        }

        $request->validate([
            'nilai' => 'required|integer|min:0|max:100',
            'catatan_reviewer' => 'nullable|string|min:5',
            'status_review' => 'required|in:Disetujui,Ditolak,Perbaikan',
        ]);

        $laporan->nilai_reviewer = $request->nilai;
        $laporan->catatan_reviewer = $request->catatan_reviewer;
        $laporan->status = $request->status_review;
        $laporan->reviewer_id = Auth::id(); // optional: catat reviewer
        $laporan->save();

        return redirect()->route('reviewer.laporan-kemajuan.index')
                         ->with('success', 'Laporan kemajuan berhasil dinilai.');
    }
}
