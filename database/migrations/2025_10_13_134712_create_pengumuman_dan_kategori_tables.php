<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Buat tabel kategori dulu
        Schema::create('kategori_pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->timestamps();
        });

        // Baru buat tabel pengumuman yang berelasi ke kategori
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('isi');
            $table->string('gambar')->nullable(); // <-- KOLOM GAMBAR DITAMBAHKAN DI SINI
            $table->foreignId('kategori_id')
                  ->nullable()
                  ->constrained('kategori_pengumuman')
                  ->onDelete('set null');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
        Schema::dropIfExists('kategori_pengumuman');
    }
};
