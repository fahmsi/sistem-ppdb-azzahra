<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spmb_observasi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pendaftaran_detail_id')
                ->constrained('spmb_pendaftaran_detail')
                ->cascadeOnDelete();

            $table->unsignedSmallInteger('attempt_number')->default(1);

            $table->dateTime('scheduled_at');

            $table->string('status')->default('dijadwalkan');

            // Reschedule traceability
            $table->foreignId('rescheduled_from_id')
                ->nullable()
                ->constrained('spmb_observasi')
                ->nullOnDelete();

            $table->text('reschedule_reason')->nullable();

            // Attendance
            $table->dateTime('attended_at')->nullable();
            $table->dateTime('completed_at')->nullable();

            // Physical measurements
            $table->decimal('tinggi_badan_cm', 5, 2)->nullable();
            $table->decimal('berat_badan_kg', 5, 2)->nullable();

            // Qualitative observation notes (admin-only)
            $table->text('catatan_wawancara_orang_tua')->nullable();
            $table->text('catatan_aktivitas_anak')->nullable();
            $table->text('catatan_kesiapan_anak')->nullable();
            $table->boolean('membutuhkan_dukungan_khusus')->nullable();
            $table->text('catatan_kebutuhan_dukungan_khusus')->nullable();
            $table->text('catatan_sekolah')->nullable();

            // User references
            $table->foreignId('scheduled_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('observed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('rescheduled_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            // Unique constraint: one attempt number per registration
            $table->unique(['pendaftaran_detail_id', 'attempt_number']);

            // Indexes for query performance
            $table->index('status');
            $table->index('scheduled_at');
            $table->index('pendaftaran_detail_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spmb_observasi');
    }
};
