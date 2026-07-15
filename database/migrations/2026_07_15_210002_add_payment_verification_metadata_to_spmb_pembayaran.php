<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spmb_pembayaran', function (Blueprint $table) {
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete()->after('catatan_admin');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
        });
    }

    public function down(): void
    {
        Schema::table('spmb_pembayaran', function (Blueprint $table) {
            $table->dropConstrainedForeignId('verified_by');
            $table->dropColumn('verified_at');
        });
    }
};
