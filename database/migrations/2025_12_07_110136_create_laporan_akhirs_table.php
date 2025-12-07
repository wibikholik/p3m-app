<?php
// database/migrations/YYYY_MM_DD_HHMMSS_create_laporan_akhirs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_akhirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usulan_id')->constrained('usulans')->onDelete('cascade');
            
            // Konten Laporan Akhir
            $table->text('ringkasan_hasil')->nullable(); // Ringkasan hasil
            $table->string('file_laporan_akhir')->nullable(); // Path file PDF
            $table->string('publikasi_target')->nullable(); // Target publikasi/luaran
            
            // Status dan Penilaian Reviewer (struktur sama dengan Laporan Kemajuan)
            $table->string('status')->default('Draft'); // Draft, Terkirim, Disetujui, Ditolak, Perbaikan
            $table->decimal('nilai_reviewer', 5, 2)->nullable();
            $table->text('catatan_reviewer')->nullable();
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->onDelete('set null');

            // Catatan Admin untuk finalisasi
            $table->text('catatan_admin')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_akhirs');
    }
};