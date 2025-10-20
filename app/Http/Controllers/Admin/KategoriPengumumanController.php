<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriPengumuman;
use Illuminate\Http\Request;

class KategoriPengumumanController extends Controller
{
    /**
     * Menampilkan daftar semua kategori pengumuman.
     */
    public function index()
    {
        $kategori = KategoriPengumuman::latest()->paginate(10);
        
        // Menggunakan path view 'admin.pengumuman.kategori.index'
        return view('admin.pengumuman.kategori.index', compact('kategori'));
    }

    /**
     * Menampilkan form untuk membuat kategori baru.
     */
    public function create()
    {
        // Menggunakan path view 'admin.pengumuman.kategori.create'
        return view('admin.pengumuman.kategori.create');
    }

    /**
     * Menyimpan kategori yang baru dibuat ke dalam database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_pengumuman',
        ]);

        KategoriPengumuman::create($request->all());

        return redirect()->route('admin.kategori-pengumuman.index')
                         ->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit kategori yang sudah ada.
     */
    public function edit(KategoriPengumuman $kategoriPengumuman)
    {
        // Menggunakan path view 'admin.pengumuman.kategori.edit'
        return view('admin.pengumuman.kategori.edit', compact('kategoriPengumuman'));
    }

    /**
     * Memperbarui data kategori di dalam database.
     */
    public function update(Request $request, KategoriPengumuman $kategoriPengumuman)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_pengumuman,nama_kategori,' . $kategoriPengumuman->id,
        ]);

        $kategoriPengumuman->update($request->all());

        return redirect()->route('admin.kategori-pengumuman.index')
                         ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Menghapus kategori dari database.
     */
    public function destroy(KategoriPengumuman $kategoriPengumuman)
    {
        $kategoriPengumuman->delete();

        // PERBAIKAN: Mengganti 'admin-kategori-pengumuman.index' menjadi 'admin.kategori-pengumuman.index'
        return redirect()->route('admin.kategori-pengumuman.index')
                         ->with('success', 'Kategori berhasil dihapus.');
    }
}

