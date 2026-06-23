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
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'suspended_at')) {
                $table->timestamp('suspended_at')->nullable()->after('remember_token');
            }

            if (! Schema::hasColumn('users', 'suspended_by')) {
                $table->foreignId('suspended_by')
                    ->nullable()
                    ->after('suspended_at')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('users', 'suspend_reason')) {
                $table->text('suspend_reason')->nullable()->after('suspended_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'suspended_by')) {
                $table->dropConstrainedForeignId('suspended_by');
            }

            if (Schema::hasColumn('users', 'suspend_reason')) {
                $table->dropColumn('suspend_reason');
            }

            if (Schema::hasColumn('users', 'suspended_at')) {
                $table->dropColumn('suspended_at');
            }
        });
    }
};
