<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\JabatanAkademik; // <-- 1. TAMBAHKAN IMPORT INI

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // -----------------------------------------------------------------
        // PENTING:
        // Pastikan Anda sudah menjalankan JabatanAkademikSeeder
        // sebelum menjalankan seeder ini.
        // -----------------------------------------------------------------

        // 2. Ambil data Jabatan dari database
        $jabatanAA = JabatanAkademik::where('nama_jabatan', 'Asisten Ahli')->first();
        $jabatanLektor = JabatanAkademik::where('nama_jabatan', 'Lektor')->first();

        // =================================================================
        // Admin
        User::create([
            'name' => 'Admin P3M',
            'email' => 'admin@p3m.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'nidn' => null, // Admin tidak perlu NIDN
            'jabatan_akademik_id' => null, // Admin tidak perlu Jabatan
        ]);

        // =================================================================
        // Reviewer
        User::create([
            'name' => 'Reviewer Sinta',
            'email' => 'reviewer@p3m.com',
            'password' => Hash::make('reviewer123'),
            'role' => 'reviewer',
            'nidn' => null, // Reviewer mungkin tidak perlu NIDN
            'jabatan_akademik_id' => null,
        ]);

        // =================================================================
        // Dosen 1 (Asisten Ahli -> hanya bisa skema PDP)
        User::create([
            'name' => 'Dosen Wibi (Asisten Ahli)',
            'email' => 'wibi@p3m.com',
            'password' => Hash::make('dosen123'),
            'role' => 'dosen',
            'nidn' => '1122334455', // <-- 3. TAMBAHKAN NIDN (contoh)
            
            // 4. Gunakan ID Jabatan
            'jabatan_akademik_id' => $jabatanAA->id ?? null,
        ]);

        // =================================================================
        // Dosen 2 (Lektor -> hanya bisa skema P2V)
        User::create([
            'name' => 'Dosen Budi (Lektor)',
            'email' => 'budi@p3m.com',
            'password' => Hash::make('dosen123'),
            'role' => 'dosen',
            'nidn' => '5566778899', // <-- NIDN HARUS UNIK
            
            // Gunakan ID Jabatan Lektor
            'jabatan_akademik_id' => $jabatanLektor->id ?? null,
        ]);
    }
}