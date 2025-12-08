<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usulan; // Pastikan Model Usulan Anda sudah diimport dengan benar

class DosenController extends Controller
{
    /**
     * Menampilkan dashboard dosen dengan ringkasan metrik penelitian dan pengabdian.
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. Ambil ID dosen yang sedang login
        $dosenId = Auth::id();
        
        // 2. Ambil semua usulan (Penelitian & Pengabdian) milik dosen ini
        // Jika Anda memiliki banyak data, pertimbangkan untuk menggunakan where('dosen_id', $dosenId)->get() sekali saja, 
        // kemudian filter menggunakan koleksi (seperti di bawah).
        $proposals = Usulan::where('id_user', $dosenId)->get();

        // 3. Siapkan Data Ringkasan (Summary Data) untuk kotak metrik dan chart
        // Catatan: Asumsi Status Akhir yang Berhasil adalah 'Diterima' atau 'Sedang Berjalan'
        $summaryData = [
            'total_usulan'      => $proposals->count(),
            
            // Metrik Aktif: Usulan yang statusnya 'Diterima' atau 'Sedang Berjalan'
            'penelitian_aktif'  => $proposals->where('tipe_usulan', 'Penelitian')
                                            ->whereIn('status', ['Diterima', 'Sedang Berjalan'])->count(),
            
            'pengabdian_aktif'  => $proposals->where('tipe_usulan', 'Pengabdian')
                                            ->whereIn('status', ['Diterima', 'Sedang Berjalan'])->count(),
            
            // Metrik Review: Usulan yang statusnya masih 'Diajukan' atau 'Menunggu Review'
            'menunggu_review'   => $proposals->whereIn('status', ['Diajukan', 'Menunggu Review'])->count(),
            
            // Data Status Akhir untuk Chart: Status 'Diterima' digunakan sebagai status keberhasilan.
            'usulan_disetujui'  => $proposals->where('status', 'Diterima')->count(),
            'usulan_ditolak'    => $proposals->where('status', 'Ditolak')->count(),
        ];

        // 4. Ambil 3 usulan terbaru (Last Proposals) untuk Tracking Timeline
        // Ini memastikan data diurutkan dari yang paling baru
        $lastProposals = $proposals->sortByDesc('created_at')->take(3);

        // 5. Kirim semua data ke view 'dosen.dashboard'
        return view('dosen.dashboard', [
            'summaryData' => $summaryData,
            'lastProposals' => $lastProposals,
        ]);
    }
}