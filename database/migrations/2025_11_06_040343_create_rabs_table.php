<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rabs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usulan')->constrained('usulan')->onDelete('cascade');
            $table->string('nama_item');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('rabs');
    }
};
