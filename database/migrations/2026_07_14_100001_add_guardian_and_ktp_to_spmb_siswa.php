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
        Schema::table('spmb_siswa', function (Blueprint $table) {
            $table->string('tinggal_bersama')->default('orang_tua');
            $table->string('nama_wali')->nullable();
            $table->string('nik_wali', 16)->nullable();
            $table->string('hubungan_wali')->nullable();
            $table->string('no_telpon_wali')->nullable();
            $table->string('foto_ktp_ayah')->nullable();
            $table->string('foto_ktp_ibu')->nullable();
            $table->string('foto_ktp_wali')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spmb_siswa', function (Blueprint $table) {
            $table->dropColumn([
                'tinggal_bersama',
                'nama_wali',
                'nik_wali',
                'hubungan_wali',
                'no_telpon_wali',
                'foto_ktp_ayah',
                'foto_ktp_ibu',
                'foto_ktp_wali',
            ]);
        });
    }
};
