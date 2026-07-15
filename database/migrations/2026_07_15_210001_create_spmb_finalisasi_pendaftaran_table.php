<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spmb_finalisasi_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_detail_id')->constrained('spmb_pendaftaran_detail')->cascadeOnDelete();
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->string('source');
            $table->text('alasan')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('finalized_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('finalized_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spmb_finalisasi_pendaftaran');
    }
};
