<?php
// File: ..._add_dosen_columns_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ini untuk NIDN
            $table->string('nidn')->unique()->nullable()->after('email');
            
            // Ini untuk Jabatan Akademik
            $table->unsignedBigInteger('jabatan_akademik_id')->nullable()->after('nidn');
            
            // Membuat foreign key
            $table->foreign('jabatan_akademik_id')
                  ->references('id')
                  ->on('jabatan_akademiks')
                  ->onDelete('set null'); // Jika jabatan dihapus, user-nya jadi null
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['jabatan_akademik_id']);
            $table->dropColumn('jabatan_akademik_id');
            $table->dropColumn('nidn');
        });
    }
};