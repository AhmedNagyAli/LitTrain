<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'language_id'=>1,
            'role' => UserRole::Admin,
        ]);

        // Writer User
        User::create([
            'name' => 'Writer User',
            'email' => 'writer@example.com',
            'password' => Hash::make('password'),
            'language_id'=>1,
            'role' => UserRole::Writer,
        ]);

        // Reader User
        User::create([
            'name' => 'Reader User',
            'email' => 'reader@example.com',
            'password' => Hash::make('password'),
            'language_id'=>1,
            'role' => UserRole::Reader,
        ]);
    }
}
