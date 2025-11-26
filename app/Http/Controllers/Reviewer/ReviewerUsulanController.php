<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usulan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReviewerUsulanController extends Controller
{
    public function index()
    {
        $reviewerId = Auth::id();

        $usulans = DB::table('usulan_reviewer')
            ->where('reviewer_id', $reviewerId)
            ->join('usulans', 'usulan_reviewer.usulan_id', '=', 'usulans.id')
            ->select('usulans.*', 'usulan_reviewer.status as reviewer_status', 'usulan_reviewer.assigned_at', 'usulan_reviewer.deadline')
            ->orderBy('usulan_reviewer.assigned_at', 'desc')
            ->get();

        return view('reviewer.usulan.index', compact('usulans'));
    }

    // Detail usulan
    public function show($id)
    {
        $reviewerId = Auth::id();

        $usulan = DB::table('usulan_reviewer')
            ->where('reviewer_id', $reviewerId)
            ->where('usulan_id', $id)
            ->join('usulans', 'usulan_reviewer.usulan_id', '=', 'usulans.id')
            ->select('usulans.*', 'usulan_reviewer.status as reviewer_status', 'usulan_reviewer.assigned_at', 'usulan_reviewer.deadline', 'usulan_reviewer.catatan_assign')
            ->first();

        if (!$usulan) {
            abort(403, 'Anda tidak memiliki akses ke usulan ini.');
        }

        return view('reviewer.usulan.show', compact('usulan'));
    }

    // Terima tugas
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

    // Tolak tugas
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

}
