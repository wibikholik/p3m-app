<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::with('kategori')
            ->where('status', 'Aktif')
            ->latest()
            ->paginate(10);

        return view('pengumuman.index', compact('pengumuman'));
    }

    public function show($id)
    {
        $pengumuman = Pengumuman::with('kategori')->findOrFail($id);
        return view('pengumuman.show', compact('pengumuman'));
    }
}
