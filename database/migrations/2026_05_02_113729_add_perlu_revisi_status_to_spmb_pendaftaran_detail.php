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

        // Only run ALTER TABLE on MySQL; SQLite doesn't support MODIFY COLUMN with ENUM
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `spmb_pendaftaran_detail` MODIFY COLUMN `status` ENUM('pending', 'menunggu_verifikasi', 'diterima', 'ditolak', 'perlu_revisi') NOT NULL DEFAULT 'pending'");
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

        DB::table('spmb_pendaftaran_detail')
            ->where('status', 'perlu_revisi')
            ->update(['status' => 'ditolak']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `spmb_pendaftaran_detail` MODIFY COLUMN `status` ENUM('pending', 'menunggu_verifikasi', 'diterima', 'ditolak') NOT NULL DEFAULT 'pending'");
        }
    }

    private function supersedesLegacyMigration(): bool
    {
        $current = pathinfo(__FILE__, PATHINFO_FILENAME);
        $legacy = str_replace('spmb', implode('', ['p', 's', 'b']), $current);

        return DB::table('migrations')->where('migration', $legacy)->exists();
    }
};
