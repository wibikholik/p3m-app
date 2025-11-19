<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('usulans', function (Blueprint $table) {
        $table->id();
        // Relasi ke User (Dosen Pengusul)
        $table->unsignedBigInteger('id_user'); 
        $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');

        // Relasi ke Pengumuman
        $table->unsignedBigInteger('id_pengumuman');
        $table->foreign('id_pengumuman')->references('id')->on('pengumuman')->onDelete('cascade');

        $table->string('email_ketua')->nullable();
        $table->string('judul');
        $table->string('skema'); // PDP, P2V, PKM, dll
        $table->text('abstrak');
        $table->string('file_usulan'); // Path PDF
        $table->string('status')->default('diajukan'); // diajukan, direview, diterima, ditolak
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usulans');
    }
};
