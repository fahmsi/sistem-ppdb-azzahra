<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach ($this->tableNames() as $legacy => $current) {
            if (Schema::hasTable($legacy) && ! Schema::hasTable($current)) {
                Schema::rename($legacy, $current);
            }
        }
    }

    public function down(): void
    {
        foreach (array_reverse($this->tableNames(), true) as $legacy => $current) {
            if (Schema::hasTable($current) && ! Schema::hasTable($legacy)) {
                Schema::rename($current, $legacy);
            }
        }
    }

    /** @return array<string, string> */
    private function tableNames(): array
    {
        $legacyPrefix = implode('', ['p', 's', 'b']);

        return [
            "{$legacyPrefix}_siswa" => 'spmb_siswa',
            "{$legacyPrefix}_pendaftaran" => 'spmb_pendaftaran',
            "{$legacyPrefix}_pendaftaran_detail" => 'spmb_pendaftaran_detail',
            "{$legacyPrefix}_pembayaran" => 'spmb_pembayaran',
        ];
    }
};
