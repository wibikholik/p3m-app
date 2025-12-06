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
    Schema::table('usulan_reviewer', function (Blueprint $table) {
    if (!Schema::hasColumn('usulan_reviewer', 'nilai')) {
        $table->decimal('nilai', 5, 2)->nullable();
    }
    if (!Schema::hasColumn('usulan_reviewer', 'catatan')) {
        $table->text('catatan')->nullable();
    }
    if (!Schema::hasColumn('usulan_reviewer', 'accepted_at')) {
        $table->timestamp('accepted_at')->nullable();
    }
    if (!Schema::hasColumn('usulan_reviewer', 'declined_at')) {
        $table->timestamp('declined_at')->nullable();
    }
});

}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usulan_reviewer', function (Blueprint $table) {
            //
        });
    }
};
