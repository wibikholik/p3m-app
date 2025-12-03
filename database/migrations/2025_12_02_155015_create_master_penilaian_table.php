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
    Schema::create('master_penilaian', function (Blueprint $table) {
        $table->id();
        $table->string('nama');                 // Nama komponen (Latar Belakang, Metodologi, dst)
        $table->text('deskripsi')->nullable(); // Penjelasan komponen
        $table->integer('bobot')->default(0);  // Bobot nilai (20, 30, dll)
        $table->integer('order')->default(0);  // Urutan
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_penilaian');
    }
};
