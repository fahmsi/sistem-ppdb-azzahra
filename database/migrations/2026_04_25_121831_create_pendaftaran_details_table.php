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
        Schema::create('psb_pendaftaran_detail', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->foreignId('siswa_id')
                ->constrained('psb_siswa')
                ->onDelete('cascade');

            $table->foreignId('pendaftaran_id')
                ->constrained('psb_pendaftaran')
                ->onDelete('cascade');

            // Status pendaftaran
            $table->enum('status', ['pending', 'diterima', 'ditolak'])
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
        Schema::dropIfExists('pendaftaran_details');
    }
};
