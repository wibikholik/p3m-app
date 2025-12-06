<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRevisiFieldsToUsulansTable extends Migration
{
    public function up()
    {
        Schema::table('usulans', function (Blueprint $table) {
            $table->string('status_revisi')->nullable()->after('status'); // 'dikembalikan','menunggu_verifikasi','disetujui'
            $table->text('catatan_revisi_admin')->nullable()->after('status_revisi');
            $table->string('file_revisi')->nullable()->after('file_usulan');
            $table->timestamp('tanggal_revisi')->nullable()->after('file_revisi');
        });
    }

    public function down()
    {
        Schema::table('usulans', function (Blueprint $table) {
            $table->dropColumn(['status_revisi','catatan_revisi_admin','file_revisi','tanggal_revisi']);
        });
    }
}
