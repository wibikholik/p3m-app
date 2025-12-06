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
        Schema::create('laporan_kemajuan', function (Blueprint $table) {
    $table->id();
    $table->foreignId('id_usulan')->constrained('usulans')->onDelete('cascade');

    $table->text('ringkasan_kemajuan')->nullable();
    $table->text('kendala')->nullable();
    $table->integer('persentase')->default(0);
    $table->string('file_laporan')->nullable();

    $table->string('status')->default('Belum dikirim'); 
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
