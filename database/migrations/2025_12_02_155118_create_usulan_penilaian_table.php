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
    Schema::create('usulan_penilaian', function (Blueprint $table) {
        $table->id();

        $table->foreignId('usulan_id')->constrained('usulans')->onDelete('cascade');
        $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('komponen_id')->constrained('master_penilaian')->onDelete('cascade');

        $table->integer('nilai')->default(0);
        $table->text('catatan')->nullable();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usulan_penilaian');
    }
};
