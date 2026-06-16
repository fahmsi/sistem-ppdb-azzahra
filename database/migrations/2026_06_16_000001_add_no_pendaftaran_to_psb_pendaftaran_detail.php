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
        Schema::table('psb_pendaftaran_detail', function (Blueprint $table) {
            if (! Schema::hasColumn('psb_pendaftaran_detail', 'no_pendaftaran')) {
                $table->string('no_pendaftaran')->nullable()->unique()->after('pendaftaran_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('psb_pendaftaran_detail', function (Blueprint $table) {
            if (Schema::hasColumn('psb_pendaftaran_detail', 'no_pendaftaran')) {
                $table->dropUnique(['no_pendaftaran']);
                $table->dropColumn('no_pendaftaran');
            }
        });
    }
};
