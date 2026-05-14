<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Seed a default admin account.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@azzahra.sch.id'],
            [
                'name' => 'Administrator',
                'password' => 'Admin123!', // hashed via model cast
                'role' => 'admin',
            ]
        );
    }
}
