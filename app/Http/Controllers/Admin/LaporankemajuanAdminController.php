<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanKemajuan;
use App\Models\Usulan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LaporanKemajuanAdminController extends Controller
{
    /**
     * Menampilkan daftar semua Laporan Kemajuan untuk Monev Admin.
     */
    public function index()
    {
        // Variabel dikirim sebagai $laporans (jamak)
        $laporans = LaporanKemajuan::with(['usulan.pengusul', 'reviewer'])
            ->orderByDesc('created_at')
            ->get();
            
        return view('admin.monev.laporan_kemajuan.index', compact('laporans'));
    }

    /**
     * Menampilkan detail Laporan Kemajuan, termasuk hasil review.
     */
    public function show($id)
    {
        $laporan = LaporanKemajuan::with(['usulan.pengusul', 'reviewer'])
            ->findOrFail($id);

        // Status review dari reviewer: True jika status Laporan Kemajuan sudah bukan 'Terkirim'
        $is_reviewed = $laporan->status !== 'Terkirim';

        return view('admin.monev.laporan_kemajuan.show', compact('laporan', 'is_reviewed'));
    }
    
    /**
     * Memproses keputusan final Admin setelah review laporan kemajuan.
     */
    public function finalize(Request $request, $id)
    {
        $laporan = LaporanKemajuan::with('usulan')->findOrFail($id);
        
        // Admin hanya bisa memfinalisasi jika Laporan sudah dinilai oleh Reviewer
        if ($laporan->status === 'Terkirim') {
            return back()->with('error', 'Laporan belum selesai di-review.');
        }

        $request->validate([
            'keputusan_akhir' => 'required|in:Lanjut Laporan Akhir,Tolak Laporan Akhir',
            'catatan_admin' => 'nullable|string'
        ]);
        
        // Lakukan tindakan berdasarkan keputusan Admin
        if ($request->keputusan_akhir === 'Lanjut Laporan Akhir') {
            $laporan->usulan->update([
                'status_lanjut' => 'menunggu_laporan_akhir',
                'status' => 'Didanai'
            ]);
            $laporan->update(['catatan_admin' => $request->catatan_admin]);
            $message = 'Usulan diloloskan ke tahap Laporan Akhir.';
        } else {
            // Tolak laporan akhir
             $laporan->usulan->update([
                'status_lanjut' => 'ditolak_laporan_kemajuan',
                'status' => 'Ditolak'
            ]);
            $laporan->update(['catatan_admin' => $request->catatan_admin]);
            $message = 'Usulan dihentikan dan status diubah menjadi Ditolak.';
        }

        return redirect()->route('admin.monev.laporan_kemajuan.index')
                         ->with('success', $message);
    }
}