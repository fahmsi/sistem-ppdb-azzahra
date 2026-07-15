<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spmb_pendaftaran', function (Blueprint $table) {
            $table->date('tanggal_mpls')->nullable()->after('tanggal_selesai');
        });
    }

    public function down(): void
    {
        Schema::table('spmb_pendaftaran', function (Blueprint $table) {
            $table->dropColumn('tanggal_mpls');
        });
    }
};
