<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Alter status column to accept 'keputusan_selesai'
        if (Schema::hasTable('spmb_pendaftaran_detail')) {
            $driver = DB::getDriverName();
            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE `spmb_pendaftaran_detail` MODIFY COLUMN `status` ENUM('pending', 'menunggu_verifikasi', 'diterima', 'ditolak', 'perlu_revisi', 'administrasi_lengkap', 'menunggu_keputusan', 'keputusan_selesai') NOT NULL DEFAULT 'pending'");
            }
        }

        // 2. Add snapshot columns to spmb_pendaftaran_detail
        Schema::table('spmb_pendaftaran_detail', function (Blueprint $table) {
            $table->string('keputusan_status')->nullable();
            $table->text('keputusan_catatan')->nullable();
            $table->text('keputusan_alasan')->nullable();
            $table->foreignId('keputusan_diputuskan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('keputusan_diputuskan_at')->nullable();
        });

        // 3. Create table spmb_keputusan_pendaftaran
        Schema::create('spmb_keputusan_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_detail_id')->constrained('spmb_pendaftaran_detail')->cascadeOnDelete();
            $table->string('status');
            $table->text('catatan')->nullable();
            $table->text('alasan')->nullable();
            $table->foreignId('decided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('decided_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spmb_keputusan_pendaftaran');

        Schema::table('spmb_pendaftaran_detail', function (Blueprint $table) {
            $table->dropForeign(['keputusan_diputuskan_oleh']);
            $table->dropColumn([
                'keputusan_status',
                'keputusan_catatan',
                'keputusan_alasan',
                'keputusan_diputuskan_oleh',
                'keputusan_diputuskan_at',
            ]);
        });

        if (Schema::hasTable('spmb_pendaftaran_detail')) {
            $driver = DB::getDriverName();
            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE `spmb_pendaftaran_detail` MODIFY COLUMN `status` ENUM('pending', 'menunggu_verifikasi', 'diterima', 'ditolak', 'perlu_revisi', 'administrasi_lengkap', 'menunggu_keputusan') NOT NULL DEFAULT 'pending'");
            }
        }
    }
};
