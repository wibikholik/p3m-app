<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PengumumanController extends Controller
{
    /**
     * Menampilkan daftar semua pengumuman.
     */
    public function index()
    {
        // Update otomatis status pengumuman berdasarkan tanggal
        $today = Carbon::today();
        $pengumumanList = Pengumuman::all();

        foreach ($pengumumanList as $pengumuman) {
            if ($pengumuman->tanggal_mulai && $pengumuman->tanggal_akhir) {
                if ($today->between(Carbon::parse($pengumuman->tanggal_mulai), Carbon::parse($pengumuman->tanggal_akhir))) {
                    $pengumuman->status = 'Aktif';
                } else {
                    $pengumuman->status = 'Tidak Aktif';
                }
                $pengumuman->save();
            }
        }

        $pengumuman = Pengumuman::latest()->paginate(10);
        return view('admin.pengumuman.index', compact('pengumuman'));
    }

    /**
     * Form tambah pengumuman.
     */
    public function create()
    {
        $kategori = \App\Models\KategoriPengumuman::all();
        return view('admin.pengumuman.create', compact('kategori'));
    }

    /**
     * Simpan pengumuman baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'kategori_id' => 'nullable|exists:kategori_pengumuman,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $data = $request->all();

        // Upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('pengumuman-images', 'public');
        }

        // Tentukan status aktif/tidak aktif berdasarkan periode
        if (!empty($data['tanggal_mulai']) && !empty($data['tanggal_akhir'])) {
            $today = Carbon::today();
            $data['status'] = $today->between(Carbon::parse($data['tanggal_mulai']), Carbon::parse($data['tanggal_akhir']))
                ? 'Aktif'
                : 'Tidak Aktif';
        } else {
            $data['status'] = 'Tidak Aktif';
        }

        Pengumuman::create($data);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil ditambahkan!');
    }

    /**
     * Form edit pengumuman.
     */
    public function edit($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $kategori = \App\Models\KategoriPengumuman::all();
        return view('admin.pengumuman.edit', compact('pengumuman','kategori'));
    }

    /**
     * Proses update pengumuman.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'kategori_id' => 'nullable|exists:kategori_pengumuman,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $pengumuman = Pengumuman::findOrFail($id);
        $data = $request->all();

        // Ganti gambar jika ada upload baru
        if ($request->hasFile('gambar')) {
            if ($pengumuman->gambar && Storage::disk('public')->exists($pengumuman->gambar)) {
                Storage::disk('public')->delete($pengumuman->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('pengumuman-images', 'public');
        } else {
            $data['gambar'] = $pengumuman->gambar;
        }

        // Tentukan status aktif/tidak aktif berdasarkan periode
        if (!empty($data['tanggal_mulai']) && !empty($data['tanggal_akhir'])) {
            $today = Carbon::today();
            $data['status'] = $today->between(Carbon::parse($data['tanggal_mulai']), Carbon::parse($data['tanggal_akhir']))
                ? 'Aktif'
                : 'Tidak Aktif';
        } else {
            $data['status'] = 'Tidak Aktif';
        }

        $pengumuman->update($data);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui!');
    }

    /**
     * Hapus pengumuman.
     */
    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        // Hapus gambar jika ada
        if ($pengumuman->gambar && Storage::disk('public')->exists($pengumuman->gambar)) {
            Storage::disk('public')->delete($pengumuman->gambar);
        }

        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil dihapus!');
    }
}
