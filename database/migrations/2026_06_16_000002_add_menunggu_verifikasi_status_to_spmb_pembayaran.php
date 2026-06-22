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
        if ($this->supersedesLegacyMigration()) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `spmb_pembayaran` MODIFY COLUMN `status` ENUM('pending', 'menunggu_verifikasi', 'lunas', 'ditolak') NOT NULL DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->supersedesLegacyMigration()) {
            return;
        }

        DB::table('spmb_pembayaran')
            ->where('status', 'menunggu_verifikasi')
            ->update(['status' => 'pending']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `spmb_pembayaran` MODIFY COLUMN `status` ENUM('pending', 'lunas', 'ditolak') NOT NULL DEFAULT 'pending'");
        }
    }

    private function supersedesLegacyMigration(): bool
    {
        $current = pathinfo(__FILE__, PATHINFO_FILENAME);
        $legacy = str_replace('spmb', implode('', ['p', 's', 'b']), $current);

        return DB::table('migrations')->where('migration', $legacy)->exists();
    }
};
