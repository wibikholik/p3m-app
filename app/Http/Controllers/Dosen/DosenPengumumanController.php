<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengumuman;
use App\Models\KategoriPengumuman;

class DosenPengumumanController extends Controller
{
    public function index(Request $request)
    {
       $kategori = KategoriPengumuman::all();

        $query = Pengumuman::with('kategori')->latest();

        if ($request->kategori) {
            $query->where('id', $request->kategori);
        }

        $pengumuman = $query->paginate(6);

        return view('dosen.pengumuman.index', compact('pengumuman', 'kategori'));
    }
    public function show($id)
    {
        $pengumuman = Pengumuman::with('kategori')->findOrFail($id);
        return view('dosen.pengumuman.show', compact('pengumuman'));
    }
}
