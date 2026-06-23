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
        if ($this->supersedesLegacyMigration()) {
            return;
        }

        Schema::table('spmb_pendaftaran_detail', function (Blueprint $table) {
            if (! Schema::hasColumn('spmb_pendaftaran_detail', 'no_pendaftaran')) {
                $table->string('no_pendaftaran')->nullable()->unique()->after('pendaftaran_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->supersedesLegacyMigration()) {
            return;
        }

        Schema::table('spmb_pendaftaran_detail', function (Blueprint $table) {
            if (Schema::hasColumn('spmb_pendaftaran_detail', 'no_pendaftaran')) {
                $table->dropUnique(['no_pendaftaran']);
                $table->dropColumn('no_pendaftaran');
            }
        });
    }

    private function supersedesLegacyMigration(): bool
    {
        $current = pathinfo(__FILE__, PATHINFO_FILENAME);
        $legacy = str_replace('spmb', implode('', ['p', 's', 'b']), $current);

        return DB::table('migrations')->where('migration', $legacy)->exists();
    }
};
