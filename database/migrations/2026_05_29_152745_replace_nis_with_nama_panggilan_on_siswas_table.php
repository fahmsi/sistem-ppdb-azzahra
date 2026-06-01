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
        Schema::table('psb_siswa', function (Blueprint $table) {
            $table->dropColumn(['nis', 'nisn']);
            $table->string('nama_panggilan', 50)->after('nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('psb_siswa', function (Blueprint $table) {
            $table->string('nisn')->nullable();
            $table->string('nis')->nullable();
            $table->dropColumn('nama_panggilan');
        });
    }
};
