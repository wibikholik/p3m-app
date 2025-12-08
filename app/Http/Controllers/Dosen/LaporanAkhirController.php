<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usulan;
use App\Models\LaporanAkhir;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class LaporanAkhirController extends Controller
{
    /**
     * Menampilkan usulan yang siap untuk Laporan Akhir.
     */
    public function index()
    {
        $dosenId = Auth::user()->id;
        
        // Memastikan filter kepemilikan (id_user) diterapkan pada semua kondisi status_lanjut
        $usulans = Usulan::where('id_user', $dosenId) 
            ->where(function ($query) {
                // Menarik usulan yang baru masuk tahap laporan akhir
                $query->where('status_lanjut', 'menunggu_laporan_akhir') 
                      // Menarik usulan yang sudah terkirim (untuk dilihat statusnya)
                      ->orWhere('status_lanjut', 'selesai_laporan_akhir')
                      ->orWhere('status_lanjut', 'laporan_akhir_terkirim'); 
                      // Anda bisa menambahkan status lain di sini, misal: 'laporan_akhir_direvisi'
            })
            ->with('laporanAkhir')
            ->get();

        return view('dosen.laporan_akhir.index', compact('usulans'));
    }

    /**
     * Menampilkan form pembuatan/edit Laporan Akhir.
     */
    public function createOrEdit($usulanId)
    {
        $dosenId = Auth::user()->id;
        
        $usulan = Usulan::where('id_user', $dosenId)
                         ->findOrFail($usulanId);
                         
        // Cek apakah usulan sudah berada di tahap Laporan Akhir
        if (!in_array($usulan->status_lanjut, ['menunggu_laporan_akhir', 'selesai_laporan_akhir','laporan_akhir_terkirim'])) {
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
        $dosenId = Auth::user()->id;
        
        $usulan = Usulan::where('id_user', $dosenId)
                         ->findOrFail($usulanId);
                         
        $laporanAkhir = LaporanAkhir::firstOrNew(['usulan_id' => $usulanId]);

        $rules = [
            'ringkasan_hasil' => 'required|string|max:1000',
            'publikasi_target' => 'nullable|string|max:500',
            'file_laporan_akhir' => $laporanAkhir->file_laporan_akhir 
                                    ? 'nullable|file|mimes:pdf|max:10240' 
                                    : 'required|file|mimes:pdf|max:10240', // 10MB
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
            'status' => 'Terkirim', // Laporan dianggap Terkirim setiap kali Dosen melakukan simpan/update
        ])->save();
        
        // Update usulan status_lanjut to indicate submission
        $usulan->update(['status_lanjut' => 'laporan_akhir_terkirim']);

        $message = $laporanAkhir->wasRecentlyCreated ? 'Laporan Akhir berhasil dikirim.' : 'Laporan Akhir berhasil diperbarui dan dikirim ulang.';

        return redirect()->route('dosen.laporan_akhir.index')->with('success', $message);
    }
}