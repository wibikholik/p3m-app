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
                'usulan_reviewer.status_revisi'
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

    // ===========================
    // Submit review usulan
    // ===========================
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

        // Update pivot sudah_direview
        DB::table('usulan_reviewer')
            ->where('usulan_id', $usulan_id)
            ->where('reviewer_id', $reviewer_id)
            ->update([
                'sudah_direview' => true,
                'updated_at' => now()
            ]);

        return redirect()->route('reviewer.usulan.index')
                         ->with('success', 'Penilaian berhasil disimpan.');
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


    // ===========================
    // Submit review revisi
    // ===========================
    public function submitReviewRevisi(Request $request, $usulan_id)
    {
        $reviewerId = Auth::id();

        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);

        DB::table('usulan_reviewer')
            ->where('usulan_id', $usulan_id)
            ->where('reviewer_id', $reviewerId)
            ->update([
                'nilai_revisi' => $request->nilai,
                'catatan_revisi' => $request->catatan,
                'sudah_direview_revisi' => true,
                'status_revisi' => 'disetujui',
                'updated_at' => now(),
            ]);

        return redirect()->route('reviewer.usulan.review_revisi', $usulan_id)
                         ->with('success', 'Penilaian revisi berhasil disimpan.');
    }
}
