<?php

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
        // Pastikan tabel 'usulans' ada sebelum menambahkan kolom
        if (Schema::hasTable('usulans')) {
            Schema::table('usulans', function (Blueprint $table) {
                
                // 1. Tambahkan 'nilai_final' (Jika belum ada)
                if (!Schema::hasColumn('usulans', 'nilai_final')) {
                    // Kolom untuk menyimpan rata-rata nilai akhir dari reviewer
                    $table->decimal('nilai_final', 5, 2)->nullable()->after('status');
                }
                
                // 2. Tambahkan 'status_revisi' (Jika belum ada)
                // ERROR Anda terjadi karena baris ini dieksekusi tanpa pengecekan.
                if (!Schema::hasColumn('usulans', 'status_revisi')) {
                    // Kolom untuk melacak status revisi (e.g., 'dikembalikan', 'menunggu_verifikasi', 'disetujui')
                    $table->string('status_revisi')->nullable()->after('nilai_final');
                }

                // 3. Tambahkan 'catatan_revisi_admin' (Jika belum ada)
                if (!Schema::hasColumn('usulans', 'catatan_revisi_admin')) {
                    // Kolom untuk catatan/instruksi revisi dari admin/koordinator
                    $table->text('catatan_revisi_admin')->nullable()->after('status_revisi');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('usulans')) {
            Schema::table('usulans', function (Blueprint $table) {
                // Tambahkan pengecekan sebelum drop column
                if (Schema::hasColumn('usulans', 'nilai_final')) {
                    $table->dropColumn('nilai_final');
                }
                if (Schema::hasColumn('usulans', 'status_revisi')) {
                    $table->dropColumn('status_revisi');
                }
                if (Schema::hasColumn('usulans', 'catatan_revisi_admin')) {
                    $table->dropColumn('catatan_revisi_admin');
                }
            });
        }
    }
};