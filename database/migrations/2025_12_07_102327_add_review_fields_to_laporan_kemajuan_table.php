<?php

// database/migrations/YYYY_MM_DD_HHMMSS_add_review_fields_to_laporan_kemajuans_table.php

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
        Schema::table('laporan_kemajuan', function (Blueprint $table) {
            // Kolom untuk nilai
            $table->decimal('nilai_reviewer', 5, 2)->nullable()->after('persentase'); 
            
            // Kolom untuk catatan/revisi
            $table->text('catatan_reviewer')->nullable()->after('nilai_reviewer'); 
            
            // Kolom untuk ID reviewer yang menilai (Foreign Key)
            $table->unsignedBigInteger('reviewer_id')->nullable()->after('catatan_reviewer');
            
            // Tambahkan foreign key constraint (opsional, tapi disarankan)
            $table->foreign('reviewer_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_kemajuan', function (Blueprint $table) {
            // Urutan penghapusan harus dibalik, foreign key dihapus duluan
            $table->dropForeign(['reviewer_id']);
            
            $table->dropColumn('reviewer_id');
            $table->dropColumn('catatan_reviewer');
            $table->dropColumn('nilai_reviewer');
        });
    }
};