<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\MasterPenilaian;

class ReviewerUsulanController extends Controller
{
    // ===========================
    // Daftar tugas review
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
                'usulan_reviewer.assigned_at',
                'usulan_reviewer.deadline',
                'usulan_reviewer.sudah_direview'
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
    // Detail review
    // ===========================
    public function review($id)
    {
        $reviewerId = Auth::id();

        $usulan = DB::table('usulans')->where('id', $id)->first();
        if (!$usulan) abort(404);

        $pivot = DB::table('usulan_reviewer')
            ->where('usulan_id', $id)
            ->where('reviewer_id', $reviewerId)
            ->first();
        if (!$pivot) abort(403);

        $pengusul = DB::table('users')->where('id', $usulan->id_user)->first();
        $komponen_penilaian = MasterPenilaian::orderBy('id')->get();

        // Nilai lama jika sudah ada
        $nilai_lama = collect();

        return view('reviewer.usulan.review', compact('usulan', 'pengusul', 'pivot', 'komponen_penilaian', 'nilai_lama'));
    }

    // ===========================
    // Detail review revisi
    // ===========================
    public function reviewRevisi($id)
    {
        $reviewerId = Auth::id();

        $usulan = DB::table('usulans')->where('id', $id)->first();
        if (!$usulan) abort(404);

        $pivot = DB::table('usulan_reviewer')
            ->where('usulan_id', $id)
            ->where('reviewer_id', $reviewerId)
            ->first();
        if (!$pivot) abort(403);

        $pengusul = DB::table('users')->where('id', $usulan->id_user)->first();
        $komponen_penilaian = MasterPenilaian::orderBy('id')->get();

        // Nilai lama revisi jika sudah ada
        $nilai_lama = DB::table('usulan_reviewer_penilaian_revisi')
            ->where('usulan_id', $id)
            ->where('reviewer_id', $reviewerId)
            ->get()
            ->keyBy('kriteria_id');

        return view('reviewer.usulan.review_revisi', compact('usulan', 'pengusul', 'pivot', 'komponen_penilaian', 'nilai_lama'));
    }

    // ===========================
    // Submit review normal
    // ===========================
    public function submitReview(Request $request, $id)
    {
        $reviewerId = Auth::id();

        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'catatan' => 'required|string',
        ]);

        DB::table('usulan_reviewer')
            ->where('usulan_id', $id)
            ->where('reviewer_id', $reviewerId)
            ->update([
                'sudah_direview' => true,
                'nilai' => $request->nilai,
                'catatan' => $request->catatan,
                'updated_at' => now(),
            ]);

        return redirect()->route('reviewer.usulan.review', $id)
            ->with('success', 'Review berhasil disimpan.');
    }

    // ===========================
    // Submit review revisi
    // ===========================
    public function submitReviewRevisi(Request $request, $id)
    {
        $reviewerId = Auth::id();

        $request->validate([
            'nilai.*' => 'required|numeric|min:0|max:100',
            'catatan.*' => 'nullable|string',
        ]);

        foreach ($request->nilai as $kriteriaId => $nilai) {
            DB::table('usulan_reviewer_penilaian_revisi')->updateOrInsert(
                [
                    'usulan_id' => $id,
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

        // Update status revisi usulan
        DB::table('usulans')
            ->where('id', $id)
            ->update(['status_revisi' => 'disetujui']);

        return redirect()->route('reviewer.usulan.index')
                         ->with('success', 'Review revisi berhasil disimpan.');
    }

    // ===========================
    // Download file (usulan / revisi)
    // ===========================
    public function downloadFile($id, $filename, $revisi = false)
    {
        $reviewerId = Auth::id();

        $cek = DB::table('usulan_reviewer')
            ->where('usulan_id', $id)
            ->where('reviewer_id', $reviewerId)
            ->first();

        if (!$cek) abort(403);

        $path = $revisi ? "usulan/revisi/$filename" : "usulan/$filename";

        if (!Storage::exists($path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::download($path);
    }

    // ===========================
    // Hasil penilaian (review & revisi)
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

        // Penilaian revisi
        $komponen = DB::table('master_penilaian')->orderBy('id')->get();
        $penilaianRevisi = DB::table('usulan_reviewer_penilaian_revisi')
            ->where('usulan_id', $id)
            ->where('reviewer_id', $reviewerId)
            ->get()
            ->keyBy('kriteria_id');

        // Map agar semua komponen muncul
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
}
