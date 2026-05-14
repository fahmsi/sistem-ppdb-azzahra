<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Expands the 'status' enum on psb_pendaftaran_detail to include
     * 'menunggu_verifikasi' (pending document verification) as an additional
     * stage between initial submission and acceptance/rejection.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `psb_pendaftaran_detail` MODIFY COLUMN `status` ENUM('pending', 'menunggu_verifikasi', 'diterima', 'ditolak') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert any 'menunggu_verifikasi' rows back to 'pending' before shrinking the enum
        DB::table('psb_pendaftaran_detail')
            ->where('status', 'menunggu_verifikasi')
            ->update(['status' => 'pending']);

        DB::statement("ALTER TABLE `psb_pendaftaran_detail` MODIFY COLUMN `status` ENUM('pending', 'diterima', 'ditolak') NOT NULL DEFAULT 'pending'");
    }
};
