<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;   
use App\Models\MasterKelengkapan;
use App\Models\UsulanKelengkapan;
use App\Models\Usulan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UsulanKelengkapanController extends Controller
{
    // Show checklist di halaman detail usulan
    public function editChecklist($usulanId)
    {
        $usulan = Usulan::findOrFail($usulanId);

        // Ambil aturan aktif
        $kelengkapan = MasterKelengkapan::where('is_active', 1)->orderBy('order')->get();

        // Ambil status sebelumnya jika ada
        $existing = UsulanKelengkapan::where('usulan_id', $usulanId)->get()->keyBy('kelengkapan_id');

        return view('usulan.checklist', compact('usulan','kelengkapan','existing'));
    }

    // Simpan checklist (bulk)
    public function updateChecklist(Request $request, $usulanId)
    {
        $request->validate([
            'status' => 'required|array', // status[kelengkapan_id] => 'lengkap'/'tidak'
            'catatan' => 'nullable|array'
        ]);

        $usulan = Usulan::findOrFail($usulanId);

        DB::transaction(function() use ($request, $usulan) {
            $statuses = $request->input('status', []);
            $catatans = $request->input('catatan', []);
            $adminId = Auth::id() ?? null;

            foreach ($statuses as $kelId => $stat) {
                if (!in_array($stat, ['lengkap','tidak'])) continue;

                UsulanKelengkapan::updateOrCreate(
                    ['usulan_id' => $usulan->id, 'kelengkapan_id' => $kelId],
                    [
                        'status' => $stat,
                        'catatan' => $catatans[$kelId] ?? null,
                        'checked_by' => $adminId
                    ]
                );
            }

            // Setelah menyimpan semua, cek apakah semua aturan aktif bernilai 'lengkap'
            $activeKel = MasterKelengkapan::where('is_active', 1)->pluck('id')->toArray();

            $totalActive = count($activeKel);
            $totalLengkap = UsulanKelengkapan::where('usulan_id', $usulan->id)
                                ->whereIn('kelengkapan_id', $activeKel)
                                ->where('status','lengkap')
                                ->count();

            // Tentukan logika lolos / ditolak
            if ($totalActive > 0 && $totalLengkap === $totalActive) {
                // semua lengkap -> set status usulan (asumsi kolom status pada table usulan)
                $usulan->update(['status' => 'lolos_kelengkapan']); // ganti sesuai skema status kamu
            } else {
                $usulan->update(['status' => 'tidak_lolos_kelengkapan']); // ganti sesuai skema status kamu
            }
        });

        return redirect()->route('usulan.show', $usulan->id)->with('success','Checklist disimpan.');
    }
}
