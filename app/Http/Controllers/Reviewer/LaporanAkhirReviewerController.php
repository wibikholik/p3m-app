<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanAkhir;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LaporanAkhirReviewerController extends Controller
{
    /**
     * Menampilkan daftar semua Laporan Akhir yang ditugaskan kepada reviewer yang sedang login.
     */
    public function index()
    {
        $reviewerId = Auth::id();

        // Mengambil Laporan Akhir yang Usulan induknya ditugaskan ke reviewer ini
        $laporanAkhirs = LaporanAkhir::with(['usulan.pengusul'])
            // Filter: Usulan harus ditugaskan ke Reviewer ini
            ->whereHas('usulan.reviewers', function ($q) use ($reviewerId) {
                $q->where('users.id', $reviewerId);
            })
            // Filter: Hanya yang terkirim atau sudah dinilai
            ->whereIn('status', ['Terkirim', 'Disetujui', 'Ditolak', 'Perbaikan'])
            ->orderByDesc('created_at')
            ->get();
            
        return view('reviewer.laporan_akhir.index', compact('laporanAkhirs'));
    }

    /**
     * Menampilkan detail Laporan Akhir dan form penilaian.
     */
    public function show($id)
    {
        $reviewerId = Auth::id();

        $laporanAkhir = LaporanAkhir::with(['usulan.pengusul'])
            // Memastikan Laporan Akhir ini ditugaskan ke reviewer yang sedang login
            ->whereHas('usulan.reviewers', function ($q) use ($reviewerId) {
                $q->where('users.id', $reviewerId);
            })
            ->findOrFail($id);

        // Cek apakah laporan sudah dinilai oleh reviewer ini
        $is_reviewed = ($laporanAkhir->reviewer_id == $reviewerId && $laporanAkhir->status != 'Terkirim');
        
        return view('reviewer.laporan_akhir.show', compact('laporanAkhir', 'is_reviewed'));
    }

    /**
     * Menyimpan nilai dan keputusan review terhadap Laporan Akhir.
     */
    public function nilai(Request $request, $id)
    {
        $reviewerId = Auth::id();

        $laporanAkhir = LaporanAkhir::with('usulan')
            ->whereHas('usulan.reviewers', function ($q) use ($reviewerId) {
                $q->where('users.id', $reviewerId);
            })
            ->findOrFail($id);

        // Validasi: Hanya bisa menilai jika status Terkirim atau Perbaikan
        if ($laporanAkhir->status !== 'Terkirim' && $laporanAkhir->status !== 'Perbaikan') {
            return back()->with('error', 'Laporan Akhir ini tidak dapat dinilai ulang.');
        }

        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'catatan_reviewer' => 'nullable|string|min:5',
            'status_review' => 'required|in:Disetujui,Ditolak,Perbaikan',
        ]);

        // Simpan hasil penilaian
        $laporanAkhir->nilai_reviewer = $request->nilai;
        $laporanAkhir->catatan_reviewer = $request->catatan_reviewer;
        $laporanAkhir->status = $request->status_review;
        $laporanAkhir->reviewer_id = $reviewerId; 
        
        $laporanAkhir->save();

        return redirect()->route('reviewer.laporan_akhir.index')
            ->with('success', 'Penilaian Laporan Akhir berhasil disimpan. Status: ' . $laporanAkhir->status);
    }
}