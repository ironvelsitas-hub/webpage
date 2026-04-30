<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@kopiancol.com'],
            [
                'name' => 'Admin Kopi Ancol',
                'email' => 'admin@kopiancol.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567890',
                'address' => 'Jl. Ancol, Jakarta Utara',
                'role' => 'admin',
            ]
        );
        
        // Customer user contoh
        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer Test',
                'email' => 'customer@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567891',
                'address' => 'Jl. Contoh No. 123, Jakarta',
                'role' => 'customer',
            ]
        );
    }
}