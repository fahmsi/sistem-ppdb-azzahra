<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Expands the 'status' enum on spmb_pendaftaran_detail to include
     * 'menunggu_verifikasi' (pending document verification) as an additional
     * stage between initial submission and acceptance/rejection.
     */
    public function up(): void
    {
        if ($this->supersedesLegacyMigration()) {
            return;
        }

        // Only run ALTER TABLE on MySQL; SQLite doesn't support MODIFY COLUMN with ENUM
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `spmb_pendaftaran_detail` MODIFY COLUMN `status` ENUM('pending', 'menunggu_verifikasi', 'diterima', 'ditolak') NOT NULL DEFAULT 'pending'");
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

        // Revert any 'menunggu_verifikasi' rows back to 'pending' before shrinking the enum
        DB::table('spmb_pendaftaran_detail')
            ->where('status', 'menunggu_verifikasi')
            ->update(['status' => 'pending']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `spmb_pendaftaran_detail` MODIFY COLUMN `status` ENUM('pending', 'diterima', 'ditolak') NOT NULL DEFAULT 'pending'");
        }
    }

    private function supersedesLegacyMigration(): bool
    {
        $current = pathinfo(__FILE__, PATHINFO_FILENAME);
        $legacy = str_replace('spmb', implode('', ['p', 's', 'b']), $current);

        return DB::table('migrations')->where('migration', $legacy)->exists();
    }
};
