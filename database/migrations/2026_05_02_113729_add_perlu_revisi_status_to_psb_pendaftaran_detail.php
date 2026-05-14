<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `psb_pendaftaran_detail` MODIFY COLUMN `status` ENUM('pending', 'menunggu_verifikasi', 'diterima', 'ditolak', 'perlu_revisi') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('psb_pendaftaran_detail')
            ->where('status', 'perlu_revisi')
            ->update(['status' => 'ditolak']);

        DB::statement("ALTER TABLE `psb_pendaftaran_detail` MODIFY COLUMN `status` ENUM('pending', 'menunggu_verifikasi', 'diterima', 'ditolak') NOT NULL DEFAULT 'pending'");
    }
};
