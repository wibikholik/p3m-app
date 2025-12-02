<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usulan_kelengkapan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usulan_id');
            $table->unsignedBigInteger('kelengkapan_id');
            $table->enum('status', ['lengkap','tidak'])->nullable();
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('checked_by')->nullable(); // admin user id
            $table->timestamps();

            $table->foreign('usulan_id')->references('id')->on('usulans')->onDelete('cascade');
            $table->foreign('kelengkapan_id')->references('id')->on('master_kelengkapan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usulan_kelengkapan');
    }
};
