<?php
// File: ..._create_jabatan_akademiks_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jabatan_akademiks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jabatan')->unique(); // 'Asisten Ahli', 'Lektor', dll
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jabatan_akademiks');
    }
    
};