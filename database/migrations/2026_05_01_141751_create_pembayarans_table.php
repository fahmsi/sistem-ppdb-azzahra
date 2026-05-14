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
        Schema::create('psb_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_detail_id')->constrained('psb_pendaftaran_detail')->onDelete('cascade');
            $table->decimal('jumlah', 15, 2)->default(0);
            $table->string('bukti_bayar')->nullable();
            $table->enum('status', ['pending', 'lunas', 'ditolak'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psb_pembayaran');
    }
};
