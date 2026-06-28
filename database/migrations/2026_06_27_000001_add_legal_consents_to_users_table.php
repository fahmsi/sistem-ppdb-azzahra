<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('terms_accepted_at')->nullable();
            $table->timestamp('privacy_accepted_at')->nullable();
            $table->string('terms_version')->nullable();
            $table->string('privacy_version')->nullable();
            $table->string('terms_accepted_ip', 45)->nullable();
            $table->string('privacy_accepted_ip', 45)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'terms_accepted_at',
                'privacy_accepted_at',
                'terms_version',
                'privacy_version',
                'terms_accepted_ip',
                'privacy_accepted_ip',
            ]);
        });
    }
};
