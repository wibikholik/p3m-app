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
    Schema::create('anggotas', function (Blueprint $table) {
        $table->id();
        // Relasi ke Usulan
        $table->unsignedBigInteger('id_usulan');
        $table->foreign('id_usulan')->references('id')->on('usulans')->onDelete('cascade');

        $table->string('nama');
        $table->string('nidn')->nullable();
        $table->string('jabatan')->nullable(); // Jabatan Akademik saat usulan dibuat
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggotas');
    }
};
