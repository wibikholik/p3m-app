<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanKemajuan;
use App\Models\Usulan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LaporanKemajuanDosenController extends Controller
{
    /**
     * Menampilkan daftar semua Laporan Kemajuan yang dimiliki oleh dosen yang sedang login.
     */
    public function index()
    {
        $dosenId = Auth::id();

        $laporans = LaporanKemajuan::with('usulan')
            ->whereHas('usulan', function ($query) use ($dosenId) {
                $query->where('id_user', $dosenId);
            })
            ->orderByDesc('created_at')
            ->get();

        return view('dosen.laporanKemajuan.index', compact('laporans'));
    }

    /**
     * Menampilkan detail laporan kemajuan.
     */
    public function show($id)
    {
        $dosenId = Auth::id();

        $laporan = LaporanKemajuan::with('usulan')
            ->whereHas('usulan', function ($query) use ($dosenId) {
                $query->where('id_user', $dosenId);
            })
            ->findOrFail($id);

        return view('dosen.laporanKemajuan.detail', compact('laporan'));
    }
    
    /**
     * Menampilkan form untuk membuat Laporan Kemajuan baru.
     */
    public function create($id_usulan)
    {
        $usulan = Usulan::findOrFail($id_usulan);
        
        if ($usulan->id_user != Auth::id()) {
             abort(403, 'Anda tidak memiliki akses ke usulan ini.');
        }

        return view('dosen.laporanKemajuan.create', compact('usulan'));
    }

    /**
     * Menyimpan Laporan Kemajuan baru.
     */
    public function store(Request $request, $id_usulan)
    {
        $usulan = Usulan::findOrFail($id_usulan);
        
        if ($usulan->id_user != Auth::id()) {
             abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'ringkasan_kemajuan' => 'required|string|max:1000',
            'kendala' => 'nullable|string|max:1000',
            'persentase' => 'required|integer|min:1|max:100',
            'file_laporan' => 'required|file|mimes:pdf|max:5120', // Maks 5MB
        ]);

        // Proses upload file
        $path = $request->file('file_laporan')->store('laporan_kemajuan', 'public');

        LaporanKemajuan::create([
            'id_usulan' => $id_usulan,
            'ringkasan_kemajuan' => $request->ringkasan_kemajuan,
            'kendala' => $request->kendala,
            'persentase' => $request->persentase,
            'file_laporan' => $path,
            'status' => 'Terkirim',
        ]);
        
        return redirect()->route('dosen.laporan-kemajuan.index')
                         ->with('success', 'Laporan kemajuan berhasil diunggah dan dikirim untuk review.');
    }

    // ========================================================
    // FUNGSI BARU: EDIT, UPDATE, DELETE (Resource Methods)
    // ========================================================

    /**
     * Menampilkan form untuk mengedit Laporan Kemajuan yang ada.
     */
    public function edit($id)
    {
        $laporan = LaporanKemajuan::with('usulan')->findOrFail($id);

        // Pengecekan Kepemilikan dan Status
        if ($laporan->usulan->id_user != Auth::id() || $laporan->status !== 'Terkirim') {
            // Hanya izinkan edit jika milik sendiri dan statusnya masih 'Terkirim' (belum direview)
            abort(403, 'Anda tidak diizinkan mengedit laporan ini.');
        }

        return view('dosen.laporan-kemajuan.edit', compact('laporan'));
    }

    /**
     * Memperbarui Laporan Kemajuan yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $laporan = LaporanKemajuan::with('usulan')->findOrFail($id);

        // Pengecekan Kepemilikan dan Status
        if ($laporan->usulan->id_user != Auth::id() || $laporan->status !== 'Terkirim') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'ringkasan_kemajuan' => 'required|string|max:1000',
            'kendala' => 'nullable|string|max:1000',
            'persentase' => 'required|integer|min:1|max:100',
            'file_laporan' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $data = $request->only(['ringkasan_kemajuan', 'kendala', 'persentase']);

        // Proses ganti file jika ada file baru diunggah
        if ($request->hasFile('file_laporan')) {
            // Hapus file lama jika ada
            if ($laporan->file_laporan) {
                Storage::disk('public')->delete($laporan->file_laporan);
            }
            $data['file_laporan'] = $request->file('file_laporan')->store('laporan_kemajuan', 'public');
        }
        
        $laporan->update($data);

        return redirect()->route('dosen.laporan-kemajuan.index')
                         ->with('success', 'Laporan kemajuan berhasil diperbarui.');
    }

    /**
     * Menghapus Laporan Kemajuan.
     */
    public function destroy($id)
    {
        $laporan = LaporanKemajuan::with('usulan')->findOrFail($id);

        // Pengecekan Kepemilikan
        if ($laporan->usulan->id_user != Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        // Hapus file dari storage
        if ($laporan->file_laporan) {
            Storage::disk('public')->delete($laporan->file_laporan);
        }

        $laporan->delete();

        return redirect()->route('dosen.laporan-kemajuan.index')
                         ->with('success', 'Laporan kemajuan berhasil dihapus.');
    }
}