<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Silver Spoon Admin',
            'email' => 'admin@silverspoon.test',
            'phone' => '0700000000',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Test Customer',
            'email' => 'customer@silverspoon.test',
            'phone' => '0712345678',
            'password' => 'password',
            'role' => 'customer',
            'is_active' => true,
        ]);
    }
}