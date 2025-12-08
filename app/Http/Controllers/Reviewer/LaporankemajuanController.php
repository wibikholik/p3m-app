<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanKemajuan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class LaporanKemajuanController extends Controller
{
    /**
     * Menampilkan daftar semua Laporan Kemajuan yang ditugaskan kepada reviewer.
     * (Menggunakan JOIN untuk filtering)
     */
   public function index()
    {
        $reviewerId = Auth::id();

        // Menggunakan Eloquent Query Builder untuk JOIN
        $laporans = LaporanKemajuan::query()
            ->join('usulans as u', 'laporan_kemajuan.id_usulan', '=', 'u.id')
            ->join('usulan_reviewer as ur', 'u.id', '=', 'ur.usulan_id')
            ->where('ur.reviewer_id', $reviewerId)
            
            ->whereIn('laporan_kemajuan.status', ['Terkirim', 'Disetujui', 'Ditolak', 'Perbaikan'])
            
            ->select(
                'laporan_kemajuan.*', 
                'u.judul as usulan_judul', 
                'u.id_user as pengusul_id' 
            )
            ->orderByDesc('laporan_kemajuan.created_at')
            ->get()
            
            ->map(function ($laporan) use ($reviewerId) {
                
                $pengusul = User::find($laporan->pengusul_id);
                $laporan->pengusul_nama = $pengusul->name ?? 'N/A';
                
                $laporan->status_review_saya = ($laporan->status === 'Terkirim') 
                                               ? 'Perlu Dinilai' 
                                               : 'Selesai Dinilai';

                return $laporan;
            });

        return view('reviewer.laporankemajuan.index', compact('laporans'));
    }
    
    // =======================================================
    // BARU: Menampilkan detail laporan dan form penilaian (SHOW)
    // =======================================================
    public function show($id)
    {
        $reviewerId = Auth::id();

        // Menggunakan Eager Loading untuk memuaskan View Blade
        $laporan = LaporanKemajuan::with(['usulan', 'usulan.pengusul'])
            // Memastikan Laporan Kemajuan ini ditugaskan ke reviewer yang sedang login
            ->whereHas('usulan.reviewers', function ($q) use ($reviewerId) {
                $q->where('users.id', $reviewerId);
            })
            ->findOrFail($id);

        // Tambahkan cek apakah sudah dinilai
        $is_reviewed = ($laporan->reviewer_id == $reviewerId && $laporan->status != 'Terkirim');

        // Note: Asumsi relasi User di model Usulan bernama 'pengusul' (u.id_user -> users.id)
        
        return view('reviewer.laporankemajuan.show', compact('laporan', 'is_reviewed'));
    }

    // =======================================================
    // BARU: Menyimpan nilai dan keputusan review (NILAI)
    // =======================================================
    public function nilai(Request $request, $id)
    {
        $reviewerId = Auth::id();

        // Ambil Laporan Kemajuan dan validasi kepemilikan
        $laporan = LaporanKemajuan::with('usulan')
            ->whereHas('usulan.reviewers', function ($q) use ($reviewerId) {
                $q->where('users.id', $reviewerId);
            })
            ->findOrFail($id);

        // Mencegah penilaian ulang jika sudah dinilai dan bukan status 'Perbaikan'
        if ($laporan->status !== 'Terkirim' && $laporan->status !== 'Perbaikan') {
            return back()->with('error', 'Laporan sudah disetujui/ditolak dan tidak dapat dinilai ulang.');
        }

        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'catatan_reviewer' => 'nullable|string|min:5',
            'status_review' => 'required|in:Disetujui,Ditolak,Perbaikan',
        ]);

        $laporan->nilai_reviewer = $request->nilai;
        $laporan->catatan_reviewer = $request->catatan_reviewer;
        $laporan->status = $request->status_review;
        $laporan->reviewer_id = $reviewerId; // Tandai reviewer yang menilai
        
        $laporan->save();

        return redirect()->route('reviewer.laporan-kemajuan.index')
            ->with('success', 'Penilaian Laporan Kemajuan berhasil disimpan. Status: ' . $laporan->status);
    }
}