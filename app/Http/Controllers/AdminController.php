<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Usulan;

class AdminController extends Controller
{
    public function index()
    {
        // Hitung berdasarkan role Spatie
        $totalReviewer = DB::table('role_user')
        ->where('role_id', '3')// reviewer
        ->count();

    $totalDosen = DB::table('role_user')
        ->where('role_id', '2')//dosen
        ->count();

    $pengumuman = Pengumuman::count();
    $totalUsulan = Usulan::count(); // nanti aktifkan kalau sudah ada tabel

        return view('admin.dashboard', compact(
            'totalReviewer',
            'totalDosen',
            'totalUsulan',
            'pengumuman'
        ));
    }
}
