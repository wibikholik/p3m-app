<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Storage;


class PengumumanController extends Controller
{
    public function index(){
        $pengumuman = Pengumuman::all();
        return view('admin.pengumuman.index', compact('pengumuman'));
    }

    public function create(){
        return view('admin.pengumuman.create');
    }
    // proses menyimpan data ke database
    public function store(Request $request){
        // validasi input
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // opsional
        ]);

        $data = $request->all();
        // handle upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('admin/pengumuman', 'public');
        }

        Pengumuman::create($data);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil ditambahkan!');
    }

    // menampilkan Tabel Pengumuman
    public function show($id){
        $pengumuman = Pengumuman::findOrFail($id);
        return view('admin.pengumuman.show', compact('pengumuman'));
    }

    // Mengedit Pengumuman
    public function edit($id){  
        $pengumuman = Pengumuman::findOrFail($id);
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }
    // Memperbarui Pengumuman
    public function update(Request $request, $id){
        $pengumuman = Pengumuman::findOrFail($id);
        // validasi input
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'isi' => 'required',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $data = $request->all();
        // handle upload gambar jika ada
        if ($request->hasFile('gambar')) {
            if ($pengumuman->gambar && Storage::disk('public')->exists($pengumuman->gambar)) {
                Storage::disk('public')->delete($pengumuman->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('admin/pengumuman', 'public');
        }

        $pengumuman->update($data);
        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }
    // Menghapus Pengumuman
    public function destroy($id){
        $pengumuman = Pengumuman::findOrFail($id);

        // Hapus gambar jika ada
        if ($pengumuman->gambar && Storage::disk('public')->exists($pengumuman->gambar)) {
            Storage::disk('public')->delete($pengumuman->gambar);
        }

        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')->with('error', 'Gagal menghapus pengumuman!');

    }

}