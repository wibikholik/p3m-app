<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\MasterPenilaian; // Tambahkan ini

class ReviewerUsulanController extends Controller
{
    public function index()
    {
        $reviewerId = Auth::id();

        $usulans = DB::table('usulans')
            ->leftJoin('usulan_reviewer', function($join) use ($reviewerId) {
                $join->on('usulans.id', '=', 'usulan_reviewer.usulan_id')
                     ->where('usulan_reviewer.reviewer_id', $reviewerId);
            })
            ->select(
                'usulans.*',
                'usulan_reviewer.status as reviewer_status',
                'usulan_reviewer.assigned_at',
                'usulan_reviewer.deadline',
                'usulan_reviewer.sudah_direview'
            )
            ->orderBy('usulan_reviewer.assigned_at', 'desc')
            ->get()
            ->map(function($item) {
                $item->reviewer_status = $item->reviewer_status ?? 'assigned';
                return $item;
            });

        return view('reviewer.usulan.index', compact('usulans'));
    }

    public function show($id)
    {
        $reviewerId = Auth::id();

        $usulan = DB::table('usulan_reviewer')
            ->where('reviewer_id', $reviewerId)
            ->where('usulan_id', $id)
            ->join('usulans', 'usulan_reviewer.usulan_id', '=', 'usulans.id')
            ->select(
                'usulans.*',
                'usulan_reviewer.status as reviewer_status',
                'usulan_reviewer.assigned_at',
                'usulan_reviewer.deadline',
                'usulan_reviewer.catatan_assign',
                'usulan_reviewer.sudah_direview'
            )
            ->first();

        if (!$usulan) abort(403);

        return view('reviewer.usulan.show', compact('usulan'));
    }

    public function accept($id)
    {
        $reviewerId = Auth::id();

        DB::table('usulan_reviewer')
            ->where('usulan_id', $id)
            ->where('reviewer_id', $reviewerId)
            ->update([
                'status' => 'accepted',
                'accepted_at' => now(),
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Tugas berhasil diterima.');
    }

    public function decline($id)
    {
        $reviewerId = Auth::id();

        DB::table('usulan_reviewer')
            ->where('usulan_id', $id)
            ->where('reviewer_id', $reviewerId)
            ->update([
                'status' => 'declined',
                'declined_at' => now(),
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Tugas telah ditolak.');
    }

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

        return view('reviewer.usulan.review', compact('usulan', 'pengusul', 'pivot', 'komponen_penilaian'));
    }

    public function submitReview(Request $request, $id)
    {
        $reviewerId = Auth::id();

        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'catatan' => 'required|string',
        ]);

        $pivot = DB::table('usulan_reviewer')
            ->where('usulan_id', $id)
            ->where('reviewer_id', $reviewerId)
            ->first();

        if (!$pivot) abort(403);

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

    public function downloadFile($id, $filename)
    {
        $reviewerId = Auth::id();

        $cek = DB::table('usulan_reviewer')
            ->where('usulan_id', $id)
            ->where('reviewer_id', $reviewerId)
            ->first();

        if (!$cek) abort(403);

        $path = "usulan/$filename";

        if (!Storage::exists($path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::download($path);
    }
}
