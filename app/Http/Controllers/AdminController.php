<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Pengumuman;
// use App\Models\Usulan;
use Illuminate\Http\Request;

class AdminController extends Controller
{
     public function index()
{
    $totalReviewer = User::where('role', 'reviewer')->count();
    $totalDosen = User::where('role', 'dosen')->count();
    $pengumuman = Pengumuman::count();
    // $totalUsulan = Usulan::count(); // nanti bisa disesuaikan kalau tabelnya belum ada

    return view('admin.dashboard', compact(
        'totalReviewer',
        'totalDosen',
        'pengumuman'
        // 'totalUsulan'
    ));
}
}
