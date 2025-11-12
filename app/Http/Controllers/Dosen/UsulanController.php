<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengumuman;
use App\Models\Usulan;
use App\Models\Rab;
use App\Models\Anggota;
use App\Models\User; // Pastikan model User di-import
use Illuminate\Support\Facades\Storage;

class UsulanController extends Controller
{
    /**
     * Menampilkan daftar usulan milik dosen yang login.
     */
    public function index()
    {
        $usulans = Usulan::where('id_dosen', auth()->id())->with('pengumuman')->get();
        return view('dosen.usulan.index', compact('usulans'));
    }

    /**
     * Menampilkan detail satu usulan.
     */
    public function show($id)
    {
        $usulan = Usulan::with(['pengumuman', 'rabs', 'anggotas.user'])->findOrFail($id);
        return view('dosen.usulan.show', compact('usulan'));
    }

    // =========================
    // FORM AJUKAN USULAN
    // =========================
    /**
     * Menampilkan form untuk membuat usulan baru.
     * Sudah dimodifikasi untuk mengecek jabatan akademik.
     */
    public function create($id_pengumuman)
    {
        $pengumuman = Pengumuman::findOrFail($id_pengumuman);

        // [LOGIKA LAMA ANDA - TETAP DIPAKAI]
        // Cek apakah pengumuman aktif
        $now = now();
        $aktif = $pengumuman->status === 'Aktif'
            && $pengumuman->tanggal_mulai
            && $pengumuman->tanggal_akhir
            && $now->between($pengumuman->tanggal_mulai, $pengumuman->tanggal_akhir);

        if (!$aktif) {
            return redirect()->route('dosen.pengumuman.show', $id_pengumuman)
                             ->with('error', 'Pengumuman sudah tidak aktif.');
        }

        // =============================================
        // [LOGIKA BARU - DARI KODE KATING]
        // =============================================

        // 1. Ambil data dosen yang login + relasi jabatan akademiknya
        $dosen = User::with('jabatanAkademik')->find(auth()->id());
        
        $skemaList = []; // Buat array kosong untuk daftar skema

        // 2. Cek jabatannya dan tentukan skema yang diizinkan
        if ($dosen && $dosen->jabatanAkademik) {
            
            $nama_jabatan = $dosen->jabatanAkademik->nama_jabatan;

            if ($nama_jabatan == 'Asisten Ahli') {
                $skemaList = ['PDP' => 'PDP (Penelitian Dosen Pemula)'];
            } else if ($nama_jabatan == 'Lektor') {
                $skemaList = ['P2V' => 'P2V (Penelitian Produk Vokasi)'];
            } else {
                // Untuk Lektor Kepala, Guru Besar, dll.
                $skemaList = [
                    'PDP' => 'PDP (Penelitian Dosen Pemula)', 
                    'P2V' => 'P2V (Penelitian Produk Vokasi)'
                ];
            }

        } else {
            // Jika dosen tidak punya jabatan (misal data belum di-set)
            // Beri semua skema sebagai default
            $skemaList = [
                'PDP' => 'PDP (Penelitian Dosen Pemula)', 
                'P2V' => 'P2V (Penelitian Produk Vokasi)'
            ];
        }
        
        // 3. Kirim data pengumuman DAN skemaList ke view
        return view('dosen.usulan.create', compact('pengumuman', 'skemaList'));
    }

    // =========================
    // SIMPAN USULAN BARU
    // =========================
    /**
     * Menyimpan usulan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pengumuman' => 'required|exists:pengumuman,id',
            'judul' => 'required|string|max:255',
            'skema' => 'required|string|max:255', // Ini akan divalidasi dari skemaList
            'deskripsi' => 'required|string',
            'tahun_pelaksanaan' => 'required|digits:4',
            'file_proposal' => 'nullable|file|mimes:pdf|max:5120',

            // Validasi RAB
            'rabs.*.nama_item' => 'required|string',
            'rabs.*.jumlah' => 'required|numeric|min:1',
            'rabs.*.harga_satuan' => 'required|numeric|min:0',

            // Validasi Anggota
            'anggotas.*.id_user' => 'required|exists:users,id',
        ]);

        // Upload proposal jika ada
        $filePath = $request->hasFile('file_proposal')
            ? $request->file('file_proposal')->store('proposal_usulan', 'public')
            : null;

        // Simpan data usulan
        $usulan = Usulan::create([
            'id_dosen' => auth()->id(),
            'id_pengumuman' => $request->id_pengumuman,
            'judul' => $request->judul,
            'skema' => $request->skema,
            'deskripsi' => $request->deskripsi,
            'tahun_pelaksanaan' => $request->tahun_pelaksanaan,
            'file_lampiran' => $filePath,
            'status' => 'Draft', // Direkomendasikan ganti ke 'Draft'
            // 'status' => 'Menunggu Persetujuan', // Ini kode lama Anda
        ]);

        // Simpan RAB
        foreach ($request->rabs ?? [] as $rab) {
            Rab::create([
                'id_usulan' => $usulan->id,
                'nama_item' => $rab['nama_item'],
                'jumlah' => $rab['jumlah'],
                'harga_satuan' => $rab['harga_satuan'],
            ]);
        }

        // Simpan Anggota
        foreach ($request->anggotas ?? [] as $anggota) {
            Anggota::create([
                'id_usulan' => $usulan->id,
                'id_user' => $anggota['id_user'],
                'peran' => $anggota['peran'] ?? null,
            ]);
        }

        return redirect()->route('dosen.usulan.show', $usulan->id)
                         ->with('success', 'Usulan berhasil diajukan.');
    }

    // =========================
    // SEARCH DOSEN (UNTUK SELECT2)
    // =========================
    /**
     * Mencari dosen untuk form tambah anggota.
     */
    public function search(Request $request)
    {
        $keyword = $request->get('q');

        $users = User::query()
            ->where('name', 'like', "%{$keyword}%")
            ->where('role', 'dosen') // hanya dosen
            ->where('id', '!=', auth()->id()) // Jangan tampilkan diri sendiri
            ->limit(10)
            ->get(['id', 'name', 'email', 'nidn']); // Kirim juga NIDN jika perlu

        return response()->json($users);
    }

    // DISARANKAN: Tambahkan fungsi submitUsulan jika Anda pakai status 'Draft'
    
    
     public function edit($id)
    {
        // 1. Ambil usulan, pastikan milik user yg login DAN statusnya 'Draft'
        $usulan = Usulan::where('id', $id)
                        ->where('id_dosen', auth()->id())
                        ->where('status', 'Draft')
                        ->with(['rabs', 'anggotas.user']) // Ambil relasinya
                        ->firstOrFail(); // Error 404 jika tidak ditemukan/tidak diizinkan

        // 2. Ambil data pengumuman terkait
        $pengumuman = $usulan->pengumuman;

        // 3. Salin logika SkemaList dari fungsi create()
        $dosen = User::with('jabatanAkademik')->find(auth()->id());
        $skemaList = [];
        if ($dosen && $dosen->jabatanAkademik) {
            $nama_jabatan = $dosen->jabatanAkademik->nama_jabatan;
            if ($nama_jabatan == 'Asisten Ahli') {
                $skemaList = ['PDP' => 'PDP (Penelitian Dosen Pemula)'];
            } else if ($nama_jabatan == 'Lektor') {
                $skemaList = ['P2V' => 'P2V (Penelitian Produk Vokasi)'];
            } else {
                $skemaList = ['PDP' => 'PDP (Penelitian Dosen Pemula)', 'P2V' => 'P2V (Penelitian Produk Vokasi)'];
            }
        } else {
            $skemaList = ['PDP' => 'PDP (Penelitian Dosen Pemula)', 'P2V' => 'P2V (Penelitian Produk Vokasi)'];
        }
        
        // 4. Kirim semua data ke view 'edit'
        return view('dosen.usulan.edit', compact('usulan', 'pengumuman', 'skemaList'));
    }

    // =========================
    // UPDATE USULAN (BARU)
    // =========================
    public function update(Request $request, $id)
    {
        // 1. Ambil usulan, pastikan milik user yg login DAN statusnya 'Draft'
        $usulan = Usulan::where('id', $id)
                        ->where('id_dosen', auth()->id())
                        ->where('status', 'Draft')
                        ->firstOrFail();

        // 2. Validasi (Sama seperti store, tapi file_proposal boleh 'nullable')
        $request->validate([
            'id_pengumuman' => 'required|exists:pengumuman,id',
            'judul' => 'required|string|max:255',
            'skema' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tahun_pelaksanaan' => 'required|digits:4',
            'file_proposal' => 'nullable|file|mimes:pdf|max:5120', // Boleh null
            'rabs.*.nama_item' => 'required|string',
            'rabs.*.jumlah' => 'required|numeric|min:1',
            'rabs.*.harga_satuan' => 'required|numeric|min:0',
            'anggotas.*.id_user' => 'required|exists:users,id',
        ]);

        // 3. Siapkan data usulan dasar
        $usulanData = [
            'id_pengumuman' => $request->id_pengumuman,
            'judul' => $request->judul,
            'skema' => $request->skema,
            'deskripsi' => $request->deskripsi,
            'tahun_pelaksanaan' => $request->tahun_pelaksanaan,
        ];

        // 4. Handle Upload File (Jika ada file baru)
        if ($request->hasFile('file_proposal')) {
            // Hapus file lama jika ada
            if ($usulan->file_lampiran) {
                Storage::disk('public')->delete($usulan->file_lampiran);
            }
            // Simpan file baru
            $filePath = $request->file('file_proposal')->store('proposal_usulan', 'public');
            $usulanData['file_lampiran'] = $filePath;
        }

        // 5. Update data usulan
        $usulan->update($usulanData);

        // 6. Update RAB (Hapus lama, buat baru - cara termudah)
        $usulan->rabs()->delete();
        foreach ($request->rabs ?? [] as $rab) {
            Rab::create([
                'id_usulan' => $usulan->id,
                'nama_item' => $rab['nama_item'],
                'jumlah' => $rab['jumlah'],
                'harga_satuan' => $rab['harga_satuan'],
            ]);
        }

        // 7. Update Anggota (Hapus lama, buat baru)
        $usulan->anggotas()->delete();
        foreach ($request->anggotas ?? [] as $anggota) {
            Anggota::create([
                'id_usulan' => $usulan->id,
                'id_user' => $anggota['id_user'],
                'peran' => $anggota['peran'] ?? null,
            ]);
        }

        return redirect()->route('dosen.usulan.show', $usulan->id)
                         ->with('success', 'Usulan berhasil diperbarui.');
    }

    // =========================
    // SUBMIT USULAN (BARU)
    // =========================
    public function submitUsulan($id)
    {
        $usulan = Usulan::where('id', $id)
                        ->where('id_dosen', auth()->id())
                        ->where('status', 'Draft')
                        ->firstOrFail();
        
        // TODO: Anda bisa tambahkan validasi akhir di sini
        // Cth: Cek apakah file_lampiran sudah ada?
        if (empty($usulan->file_lampiran)) {
             return redirect()->route('dosen.usulan.show', $usulan->id)
                         ->with('error', 'Gagal submit. File proposal wajib diunggah.');
        }
        // Cth: Cek apakah RAB sudah ada?
        if ($usulan->rabs->count() == 0) {
             return redirect()->route('dosen.usulan.show', $usulan->id)
                         ->with('error', 'Gagal submit. RAB wajib diisi.');
        }
        
        $usulan->status = 'Menunggu Persetujuan';
        $usulan->save();

        return redirect()->route('dosen.usulan.show', $usulan->id)
                         ->with('success', 'Usulan berhasil di-submit ke admin.');
    }
    
}