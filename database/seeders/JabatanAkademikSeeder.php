<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JabatanAkademik; // Pastikan Anda import modelnya

class JabatanAkademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar jabatan yang ingin dimasukkan
        $jabatans = [
            'Asisten Ahli',
            'Lektor',
            'Lektor Kepala',
            'Guru Besar (Profesor)',
            'Tenaga Pengajar', // Anda bisa tambahkan ini jika perlu
        ];

        // Loop dan buat data jika belum ada
        foreach ($jabatans as $jabatan) {
            JabatanAkademik::firstOrCreate([
                'nama_jabatan' => $jabatan
            ]);
        }
    }
}