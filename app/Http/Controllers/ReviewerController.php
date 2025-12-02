<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReviewerController extends Controller
{
    public function index()
    {
        $reviewerId = Auth::id();

        // Statistik
        $total_usulan = DB::table('usulan_reviewer')
            ->where('reviewer_id', $reviewerId)
            ->count();

        $belum_dinilai = DB::table('usulan_reviewer')
            ->where('reviewer_id', $reviewerId)
            ->where('status', 'assigned')
            ->count();

        $sudah_dinilai = DB::table('usulan_reviewer')
            ->where('reviewer_id', $reviewerId)
            ->where('status', 'done')
            ->count();

        $menunggu_revisi = DB::table('usulan_reviewer')
            ->where('reviewer_id', $reviewerId)
            ->where('status', 'revisi')
            ->count();

        // Daftar usulan untuk reviewer ini
        $usulan = DB::table('usulan_reviewer')
            ->join('usulans', 'usulan_reviewer.usulan_id', '=', 'usulans.id')
            ->join('users', 'usulans.id_user', '=', 'users.id')
            ->where('usulan_reviewer.reviewer_id', $reviewerId)
            ->select(
                'usulans.*',
                'users.name as pengusul_name',
                'usulan_reviewer.status as review_status',
                'usulan_reviewer.deadline as review_deadline'
            )
            ->orderBy('usulans.created_at', 'desc')
            ->get()
            ->map(function ($item) {
                // Convert created_at jadi Carbon agar bisa format
                $item->created_at_formatted = Carbon::parse($item->created_at)->format('d M Y');
                return $item;
            });

        return view('reviewer.dashboard', compact(
            'total_usulan',
            'belum_dinilai',
            'sudah_dinilai',
            'menunggu_revisi',
            'usulan'
        ));
    }
}
