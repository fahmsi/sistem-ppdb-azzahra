<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Create the default Super Admin account.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@azzahra.sch.id'],
            [
                'name'              => 'Super Admin',
                'email'             => 'superadmin@azzahra.sch.id',
                'password'          => 'SuperAdmin123',
                'role'              => 'super_admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
