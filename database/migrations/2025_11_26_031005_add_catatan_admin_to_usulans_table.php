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
    Schema::table('usulans', function (Blueprint $table) {
        $table->json('checklist')->nullable()->after('catatan_admin');
    });
}

public function down()
{
    Schema::table('usulans', function (Blueprint $table) {
        $table->dropColumn('checklist');
    });
}

};
