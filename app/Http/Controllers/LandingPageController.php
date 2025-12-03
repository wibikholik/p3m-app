<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Models\KategoriPengumuman;

class LandingPageController extends Controller
{
    public function index()
    {
        // Ambil pengumuman aktif terbaru (tampilan landing page)
        $pengumuman = Pengumuman::with('kategori')
            ->where('status', 'Aktif')
            ->latest()
            ->take(6)
            ->get();

        // Ambil semua kategori untuk filter di landing
        $kategori = KategoriPengumuman::all();

        return view('index', compact('pengumuman', 'kategori'));
    }
}
