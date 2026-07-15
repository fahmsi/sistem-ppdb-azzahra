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
        Schema::table('spmb_pendaftaran_detail', function (Blueprint $table) {
            $table->date('tanggal_acuan_usia')->nullable();
            $table->unsignedSmallInteger('usia_bulan_saat_acuan')->nullable();
            $table->string('kelompok_rekomendasi')->nullable()->index();
            $table->string('kelompok_final')->nullable()->index();
            $table->foreignId('kelompok_ditetapkan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('kelompok_ditetapkan_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spmb_pendaftaran_detail', function (Blueprint $table) {
            $table->dropForeign(['kelompok_ditetapkan_oleh']);
            $table->dropColumn([
                'tanggal_acuan_usia',
                'usia_bulan_saat_acuan',
                'kelompok_rekomendasi',
                'kelompok_final',
                'kelompok_ditetapkan_oleh',
                'kelompok_ditetapkan_at',
            ]);
        });
    }
};
