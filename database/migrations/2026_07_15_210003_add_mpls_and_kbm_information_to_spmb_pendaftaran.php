<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spmb_pendaftaran', function (Blueprint $table) {
            $table->time('jam_mpls_mulai')->nullable()->after('tanggal_mpls');
            $table->time('jam_mpls_selesai')->nullable()->after('jam_mpls_mulai');
            $table->string('lokasi_mpls')->nullable()->after('jam_mpls_selesai');
            $table->text('informasi_mpls')->nullable()->after('lokasi_mpls');
            $table->date('tanggal_mulai_kbm')->nullable()->after('informasi_mpls');
            $table->time('jam_masuk_kbm')->nullable()->after('tanggal_mulai_kbm');
            $table->text('informasi_kbm')->nullable()->after('jam_masuk_kbm');
        });
    }

    public function down(): void
    {
        Schema::table('spmb_pendaftaran', function (Blueprint $table) {
            $table->dropColumn(['jam_mpls_mulai', 'jam_mpls_selesai', 'lokasi_mpls', 'informasi_mpls', 'tanggal_mulai_kbm', 'jam_masuk_kbm', 'informasi_kbm']);
        });
    }
};
