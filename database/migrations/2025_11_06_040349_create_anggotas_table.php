<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('anggotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usulan')->constrained('usulan')->onDelete('cascade');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->string('peran')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('anggotas');
    }
};
