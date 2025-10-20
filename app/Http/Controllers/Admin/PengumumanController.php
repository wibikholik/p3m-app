<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\KategoriPengumuman; // Import model Kategori
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    /**
     * Menampilkan daftar pengumuman dengan paginasi dan relasi.
     * Eager loading 'kategori' untuk menghindari N+1 problem.
     */
    public function index()
    {
        $pengumuman = Pengumuman::with('kategori')->latest()->paginate(10);
        return view('admin.pengumuman.index', compact('pengumuman'));
    }

    /**
     * Menampilkan form untuk membuat pengumuman baru.
     * Mengirimkan data kategori untuk ditampilkan di dropdown.
     */
    public function create()
    {
        $kategori = KategoriPengumuman::all(); // Ambil semua kategori
        return view('admin.pengumuman.create', compact('kategori'));
    }

    /**
     * Menyimpan data pengumuman baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'kategori_id' => 'nullable|exists:kategori_pengumuman,id', // Validasi relasi
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $data = $request->all();

        // Handle upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('pengumuman-images', 'public');
        }

        Pengumuman::create($data);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail satu pengumuman.
     * Menggunakan Route Model Binding.
     */
    public function show(Pengumuman $pengumuman)
    {
        return view('admin.pengumuman.show', compact('pengumuman'));
    }

    /**
     * Menampilkan form untuk mengedit pengumuman.
     * Menggunakan Route Model Binding dan mengirimkan data kategori.
     */
    public function edit(Pengumuman $pengumuman)
    {
        $kategori = KategoriPengumuman::all(); // Ambil semua kategori
        return view('admin.pengumuman.edit', compact('pengumuman', 'kategori'));
    }

    /**
     * Memperbarui data pengumuman di database.
     * Menggunakan Route Model Binding.
     */
    public function update(Request $request, Pengumuman $pengumuman)
    {
        // Validasi input
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'kategori_id' => 'nullable|exists:kategori_pengumuman,id', // Validasi relasi
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $data = $request->all();

        // Handle upload gambar baru jika ada
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($pengumuman->gambar && Storage::disk('public')->exists($pengumuman->gambar)) {
                Storage::disk('public')->delete($pengumuman->gambar);
            }
            // Simpan gambar baru
            $data['gambar'] = $request->file('gambar')->store('pengumuman-images', 'public');
        }

        $pengumuman->update($data);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Menghapus pengumuman dari database.
     * Menggunakan Route Model Binding.
     */
    public function destroy(Pengumuman $pengumuman)
    {
        // Hapus gambar terkait jika ada
        if ($pengumuman->gambar && Storage::disk('public')->exists($pengumuman->gambar)) {
            Storage::disk('public')->delete($pengumuman->gambar);
        }

        $pengumuman->delete();

        // Menggunakan pesan 'success' karena proses berhasil
        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil dihapus!');
    }
}
