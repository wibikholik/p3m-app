<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReviewerController extends Controller
{
    /**
     * Menampilkan dashboard reviewer dengan statistik dan daftar usulan.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $reviewerId = Auth::id();

        // --- 1. Statistik ---
        $baseQuery = DB::table('usulan_reviewer')
            ->where('reviewer_id', $reviewerId);

        $total_usulan = (clone $baseQuery)->count();
        $belum_dinilai = (clone $baseQuery)->where('status', 'assigned')->count();
        $sudah_dinilai = (clone $baseQuery)->where('status', 'done')->count();
        $menunggu_revisi = (clone $baseQuery)->where('status', 'revisi')->count();

        // --- 2. Daftar Usulan dengan Filter, Pencarian, dan Paginasi ---
        $usulanQuery = DB::table('usulan_reviewer')
            ->join('usulans', 'usulan_reviewer.usulan_id', '=', 'usulans.id')
            ->join('users', 'usulans.id_user', '=', 'users.id')
            ->where('usulan_reviewer.reviewer_id', $reviewerId)
            ->select(
                'usulans.id as usulan_id', // ID Usulan
                'usulan_reviewer.id as review_id', // ID dari tabel pivot usulan_reviewer
                'usulans.judul',
                'usulans.skema',
                'users.name as pengusul_name',
                'usulan_reviewer.status as review_status',
                'usulan_reviewer.created_at', // Tanggal penugasan review
                'usulan_reviewer.deadline as review_deadline'
            );

        // --- 3. Logika Filter dan Pencarian ---

        // Pencarian (Search: Judul atau Pengusul)
        if ($request->has('search') && $request->search != '') {
            $searchTerm = '%' . $request->search . '%';
            $usulanQuery->where(function ($query) use ($searchTerm) {
                $query->where('usulans.judul', 'like', $searchTerm)
                      ->orWhere('users.name', 'like', $searchTerm);
            });
        }
        
        // Filter Status
        if ($request->has('status') && $request->status != '') {
            $usulanQuery->where('usulan_reviewer.status', $request->status);
        }

        // Filter Skema
        if ($request->has('skema') && $request->skema != '') {
            $usulanQuery->where('usulans.skema', $request->skema);
        }
        
        // Urutkan dan Paginasi
        $usulan = $usulanQuery
            ->orderBy('usulan_reviewer.created_at', 'desc')
            ->paginate(10); 

        // Mapping untuk format tanggal
        $usulan->getCollection()->transform(function ($item) {
            $item->created_at_formatted = Carbon::parse($item->created_at)->format('d M Y');
            return $item;
        });
        
        // Ambil daftar skema unik untuk dropdown filter (opsional)
        $skema_list = DB::table('usulans')->distinct()->pluck('skema')->filter();


        return view('reviewer.dashboard', compact(
            'total_usulan',
            'belum_dinilai',
            'sudah_dinilai',
            'menunggu_revisi',
            'usulan',
            'skema_list'
        ));
    }
}