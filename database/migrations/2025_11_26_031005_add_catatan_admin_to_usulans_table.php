<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usulans', function (Blueprint $table) {
            $table->text('catatan_admin')->nullable()->after('status');
            $table->json('checklist')->nullable()->after('catatan_admin');
        });
    }

    public function down(): void
{
    Schema::table('usulans', function (Blueprint $table) {
        if (Schema::hasColumn('usulans', 'catatan_admin')) {
            $table->dropColumn('catatan_admin');
        }

        if (Schema::hasColumn('usulans', 'checklist')) {
            $table->dropColumn('checklist');
        }
    });
}

};
