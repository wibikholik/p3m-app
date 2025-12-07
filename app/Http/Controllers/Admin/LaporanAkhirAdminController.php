<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanAkhir;
use App\Models\Usulan;
use App\Models\User;

class LaporanAkhirAdminController extends Controller
{
    /**
     * Menampilkan daftar semua Laporan Akhir untuk Monev Admin.
     */
    public function index()
    {
        // Ambil semua Laporan Akhir, muat Usulan, Pengusul, dan Reviewer
        $laporanAkhirs = LaporanAkhir::with(['usulan.pengusul', 'reviewer'])
            ->orderByDesc('created_at')
            ->get();
            
        return view('admin.monev.laporan_akhir.index', compact('laporanAkhirs'));
    }

    /**
     * Menampilkan detail Laporan Akhir, hasil review, dan form finalisasi.
     */
    public function show($id)
    {
        $laporanAkhir = LaporanAkhir::with(['usulan.pengusul', 'reviewer'])
            ->findOrFail($id);

        // Status review dari reviewer: True jika status Laporan Akhir sudah bukan 'Terkirim'
        $is_reviewed = $laporanAkhir->status !== 'Terkirim';

        return view('admin.monev.laporan_akhir.show', compact('laporanAkhir', 'is_reviewed'));
    }
    
    /**
     * Memproses keputusan final Admin setelah review Laporan Akhir.
     * Mengubah status Usulan ke status Selesai.
     */
    public function finalize(Request $request, $id)
    {
        $laporanAkhir = LaporanAkhir::with('usulan')->findOrFail($id);
        
        // Admin hanya bisa memfinalisasi jika Laporan sudah dinilai oleh Reviewer
        if ($laporanAkhir->status === 'Terkirim') {
            return back()->with('error', 'Laporan Akhir belum selesai di-review oleh reviewer.');
        }

        $request->validate([
            'keputusan_akhir' => 'required|in:Selesai,Tolak Final',
            'catatan_admin' => 'nullable|string'
        ]);
        
        // Lakukan tindakan berdasarkan keputusan Admin
        if ($request->keputusan_akhir === 'Selesai') {
            $laporanAkhir->usulan->update([
                'status_lanjut' => 'selesai_laporan_akhir', // Status Usulan: Selesai di tahap Laporan Akhir
                'status' => 'Selesai' // Status Usulan Utama: Selesai
            ]);
            $laporanAkhir->update(['catatan_admin' => $request->catatan_admin]);
            $message = 'Usulan diselesaikan (Lunas/Selesai) dan status diperbarui.';
        } else {
            // Tolak final (Biasanya setelah status Ditolak dari Reviewer)
             $laporanAkhir->usulan->update([
                'status_lanjut' => 'ditolak_final',
                'status' => 'Ditolak' // Status Usulan Utama: Ditolak
            ]);
            $laporanAkhir->update(['catatan_admin' => $request->catatan_admin]);
            $message = 'Usulan ditolak di tahap final dan status diubah menjadi Ditolak.';
        }

        return redirect()->route('admin.monev.laporan_akhir.index')
                         ->with('success', $message);
    }
}