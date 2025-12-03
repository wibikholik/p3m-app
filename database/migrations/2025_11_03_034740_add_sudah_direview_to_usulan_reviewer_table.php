<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usulan_reviewer', function (Blueprint $table) {
            $table->boolean('sudah_direview')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('usulan_reviewer', function (Blueprint $table) {
            $table->dropColumn('sudah_direview');
        });
    }
};
