<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Updates the 'role' column on users table from enum('admin','siswa')
     * to enum('admin','parent') and converts existing 'siswa' values to 'parent'.
     */
    public function up(): void
    {
        // Step 1: Temporarily expand the enum to include both old and new values
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin', 'siswa', 'parent') NOT NULL DEFAULT 'siswa'");

        // Step 2: Convert existing 'siswa' rows to 'parent'
        DB::table('users')->where('role', 'siswa')->update(['role' => 'parent']);

        // Step 3: Now safely narrow the enum to the final values
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin', 'parent') NOT NULL DEFAULT 'parent'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin', 'siswa', 'parent') NOT NULL DEFAULT 'parent'");
        DB::table('users')->where('role', 'parent')->update(['role' => 'siswa']);
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin', 'siswa') NOT NULL DEFAULT 'siswa'");
    }
};
