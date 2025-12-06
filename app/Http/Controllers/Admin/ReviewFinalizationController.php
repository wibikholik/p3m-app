<?php

namespace App\Http\Controllers\Admin;

use App\Models\Usulan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReviewFinalizationController extends Controller
{
    /**
     * Menangani finalisasi hasil review dan penentuan status usulan (termasuk Revisi).
     *
     * @param int $usulanId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function finalizeReview(int $usulanId)
    {
        // 1. Otorisasi
        // Pastikan hanya user dengan role Administrator/Koordinator yang dapat melakukan finalisasi.
        if (!Auth::user()->can('finalize_reviews')) {
            return back()->with('error', 'Anda tidak memiliki izin untuk melakukan finalisasi.');
        }

        $usulan = Usulan::with('penilaian')->findOrFail($usulanId);

        // 2. Agregasi Nilai (Contoh Logika)
        // Logika ini harus disesuaikan dengan sistem penilaian Anda (rata-rata, median, dll.)
        $totalReviewer = $usulan->reviewers()->count();
        $totalPenilaian = $usulan->penilaian->count();

        if ($totalPenilaian < $totalReviewer) {action()
            // Guard: Pastikan semua reviewer telah memberikan penilaian jika itu adalah syarat.
            return back()->with('error', 'Penilaian belum lengkap dari semua reviewer.');
        }

        // Contoh: Menghitung Rata-rata Nilai
        $avgScore = $usulan->penilaian->avg('total_nilai'); // Asumsi ada kolom 'total_nilai' di UsulanPenilaian

        // 3. Penentuan Keputusan Final
        $finalStatus = 'Ditolak';
        $revisionStatus = null;
        $adminNotes = null;
        $revisionThreshold = 75; // Nilai batas revisi (contoh)
        $acceptanceThreshold = 85; // Nilai batas penerimaan langsung (contoh)

        if ($avgScore >= $acceptanceThreshold) {
            $finalStatus = 'Diterima';
        } elseif ($avgScore >= $revisionThreshold) {
            // Keputusan: Perlu Revisi
            $finalStatus = 'Selesai Penilaian'; // Set status utama ke tahap selanjutnya
            $revisionStatus = 'dikembalikan'; // Mengaktifkan proses revisi
            
            // Logika untuk mengkompilasi Catatan Revisi dari Reviewer
            $feedback = $usulan->penilaian->pluck('catatan')->implode("\n---\n");
            $adminNotes = "Keputusan: Usulan perlu direvisi berdasarkan masukan dari reviewer.\n\nRingkasan Masukan:\n" . $feedback;
        }

        // 4. Update Model Usulan
        $usulan->status = $finalStatus;

        if ($revisionStatus) {
            $usulan->status_revisi = $revisionStatus; // Mengisi kolom revisi
            $usulan->catatan_revisi_admin = $adminNotes; // Mengisi kolom catatan revisi
            // 'file_revisi' dan 'tanggal_revisi' akan diisi saat pengusul meng-upload revisi
        }

        $usulan->save();

        // 5. Memicu Notifikasi (Email/Sistem)
        if ($revisionStatus === 'dikembalikan') {
            // TODO: Kirim notifikasi ke Pengusul bahwa usulan harus direvisi
            // NotifikasiPengusul::dispatch($usulan, 'revisi');
            $message = 'Finalisasi berhasil. Usulan telah ditetapkan membutuhkan revisi. Notifikasi telah dikirim ke pengusul.';
        } else {
            $message = "Finalisasi berhasil. Usulan ditetapkan sebagai: {$finalStatus}.";
        }

        return redirect()->route('admin.usulan.detail', $usulanId)->with('success', $message);
    }
}