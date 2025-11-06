<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengumuman;
use App\Models\Usulan;
use App\Models\Rab;
use App\Models\Anggota;

class UsulanController extends Controller
{
    // Form ajukan usulan
    public function create($id_pengumuman)
    {
        $pengumuman = Pengumuman::findOrFail($id_pengumuman);

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

        return view('dosen.usulan.create', compact('pengumuman'));
    }

    // Simpan usulan beserta RAB & Anggota
    public function store(Request $request)
    {
        $request->validate([
            'id_pengumuman' => 'required|exists:pengumuman,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'file_proposal' => 'nullable|file|mimes:pdf',
            'rabs.*.nama_item' => 'required|string',
            'rabs.*.jumlah' => 'required|numeric',
            'rabs.*.harga_satuan' => 'required|numeric',
            'anggotas.*.nama' => 'required|string',
        ]);

        // Upload proposal
        $filePath = null;
        if ($request->hasFile('file_proposal')) {
            $filePath = $request->file('file_proposal')->store('proposal_usulan');
        }

        // Simpan usulan
        $usulan = Usulan::create([
            'id_dosen' => auth()->user()->id,
            'id_pengumuman' => $request->id_pengumuman,
            'judul' => $request->judul,
            'skema'=>$request->skema,
            'deskripsi' => $request->deskripsi,
            'tahun_pelaksanaan' => $request->tahun_pelaksanaan,
            'file_lampiran' => $filePath,
            'status' => 'Menunggu Persetujuan',
        ]);

        // Simpan RAB
        if ($request->rabs) {
            foreach ($request->rabs as $rab) {
                Rab::create([
                    'id_usulan' => $usulan->id,
                    'nama_item' => $rab['nama_item'],
                    'jumlah' => $rab['jumlah'],
                    'harga_satuan' => $rab['harga_satuan'],
                ]);
            }
        }

        // Simpan anggota
        if ($request->anggotas) {
            foreach ($request->anggotas as $anggota) {
                Anggota::create([
                    'id_usulan' => $usulan->id,
                    'nama' => $anggota['nama'],
                    'peran' => $anggota['peran'] ?? null,
                ]);
            }
        }

        return redirect()->route('dosen.pengumuman.show', $request->id_pengumuman)
                         ->with('success', 'Usulan berhasil diajukan.');
    }
}
