<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('spmb_pendaftaran_detail', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->foreignId('siswa_id')
                ->constrained('spmb_siswa')
                ->onDelete('cascade');

            $table->foreignId('pendaftaran_id')
                ->constrained('spmb_pendaftaran')
                ->onDelete('cascade');

            // Status pendaftaran
            $table->enum('status', ['pending', 'menunggu_verifikasi', 'diterima', 'ditolak', 'perlu_revisi'])
                ->default('pending');

            // Notifikasi / catatan
            $table->text('notifikasi')->nullable();

            $table->unique(['siswa_id', 'pendaftaran_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spmb_pendaftaran_detail');
    }
};
