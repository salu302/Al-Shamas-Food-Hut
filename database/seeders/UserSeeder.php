<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'phone' => '03000000001',
            'role' => 'super_admin',
        ]);

        User::create([
            'name' => 'Restaurant Owner',
            'email' => 'owner@example.com',
            'password' => Hash::make('password'),
            'phone' => '03000000002',
            'role' => 'owner',
        ]);

        User::create([
            'name' => 'Customer User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'phone' => '03000000003',
            'role' => 'customer',
        ]);
    }
}
