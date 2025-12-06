<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRevisiColumnsToUsulanReviewer extends Migration
{
    public function up()
    {
        Schema::table('usulan_reviewer', function (Blueprint $table) {
            $table->tinyInteger('sudah_direview_revisi')->default(0);
            $table->decimal('nilai_revisi', 5, 2)->nullable();
            $table->text('catatan_revisi')->nullable();
            $table->string('status_revisi')->nullable(); // menunggu_verifikasi, disetujui, dikembalikan
        });
    }

    public function down()
    {
        Schema::table('usulan_reviewer', function (Blueprint $table) {
            $table->dropColumn(['sudah_direview_revisi', 'nilai_revisi', 'catatan_revisi', 'status_revisi']);
        });
    }
};
