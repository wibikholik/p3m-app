<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usulan;
use App\Models\UsulanAnggota;
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
        $this->checkAccess();
        $user = Auth::user();

        $usulans = $user->hasRole('dosen')
            ? Usulan::where('id_user', $user->id)->get()
            : Usulan::all();

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

        // Tentukan skema sesuai jabatan fungsional
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
        // Semua dosen bisa ajukan PKM
        $skemaList['PKM'] = 'Penerapan Iptek Masyarakat (PKM)';

        // Daftar dosen untuk anggota
        $dosenList = User::whereHas('roles', function($q){
            $q->where('name', 'dosen');
        })->get();

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

        // Validasi input
        $request->validate([
            'judul' => 'required|string|max:255',
            'skema' => 'required|string|max:100',
            'abstrak' => 'required|string',
            'file_usulan' => 'required|file|mimes:pdf|max:10240',
            'id_pengumuman' => 'required|exists:pengumuman,id',
        ]);

        // Cek total usulan user (max 2)
        $totalUsulan = Usulan::where('id_user', $user->id)->count();
        if ($totalUsulan >= 2) {
            return back()->withErrors('Anda sudah mencapai batas 2 usulan.');
        }

        // Cek jumlah hibah sebagai ketua dengan skema yang sama (max 2)
        $usulanKetuaSamaSkema = Usulan::where('id_user', $user->id)
            ->where('skema', $request->skema)
            ->count();

        if ($usulanKetuaSamaSkema >= 2) {
            return back()->withErrors('Anda sudah pernah mendapatkan hibah dengan skema yang sama 2 kali sebagai ketua.');
        }

        // Upload file PDF
        $filePath = $request->file('file_usulan')->store('usulan_files', 'public');

        // Simpan usulan baru
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

        // Simpan anggota tim jika ada
        if ($request->has('anggota')) {
            foreach ($request->anggota as $anggota) {
                if (!empty($anggota['nama'])) {
                    $usulan->anggotas()->create([
                        'nama' => $anggota['nama'],
                        'nidn' => $anggota['nidn'] ?? null,
                        'jabatan' => $anggota['jabatan'] ?? null,
                        'peran' => $anggota['peran'] ?? 'Anggota Peneliti',
                    ]);
                }
            }
        }

        return redirect()->route('usulan.index')->with('success', 'Usulan berhasil dibuat. Anda otomatis menjadi ketua.');
    }

    // ===========================
    // Relasi anggota
    // ===========================
    

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

        return view('dosen.usulan.show', compact('usulan'));
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

        return view('dosen.usulan.edit', compact('usulan'));
    }

    // ===========================
    // Update usulan
    // ===========================
    public function update(Request $request, $id)
    {
        $this->checkAccess();
        $user = Auth::user();
        $usulan = Usulan::findOrFail($id);

        if ($user->hasRole('dosen') && $usulan->id_user !== $user->id) {
            return back()->withErrors('Anda tidak memiliki hak untuk mengedit usulan ini.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'skema' => 'required|string|max:100',
            'abstrak' => 'required|string',
            'file_usulan' => 'nullable|file|mimes:pdf|max:10240',
            'id_pengumuman' => 'required|exists:pengumuman,id',
        ]);

        if ($request->hasFile('file_usulan')) {
            if ($usulan->file_usulan && Storage::disk('public')->exists($usulan->file_usulan)) {
                Storage::disk('public')->delete($usulan->file_usulan);
            }
            $usulan->file_usulan = $request->file('file_usulan')->store('usulan_files', 'public');
        }

        $usulan->update([
            'judul' => $request->judul,
            'skema' => $request->skema,
            'abstrak' => $request->abstrak,
            'id_pengumuman' => $request->id_pengumuman,
        ]);

        return redirect()->route('usulan.index')->with('success', 'Usulan berhasil diperbarui.');
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

        $usulan->delete();

        return redirect()->route('usulan.index')->with('success', 'Usulan berhasil dihapus.');
    }
}
