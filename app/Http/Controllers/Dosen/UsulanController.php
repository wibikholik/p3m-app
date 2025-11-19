<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengumuman;
use App\Models\Usulan;
use App\Models\Rab;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UsulanController extends Controller
{
    /**
     * Menampilkan daftar usulan milik dosen yang login.
     */
    public function index()
    {
        $user = auth()->user();

        $usulans = Usulan::where('id_user', $user->id)
            ->with(['pengumuman', 'anggota'])
            ->latest()
            ->get();

        return view('dosen.usulan.index', compact('usulans'));
    }

    /**
     * Halaman create usulan.
     */
    public function create($id_pengumuman)
    {
        // Load pengumuman beserta kategorinya
        $pengumuman = Pengumuman::with('kategori')->findOrFail($id_pengumuman);
        
        $skemaList = []; 
        
        $user = Auth::user();
        // 1. Ambil Jabatan (Pastikan kolom di DB users adalah 'jabatan_akademik')
        $jabatan = strtolower(trim($user->jabatan_akademik)); 
        
        // 2. Ambil Jenis dari Relasi Kategori
        // Pastikan '$pengumuman->kategori->nama' sesuai dengan kolom di tabel kategori (bisa 'nama' atau 'nama_kategori')
        $namaKategori = $pengumuman->kategori ? $pengumuman->kategori->nama_kategori : ''; 
        $jenisPengumuman = strtolower(trim($namaKategori));

        // 3. LOGIKA FILTER SKEMA
        if ($jenisPengumuman === 'penelitian') {
            
            // Syarat Jabatan (Huruf kecil semua)
            $syaratPdp = ['asisten ahli', 'tenaga pengajar', 'biasa']; 
            $syaratP2v = ['lektor', 'lektor kepala'];

            // Cek Syarat PDP
            if (in_array($jabatan, $syaratPdp)) {
                $skemaList['pdp'] = 'Penelitian Dosen Pemula (PDP)';
            }
            
            // Cek Syarat P2V
            if (in_array($jabatan, $syaratP2v)) {
                $skemaList['p2v'] = 'Penelitian P2V'; 
            }
            
        } elseif ($jenisPengumuman === 'pengabdian') {
            
            $skemaList = [
                'pkm'  => 'PKM',
                'ppdm' => 'PPDM', 
            ];
        }

        // 4. AMBIL DATA DOSEN UNTUK SELECT2 (Pencarian Anggota)
        // Menggunakan whereHas karena relasi many-to-many (role_user)
        $dosenList = User::whereHas('roles', function($q) {
            $q->where('name', 'dosen'); // Pastikan nama role di DB adalah 'dosen'
        })
        ->where('id', '!=', Auth::id()) // Kecualikan diri sendiri
        ->get();

        // Jangan lupa masukkan 'dosenList' ke compact
        return view('dosen.usulan.create', compact('pengumuman', 'skemaList', 'dosenList'));
    }

    /**
     * Proses store usulan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pengumuman' => 'required',
            'judul' => 'required|string|max:255',
            'skema' => 'required',
            'abstrak' => 'required',
            'file_usulan' => 'required|mimes:pdf|max:2048',
        ]);

        $user = auth()->user();

        // Upload berkas
        $pathFile = $request->file('file_usulan')->store('usulan', 'public');

        // Simpan usulan
        $usulan = Usulan::create([
            'id_user' => $user->id,
            'email_ketua' => $user->email,
            'id_pengumuman' => $request->id_pengumuman,
            'judul' => $request->judul,
            'skema' => $request->skema,
            'abstrak' => $request->abstrak,
            'file_usulan' => $pathFile,
            'status' => 'diajukan',
        ]);

        // Simpan anggota (jika ada)
        if ($request->anggota) {
            foreach ($request->anggota as $a) {
                // Cek minimal nama terisi
                if (!empty($a['nama'])) {
                    Anggota::create([
                        'id_usulan' => $usulan->id,
                        'nama' => $a['nama'],
                        'nidn' => $a['nidn'] ?? null, // Pakai null coalescing operator biar aman
                        'jabatan' => $a['jabatan'] ?? null,
                        'peran'=>$a['peran']??null,
                    ]);
                }
            }
        }

        return redirect()->route('dosen.usulan.index')
            ->with('success', 'Usulan berhasil diajukan.');
    }

    /**
     * Detail usulan.
     */
    public function show($id)
    {
        $usulan = Usulan::with(['pengumuman', 'anggota'])->findOrFail($id);
        return view('dosen.usulan.show', compact('usulan'));
    }

    /**
     * Edit usulan.
     */
    public function edit($id)
    {
        $usulan = Usulan::with('anggota')->findOrFail($id);
        // Load relasi kategori juga di sini agar logic filter jalan
        $pengumuman = Pengumuman::with('kategori')->find($usulan->id_pengumuman);
        
        $user = auth()->user();
        $jabatan = strtolower(trim($user->jabatan_akademik)); // Fix: konsisten pakai jabatan_akademik
        
        $namaKategori = $pengumuman->kategori ? $pengumuman->kategori->nama_kategori : '';
        $jenisPengumuman = strtolower(trim($namaKategori));

        $skemaList = [];

        if ($jenisPengumuman === 'penelitian') {
            $syaratPdp = ['asisten ahli', 'tenaga pengajar', 'biasa'];
            $syaratP2v = ['lektor', 'lektor kepala'];

            if (in_array($jabatan, $syaratPdp)) $skemaList['pdp'] = 'PDP';
            if (in_array($jabatan, $syaratP2v)) $skemaList['p2v'] = 'P2V';
        } elseif ($jenisPengumuman === 'pengabdian') {
            $skemaList = ['pkm' => 'PKM', 'ppdm' => 'PPDM'];
        }

        // Ambil dosenList juga untuk form edit (jika mau ganti anggota)
        $dosenList = User::whereHas('roles', function($q) {
            $q->where('name', 'dosen');
        })->where('id', '!=', Auth::id())->get();

        return view('dosen.usulan.edit', compact('usulan', 'skemaList', 'pengumuman', 'dosenList'));
    }

    /**
     * Update usulan.
     */
    public function update(Request $request, $id)
    {
        $usulan = Usulan::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'skema' => 'required',
            'abstrak' => 'required',
            'file_usulan' => 'nullable|mimes:pdf|max:2048',
        ]);

        // upload file baru jika ada
        if ($request->hasFile('file_usulan')) {
            // hapus file lama
            if ($usulan->file_usulan && Storage::disk('public')->exists($usulan->file_usulan)) {
                Storage::disk('public')->delete($usulan->file_usulan);
            }

            $usulan->file_usulan = $request->file('file_usulan')->store('usulan', 'public');
        }

        // update data utama
        $usulan->update([
            'judul' => $request->judul,
            'skema' => $request->skema,
            'abstrak' => $request->abstrak,
        ]);

        // Update anggota lama & tambah baru
        if ($request->anggota) {
            // hapus dulu
            Anggota::where('id_usulan', $usulan->id)->delete();

            foreach ($request->anggota as $a) {
                 if (!empty($a['nama'])) {
                    Anggota::create([
                        'id_usulan' => $usulan->id,
                        'nama' => $a['nama'],
                        'nidn' => $a['nidn'] ?? null,
                        'jabatan' => $a['jabatan'] ?? null,
                        'peran' => $a['peran'] ?? null,
                    ]);
                 }
            }
        }

        return redirect()->route('dosen.usulan.index')
            ->with('success', 'Usulan berhasil diperbarui.');
    }

    /**
     * Delete usulan.
     */
    public function destroy($id)
    {
        $usulan = Usulan::findOrFail($id);

        // hapus file
        if ($usulan->file_usulan && Storage::disk('public')->exists($usulan->file_usulan)) {
            Storage::disk('public')->delete($usulan->file_usulan);
        }

        // hapus anggota
        Anggota::where('id_usulan', $usulan->id)->delete();

        $usulan->delete();

        return redirect()->route('dosen.usulan.index')
            ->with('success', 'Usulan berhasil dihapus.');
    }
}