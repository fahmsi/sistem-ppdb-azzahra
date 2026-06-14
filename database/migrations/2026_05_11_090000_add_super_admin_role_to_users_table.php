<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add 'super_admin' to the role enum on users table.
     */
    public function up(): void
    {
        // Only run ALTER TABLE on MySQL; SQLite doesn't support MODIFY COLUMN with ENUM
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('super_admin', 'admin', 'parent') NOT NULL DEFAULT 'parent'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('role', 'super_admin')->update(['role' => 'admin']);
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin', 'parent') NOT NULL DEFAULT 'parent'");
        }
    }
};
