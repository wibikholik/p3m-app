<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usulan;
use App\Models\Anggota;
use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UsulanController extends Controller
{
    // ===========================
    // Helper: cek akses role
    // ===========================
    private function checkAccess()
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['dosen', 'reviewer', 'admin'])) {
            abort(403, 'Anda tidak memiliki akses.');
        }
    }

    // ===========================
    // Tampilkan daftar usulan
    // ===========================
   public function index()
{
    $this->checkAccess(); // Pastikan user punya akses ke halaman ini
    $user = Auth::user();

    // Ambil usulan dosen jika role dosen, atau semua usulan untuk admin
    $usulans = $user->hasRole('dosen')
        ? Usulan::where('id_user', $user->id)->get()
        : Usulan::all();

    // Tidak perlu filter lagi di controller, Blade akan menampilkan tombol "Buat Laporan"
    // hanya untuk usulan yang status_lanjut = 'siap_laporan'

    return view('dosen.usulan.index', compact('usulans'));
} 
    // ===========================
    // Form tambah usulan
    // ===========================
    public function create()
    {
        $this->checkAccess();
        $user = Auth::user();

        $pengumuman = Pengumuman::latest()->first();
        $skemaList = $this->getSkemaList($user);
        $dosenList = $this->getDosenList();

        return view('dosen.usulan.create', compact('pengumuman','skemaList','dosenList'));
    }

    // ===========================
    // Simpan usulan baru
    // ===========================
    public function store(Request $request)
    {
        $this->checkAccess();
        $user = Auth::user();

        if (!$user->hasRole('dosen')) {
            return back()->withErrors('Anda tidak memiliki hak untuk membuat usulan.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'skema' => 'required|string|max:100',
            'abstrak' => 'required|string',
            'file_usulan' => 'required|file|mimes:pdf|max:10240',
            'id_pengumuman' => 'required|exists:pengumuman,id',
        ]);

        // Upload file PDF
        $filePath = $request->file('file_usulan')->store('usulan_files', 'public');

        // Simpan usulan
        $usulan = Usulan::create([
            'id_user' => $user->id,
            'email_ketua' => $user->email,
            'id_pengumuman' => $request->id_pengumuman,
            'judul' => $request->judul,
            'skema' => $request->skema,
            'abstrak' => $request->abstrak,
            'file_usulan' => $filePath,
            'status' => 'diajukan',
        ]);

        // Simpan anggota tim
        if ($request->has('anggota')) {
            foreach ($request->anggota as $anggota) {
                if (!empty($anggota['nama'])) {
                    $usulan->anggota()->create([
                        'nama' => $anggota['nama'],
                        'nidn' => $anggota['nidn'] ?? null,
                        'jabatan' => $anggota['jabatan'] ?? null,
                        'peran' => $anggota['peran'] ?? 'Anggota Peneliti',
                    ]);
                }
            }
        }

        return redirect()->route('dosen.usulan.index')
                         ->with('success', 'Usulan berhasil dibuat. Anda otomatis menjadi ketua.');
    }

    // ===========================
    // Detail usulan
    // ===========================
    public function show($id)
    {
        $this->checkAccess();
        $user = Auth::user();
        $usulan = Usulan::findOrFail($id);

        if ($user->hasRole('dosen') && $usulan->id_user !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke usulan ini.');
        }

        $pengumuman = Pengumuman::latest()->first();

        return view('dosen.usulan.show', compact('usulan','pengumuman'));
    }

    // ===========================
    // Form edit usulan
    // ===========================
    public function edit($id)
    {
        $this->checkAccess();
        $user = Auth::user();
        $usulan = Usulan::findOrFail($id);

        if ($user->hasRole('dosen') && $usulan->id_user !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit usulan ini.');
        }

        $pengumuman = Pengumuman::latest()->first();
        $skemaList = $this->getSkemaList($user);
        $dosenList = $this->getDosenList();

        return view('dosen.usulan.edit', compact('usulan', 'pengumuman', 'skemaList', 'dosenList'));
    }

    // ===========================
    // Update usulan
    // ===========================
    public function update(Request $request, $id)
{
    $usulan = Usulan::findOrFail($id);

    // ====================
    // Mode revisi
    // ====================
    if ($request->has('mode_revisi')) {
        $request->validate([
            'file_revisi' => 'required|file|mimes:pdf|max:10240',
        ]);

        $file = $request->file('file_revisi');
        $path = $file->store('usulan/revisi', 'public');

        $usulan->update([
            'file_revisi' => $path,
            'status' => 'revisi_diajukan',
            'status_revisi' => 'menunggu_verifikasi',
            'tanggal_revisi' => now(),
        ]);

        return redirect()->route('dosen.usulan.show', $usulan->id)
                         ->with('success', 'File revisi berhasil diunggah dan status diubah.');
    }

    // ====================
    // Edit normal
    // ====================
    $validated = $request->validate([
        'judul' => 'required|string|max:255',
        'skema' => 'required|string',
        'abstrak' => 'required|string',
        'file_usulan' => 'nullable|file|mimes:pdf|max:2048',
    ]);

    $usulan->update($validated);

    if ($request->hasFile('file_usulan')) {
        $path = $request->file('file_usulan')->store('usulan', 'public');
        $usulan->update(['file_usulan' => $path]);
    }

    return redirect()->route('dosen.usulan.show', $usulan->id)
                     ->with('success', 'Usulan berhasil diperbarui.');
}



    // ===========================
    // Hapus usulan
    // ===========================
    public function destroy($id)
    {
        $this->checkAccess();
        $user = Auth::user();
        $usulan = Usulan::findOrFail($id);

        if ($user->hasRole('dosen') && $usulan->id_user !== $user->id) {
            return back()->withErrors('Anda tidak memiliki hak untuk menghapus usulan ini.');
        }

        if ($usulan->file_usulan && Storage::disk('public')->exists($usulan->file_usulan)) {
            Storage::disk('public')->delete($usulan->file_usulan);
        }

        if ($usulan->file_revisi && Storage::disk('public')->exists($usulan->file_revisi)) {
            Storage::disk('public')->delete($usulan->file_revisi);
        }

        $usulan->delete();

        return redirect()->route('dosen.usulan.index')
                         ->with('success', 'Usulan berhasil dihapus.');
    }

    // ===========================
    // Helper: daftar skema sesuai jabatan
    // ===========================
    private function getSkemaList($user)
    {
        $skemaList = [];
        switch($user->jabatan_akademik) {
            case 'Biasa':
            case 'Asisten Ahli':
                $skemaList['PDP'] = 'Penelitian Dosen Pemula (PDP)';
                break;
            case 'Lektor':
            case 'Lektor Kepala':
                $skemaList['P2V'] = 'Penelitian Produk Vokasi (P2V)';
                break;
        }
        $skemaList['PKM'] = 'Penerapan Iptek Masyarakat (PKM)';
        return $skemaList;
    }

    // ===========================
    // Helper: daftar dosen
    // ===========================
    private function getDosenList()
    {
        return User::whereHas('roles', function($q){
            $q->where('name', 'dosen');
        })->get();
    }
}
