<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('usulan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dosen')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_pengumuman')->constrained('pengumuman')->onDelete('cascade');
            $table->string('judul');
            $table->string('skema');
            $table->text('deskripsi');
            $table->string('tahun_pelaksanaan', 4);
            $table->string('file_lampiran')->nullable();
            $table->string('status')->default('Menunggu Persetujuan');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('usulan');
    }
};

