<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spmb_pendaftaran_detail', function (Blueprint $table) {
            $table->string('final_status')->default('dalam_proses')->after('keputusan_diputuskan_at');
            $table->text('final_alasan')->nullable()->after('final_status');
            $table->text('final_catatan')->nullable()->after('final_alasan');
            $table->foreignId('final_ditetapkan_oleh')->nullable()->constrained('users')->nullOnDelete()->after('final_catatan');
            $table->timestamp('final_ditetapkan_at')->nullable()->after('final_ditetapkan_oleh');
        });
    }

    public function down(): void
    {
        Schema::table('spmb_pendaftaran_detail', function (Blueprint $table) {
            $table->dropConstrainedForeignId('final_ditetapkan_oleh');
            $table->dropColumn(['final_status', 'final_alasan', 'final_catatan', 'final_ditetapkan_at']);
        });
    }
};
