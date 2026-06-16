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
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `psb_pembayaran` MODIFY COLUMN `status` ENUM('pending', 'menunggu_verifikasi', 'lunas', 'ditolak') NOT NULL DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('psb_pembayaran')
            ->where('status', 'menunggu_verifikasi')
            ->update(['status' => 'pending']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `psb_pembayaran` MODIFY COLUMN `status` ENUM('pending', 'lunas', 'ditolak') NOT NULL DEFAULT 'pending'");
        }
    }
};
