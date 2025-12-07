<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usulan;
use App\Models\User;
use App\Models\MasterPenilaian; // <-- PERBAIKAN 1: Import Model MasterPenilaian
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\MasterKelengkapan;
use App\Models\UsulanKelengkapan;

class AdminUsulanController extends Controller
{
    /**
     * Menampilkan daftar usulan dengan filter status
     */
    public function usulanIndex(Request $request)
    {
        $filterStatus = $request->input('status');

        $query = Usulan::with(['pengusul', 'pengumuman.kategori'])
            ->withCount('reviewers')
            ->withCount(['reviewers as reviewers_done' => function($q) {
                $q->where('usulan_reviewer.sudah_direview', true);
            }])
            ->withAvg('penilaian', 'nilai');

        if ($filterStatus) {
            $query->where('status', $filterStatus);
        }

        $usulan = $query->latest()->get()->map(function($item) {
            $item->average_score = $item->penilaian_avg_nilai;
            return $item;
        });

        return view('admin.usulan.index', compact('usulan'));
    }

    /**
     * Menampilkan rekap review awal & revisi (Perbaikan Relasi Revisi)
     */
    public function show($id)
    {
        // PERBAIKAN 2: Menggunakan Model MasterPenilaian yang sudah di-import
        $allKomponen = MasterPenilaian::pluck('nama', 'id');
        $allBobot = MasterPenilaian::pluck('bobot', 'id');
        
        // 1. Ambil usulan dengan relasi yang dibutuhkan
        $usulan = Usulan::with(['reviewers', 'penilaian.komponen', 'penilaian.reviewer', 'penilaianRevisi.reviewer'])->findOrFail($id);

        $totalPerReviewer = [];
        $totalRevisiPerReviewer = [];

        foreach($usulan->reviewers as $reviewer){
            
            // --- 1. Penilaian Awal (Nilai Tertimbang) ---
            $nilaiReviewer = $usulan->penilaianPerReviewer($reviewer->id)->get();

            $totalTertimbang = $nilaiReviewer->sum(function ($p) {
                $bobot = $p->komponen->bobot ?? 0;
                return ($p->nilai * $bobot / 100);
            });
            
            $totalPerReviewer[$reviewer->id] = [
                'reviewer' => $reviewer,
                'nilai' => $totalTertimbang, 
                'detail' => $nilaiReviewer
            ];

            // --- 2. Penilaian Revisi (Nilai Tertimbang) ---
            $nilaiRevisi = $usulan->penilaianRevisiPerReviewer($reviewer->id)->get();

            $totalRevisiTertimbang = 0;
            
            // Perbaiki relasi komponen pada setiap item revisi dan hitung total tertimbang
            $nilaiRevisi = $nilaiRevisi->map(function ($p) use ($allKomponen, $allBobot, &$totalRevisiTertimbang) {
                // Asumsi: Kolom di tabel revisi adalah 'kriteria_id'
                $kriteriaId = $p->kriteria_id; 
                $bobot = $allBobot[$kriteriaId] ?? 0;
                $komponenNama = $allKomponen[$kriteriaId] ?? 'Kriteria Tdk Ditemukan';

                // Buat objek komponen palsu untuk konsistensi view
                $p->komponen = (object)['nama' => $komponenNama, 'bobot' => $bobot]; 
                
                // Hitung total tertimbang 
                $totalRevisiTertimbang += ($p->nilai * $bobot / 100);
                return $p;
            });
            
            $totalRevisiPerReviewer[$reviewer->id] = [
                'reviewer' => $reviewer,
                'nilai' => $totalRevisiTertimbang, 
                'detail' => $nilaiRevisi
            ];
        }

        // 2. Cek semua reviewer sudah menilai DARI STATUS USULAN
        $finalStatus = ['selesai_direview', 'Diterima', 'Ditolak', 'Menunggu Revisi', 'selesai_review_revisi'];
        $allReviewed = in_array($usulan->status, $finalStatus);

        // 3. Hitung nilai final sementara jika kriteria sudah terpenuhi
        if ($allReviewed && $usulan->nilai_final === null) {
             $avgFinalScore = collect($totalPerReviewer)->avg('nilai');
             $usulan->nilai_final = $avgFinalScore; 
        }

        return view('admin.usulan.rekap', compact('usulan','totalPerReviewer','totalRevisiPerReviewer','allReviewed'));
    }


    /**
     * Finalisasi keputusan usulan
     */
    public function finalize(Request $request, Usulan $usulan)
    {
        // Hitung rata-rata tertimbang dari SEMUA reviewer untuk disimpan sebagai nilai final
        $allReviewScores = DB::table('usulan_penilaian')
            ->select(
                DB::raw('SUM(up.nilai * mp.bobot / 100) as total_weighted_score')
            )
            ->from('usulan_penilaian as up')
            ->join('master_penilaian as mp', 'up.komponen_id', '=', 'mp.id')
            ->where('up.usulan_id', $usulan->id)
            ->groupBy('up.reviewer_id')
            ->pluck('total_weighted_score');
            
        $averageWeightedScore = $allReviewScores->avg();

        DB::beginTransaction();

        try {
            $usulan->nilai_final = $averageWeightedScore;
            $action = $request->input('action');

            if ($action === 'diterima') {
                $usulan->status = 'Diterima';
                $usulan->status_revisi = 'disetujui';
                $usulan->status_lanjut = 'siap_laporan';
                $message = 'Usulan berhasil ditetapkan DITERIMA dan siap untuk laporan kemajuan.';

            } elseif ($action === 'ditolak') {
                $usulan->status = 'Ditolak';
                $usulan->status_revisi = null;
                $usulan->status_lanjut = null;
                $message = 'Usulan berhasil ditetapkan DITOLAK.';

            } elseif ($action === 'revisi') {
                $request->validate([
                    'catatan_revisi' => 'required|string|min:10',
                ], [
                    'catatan_revisi.required' => 'Catatan/Instruksi Revisi wajib diisi.',
                    'catatan_revisi.min' => 'Catatan minimal 10 karakter.',
                ]);

                $usulan->status = 'Menunggu Revisi';
                $usulan->status_revisi = 'dikembalikan';
                $usulan->catatan_revisi_admin = $request->catatan_revisi;
                $usulan->status_lanjut = null;
                $message = 'Usulan berhasil ditetapkan Menunggu Revisi.';

            } else {
                DB::rollBack();
                return redirect()->back()->with('error', 'Aksi finalisasi tidak valid.');
            }

            $usulan->save();
            DB::commit();

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Finalize Review Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses keputusan final. Terjadi kesalahan sistem.');
        }
    }

    /**
     * Detail usulan
     */
    public function usulanDetail($id)
    {
        $usulan = Usulan::with(['pengusul', 'anggota', 'pengumuman.kategori'])->findOrFail($id);

        $masterKelengkapan = MasterKelengkapan::where('is_active', 1)
            ->orderBy('order')
            ->get();

        $ceklistUsulan = UsulanKelengkapan::where('usulan_id', $id)
            ->get()
            ->keyBy('kelengkapan_id');

        return view('admin.usulan.show', compact('usulan','masterKelengkapan','ceklistUsulan'));
    }

    public function previewFile($filename)
    {
        $path = public_path('storage/usulan/' . $filename);
        if (!file_exists($path)) abort(404, 'File tidak ditemukan.');
        return response()->file($path);
    }

    public function downloadFile($filename)
    {
        $path = public_path('storage/usulan/' . $filename);
        if (!file_exists($path)) abort(404, 'File tidak ditemukan.');
        return response()->download($path);
    }

    public function verifikasi(Request $request, $id)
    {
        $usulan = Usulan::findOrFail($id);

        $checked = $request->input('checklist', []);
        foreach ($checked as $kelengkapanId) {
            UsulanKelengkapan::create([
                'usulan_id'       => $id,
                'kelengkapan_id'  => $kelengkapanId,
                'status'          => 1,
            ]);
        }

        $action = $request->action;
        if ($action === 'approve') {
            $usulan->status = 'lolos_administrasi';
            $usulan->catatan_admin = null;
        } else {
            if (!$request->catatan_admin) {
                return back()->with('error', 'Catatan wajib diisi jika menolak.');
            }
            $usulan->status = 'ditolak_administrasi';
            $usulan->catatan_admin = $request->catatan_admin;
        }

        $usulan->save();

        return back()->with('success', 'Status verifikasi berhasil diperbarui.');
    }

    public function assignReviewerPage($id)
    {
        $usulan = Usulan::with('reviewers')->findOrFail($id);
        $reviewers = User::whereHas('roles', fn($q) => $q->where('name', 'reviewer'))->get();
        return view('admin.usulan.assign_reviewer', compact('usulan', 'reviewers'));
    }

    public function assignReviewer(Request $request, $id)
    {
        $usulan = Usulan::findOrFail($id);

        $data = $request->validate([
            'reviewers' => 'required|array|min:1',
            'reviewers.*.id' => 'required|exists:users,id',
            'reviewers.*.deadline' => 'nullable|date',
            'reviewers.*.catatan_assign' => 'nullable|string',
        ]);

        DB::transaction(function() use ($data, $usulan) {
            foreach ($data['reviewers'] as $rev) {
                $exists = DB::table('usulan_reviewer')
                    ->where('usulan_id', $usulan->id)
                    ->where('reviewer_id', $rev['id'])
                    ->exists();
                if ($exists) continue;

                DB::table('usulan_reviewer')->insert([
                    'usulan_id'      => $usulan->id,
                    'reviewer_id'    => $rev['id'],
                    'assigned_by'    => auth()->id(),
                    'assigned_at'    => now(),
                    'deadline'       => $rev['deadline'] ?? null,
                    'catatan_assign' => $rev['catatan_assign'] ?? null,
                    'status'         => 'assigned',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }

            $usulan->status = 'sedang_di_review';
            $usulan->save();
        });

        return back()->with('success', 'Reviewer berhasil ditugaskan dan status usulan diperbarui.');
    }
    
}