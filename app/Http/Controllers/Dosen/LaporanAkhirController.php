<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usulan;
use App\Models\LaporanAkhir;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // Pastikan Auth di-import

class LaporanAkhirController extends Controller
{
    /**
     * Menampilkan usulan yang siap untuk Laporan Akhir (status_lanjut = menunggu_laporan_akhir).
     */
    public function index()
    {
        $dosenId = Auth::user()->id;
        
        // FILTER KEPEMILIKAN: Menggunakan id_user
        $usulans = Usulan::where('id_user', $dosenId) 
                         ->where('status_lanjut', 'menunggu_laporan_akhir')
                         ->orWhere('status_lanjut', 'laporan_akhir_terkirim') 
                         ->with('laporanAkhir')
                         ->get();

        return view('dosen.laporan_akhir.index', compact('usulans'));
    }

    /**
     * Menampilkan form pembuatan/edit Laporan Akhir.
     */
    public function createOrEdit($usulanId)
    {
        // FILTER KEPEMILIKAN: Menggunakan id_user
        $usulan = Usulan::where('id_user', Auth::user()->id)
                        ->findOrFail($usulanId);
                        
        // Cek apakah usulan sudah berada di tahap Laporan Akhir
        if (!in_array($usulan->status_lanjut, ['menunggu_laporan_akhir', 'laporan_akhir_terkirim'])) {
            return redirect()->route('dosen.laporan_akhir.index')->with('error', 'Usulan ini belum memenuhi syarat untuk Laporan Akhir.');
        }

        $laporanAkhir = LaporanAkhir::firstOrNew(['usulan_id' => $usulanId]);

        // Catatan Reviewer/Admin untuk Perbaikan
        $catatanAdmin = $laporanAkhir?->catatan_admin;
        $catatanReviewer = $laporanAkhir?->catatan_reviewer;

        return view('dosen.laporan_akhir.form', compact('usulan', 'laporanAkhir', 'catatanAdmin', 'catatanReviewer'));
    }

    /**
     * Menyimpan/Memperbarui Laporan Akhir.
     */
    public function storeOrUpdate(Request $request, $usulanId)
    {
        // FILTER KEPEMILIKAN: Menggunakan id_user
        $usulan = Usulan::where('id_user', Auth::user()->id)
                        ->findOrFail($usulanId);
        
        $laporanAkhir = LaporanAkhir::firstOrNew(['usulan_id' => $usulanId]);

        $rules = [
            'ringkasan_hasil' => 'required|string|max:1000',
            'publikasi_target' => 'nullable|string|max:500',
            // File wajib jika belum ada, atau jika ada file baru diupload
            'file_laporan_akhir' => $laporanAkhir->file_laporan_akhir ? 'nullable|file|mimes:pdf|max:10240' : 'required|file|mimes:pdf|max:10240', // 10MB
        ];

        $request->validate($rules);
        
        $filePath = $laporanAkhir->file_laporan_akhir;
        
        // Handle file upload
        if ($request->hasFile('file_laporan_akhir')) {
            // Delete old file if exists
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file_laporan_akhir')->store('laporan_akhir_files', 'public');
        }

        $laporanAkhir->fill([
            'ringkasan_hasil' => $request->ringkasan_hasil,
            'publikasi_target' => $request->publikasi_target,
            'file_laporan_akhir' => $filePath,
            'status' => 'Terkirim', // Change status to 'Terkirim' upon submission/update
        ])->save();
        
        // Update usulan status_lanjut to indicate submission
        $usulan->update(['status_lanjut' => 'laporan_akhir_terkirim']);

        $message = $laporanAkhir->wasRecentlyCreated ? 'Laporan Akhir berhasil dikirim.' : 'Laporan Akhir berhasil diperbarui.';

        return redirect()->route('dosen.laporan_akhir.index')->with('success', $message);
    }
}