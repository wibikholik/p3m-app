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
        if (!Schema::hasTable('master_penilaian')) {
            Schema::create('master_penilaian', function (Blueprint $table) {
                $table->id();
                $table->string('nama');                 // Nama komponen
                $table->text('deskripsi')->nullable(); // Deskripsi komponen
                $table->integer('bobot')->default(0);  // Bobot nilai
                $table->integer('order')->default(0);  // Urutan
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_penilaian');
    }
};
