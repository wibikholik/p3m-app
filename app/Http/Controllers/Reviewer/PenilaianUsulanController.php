<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Usulan;
use App\Models\MasterPenilaian;
use App\Models\UsulanPenilaian;

class PenilaianUsulanController extends Controller
{
    // ===========================
    // Daftar usulan reviewer
    // ===========================
    public function index()
    {
        $reviewerId = Auth::id();

        $usulans = DB::table('usulans')
            ->leftJoin('usulan_reviewer', 'usulans.id', '=', 'usulan_reviewer.usulan_id')
            ->where('usulan_reviewer.reviewer_id', $reviewerId)
            ->select(
                'usulans.*',
                'usulan_reviewer.status as reviewer_status',
                'usulan_reviewer.sudah_direview',
                'usulan_reviewer.assigned_at',
                'usulan_reviewer.deadline',
                // MENGAMBIL STATUS REVISI DARI TABEL USULAN (usulans.status_revisi)
                'usulans.status_revisi' 
            )
            ->orderByDesc('usulan_reviewer.assigned_at')
            ->get()
            ->map(function ($item) {
                $item->reviewer_status = $item->reviewer_status ?? 'assigned';
                return $item;
            });

        return view('reviewer.usulan.index', compact('usulans'));
    }

    // ===========================
    // Form review usulan
    // ===========================
    public function review($usulan_id)
    {
        $reviewer_id = Auth::id();

        $usulan = Usulan::findOrFail($usulan_id);
        $komponen = MasterPenilaian::where('is_active', 1)->orderBy('order')->get();

        // Ambil nilai lama reviewer ini
        $nilai_lama = UsulanPenilaian::where('usulan_id', $usulan_id)
            ->where('reviewer_id', $reviewer_id)
            ->get()
            ->keyBy('komponen_id');

        return view('reviewer.usulan.review', compact('usulan', 'komponen', 'nilai_lama'));
    }
    
    // =======================================================
    // BARU: Form Review Revisi (Untuk mengatasi Error Method Not Found)
    // =======================================================
    public function reviewRevisi($usulan_id)
    {
        $reviewer_id = Auth::id();

        $usulan = Usulan::findOrFail($usulan_id);
        $komponen = MasterPenilaian::where('is_active', 1)->orderBy('order')->get();

        // Ambil nilai lama revisi reviewer ini (dari tabel penilaian revisi)
        $nilai_lama = DB::table('usulan_reviewer_penilaian_revisi')
            ->where('usulan_id', $usulan_id)
            ->where('reviewer_id', $reviewer_id)
            ->get()
            ->keyBy('kriteria_id');

        // Note: Asumsikan Anda memiliki view 'reviewer.usulan.review_revisi'
        return view('reviewer.usulan.review_revisi', compact('usulan', 'komponen', 'nilai_lama'));
    }

    // =======================================================
    // Submit review usulan
    // =======================================================
    public function submitReview(Request $request, $usulan_id)
    {
        $reviewer_id = Auth::id();

        $request->validate([
            'nilai' => 'required|array',
            'nilai.*' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|array',
        ]);

        // Hapus nilai lama
        UsulanPenilaian::where('usulan_id', $usulan_id)
            ->where('reviewer_id', $reviewer_id)
            ->delete();

        // Simpan nilai baru
        foreach ($request->input('nilai', []) as $komponen_id => $value) {
            UsulanPenilaian::create([
                'usulan_id' => $usulan_id,
                'reviewer_id' => $reviewer_id,
                'komponen_id' => $komponen_id,
                'nilai' => $value,
                'catatan' => $request->input("catatan.$komponen_id") ?? null,
            ]);
        }

        // 1. Update pivot sudah_direview
        DB::table('usulan_reviewer')
            ->where('usulan_id', $usulan_id)
            ->where('reviewer_id', $reviewer_id)
            ->update([
                'sudah_direview' => true,
                'updated_at' => now()
            ]);

        // 2. Logika Update Status Usulan di Tabel 'usulans' (Multi-Reviewer Logic)
        $totalReviewer = DB::table('usulan_reviewer')->where('usulan_id', $usulan_id)->count();
        $reviewerSelesai = DB::table('usulan_reviewer')
            ->where('usulan_id', $usulan_id)
            ->where('sudah_direview', true)
            ->count();
        
        if ($totalReviewer > 0 && $totalReviewer == $reviewerSelesai) {
            // Jika SEMUA reviewer sudah selesai, ubah status usulan menjadi 'selesai_direview'
            DB::table('usulans')
                ->where('id', $usulan_id)
                ->update([
                    'status' => 'selesai_direview',
                    'updated_at' => now(),
                ]);
        } else {
            // Jika BELUM semua selesai, ubah status usulan menjadi 'sedang_direview'
            DB::table('usulans')
                ->where('id', $usulan_id)
                ->whereNotIn('status', ['selesai_direview', 'diterima', 'ditolak'])
                ->update([
                    'status' => 'sedang_direview',
                    'updated_at' => now(),
                ]);
        }

        return redirect()->route('reviewer.usulan.index')
                             ->with('success', 'Penilaian berhasil disimpan. Status usulan diperbarui.');
    }

    // ===========================
    // Form hasil penilaian
    // ===========================
   public function showHasilPenilaian($id)
    {
        $reviewerId = Auth::id();

        // Ambil usulan
        $usulan = DB::table('usulans')->where('id', $id)->first();
        if (!$usulan) abort(404);

        // Penilaian awal
        $penilaian = DB::table('usulan_penilaian')
            ->where('usulan_id', $id)
            ->where('reviewer_id', $reviewerId)
            ->get()
            ->map(function ($p) {
                $p->komponen = DB::table('master_penilaian')->where('id', $p->komponen_id)->first();
                return $p;
            });
        $totalNilai = $penilaian->sum(function ($p) {
            return ($p->nilai * $p->komponen->bobot / 100);
        });

        // Penilaian revisi per komponen
        $komponen = DB::table('master_penilaian')->orderBy('id')->get();
        $penilaianRevisi = DB::table('usulan_reviewer_penilaian_revisi')
            ->where('usulan_id', $id)
            ->where('reviewer_id', $reviewerId)
            ->get()
            ->keyBy('kriteria_id'); // kriteria_id = master_penilaian.id

        // Map ulang supaya semua komponen tetap tampil, meskipun belum dinilai revisi
        $penilaianRevisiFull = $komponen->map(function ($k) use ($penilaianRevisi) {
            $p = $penilaianRevisi->get($k->id);
            return (object)[
                'komponen' => $k,
                'nilai' => $p->nilai ?? 0,
                'catatan' => $p->catatan ?? '-'
            ];
        });

        $totalNilaiRevisi = $penilaianRevisiFull->sum(function ($p) {
            return ($p->nilai * $p->komponen->bobot / 100);
        });

        return view('reviewer.usulan.hasil', compact(
            'usulan',
            'penilaian',
            'totalNilai',
            'penilaianRevisiFull',
            'totalNilaiRevisi'
        ));
    }


    // =======================================================
    // Submit review revisi
    // =======================================================
    public function submitReviewRevisi(Request $request, $usulan_id)
    {
        $reviewerId = Auth::id();

        // VALIDASI UNTUK NILAI PER KOMPONEN
        $request->validate([
            'nilai' => 'required|array',
            'nilai.*' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|array',
        ]);
        
        // 1. Simpan nilai revisi per komponen ke tabel usulan_reviewer_penilaian_revisi
        foreach ($request->input('nilai', []) as $kriteriaId => $nilai) {
            DB::table('usulan_reviewer_penilaian_revisi')->updateOrInsert(
                [
                    'usulan_id' => $usulan_id,
                    'reviewer_id' => $reviewerId,
                    'kriteria_id' => $kriteriaId 
                ],
                [
                    'nilai' => $nilai,
                    'catatan' => $request->catatan[$kriteriaId] ?? null,
                    'updated_at' => now(),
                ]
            );
        }


        // 2. Update status review revisi di tabel pivot
        DB::table('usulan_reviewer')
            ->where('usulan_id', $usulan_id)
            ->where('reviewer_id', $reviewerId)
            ->update([
                'sudah_direview_revisi' => true,
                'updated_at' => now(),
            ]);

        // 3. Logika Update Status Usulan Global (Jika semua reviewer selesai)
        $totalReviewer = DB::table('usulan_reviewer')->where('usulan_id', $usulan_id)->count();
        $reviewerSelesaiRevisi = DB::table('usulan_reviewer')
            ->where('usulan_id', $usulan_id)
            ->where('sudah_direview_revisi', true)
            ->count();
        
        if ($totalReviewer > 0 && $totalReviewer == $reviewerSelesaiRevisi) {
            // Jika SEMUA reviewer selesai me-review revisi:
            // Ubah status usulan menjadi 'selesai_review_revisi' dan status_revisi menjadi 'disetujui'
            DB::table('usulans')
                ->where('id', $usulan_id)
                ->update([
                    'status' => 'selesai_review_revisi', 
                    'status_revisi' => 'disetujui', // Status revisi: disetujui (siap finalisasi Admin)
                    'updated_at' => now(),
                ]);
        }
        
        return redirect()->route('reviewer.usulan.index')
                             ->with('success', 'Penilaian revisi berhasil disimpan dan usulan siap untuk finalisasi.');
    }
}