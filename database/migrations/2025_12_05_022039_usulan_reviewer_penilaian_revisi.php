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
        Schema::create('usulan_reviewer_penilaian_revisi', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('usulan_id');
    $table->unsignedBigInteger('reviewer_id');
    $table->unsignedBigInteger('kriteria_id'); // MasterPenilaian
    $table->integer('nilai');
    $table->text('catatan')->nullable();
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
