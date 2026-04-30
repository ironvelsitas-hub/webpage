<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Buat user admin
        User::updateOrCreate(
            ['email' => 'admin@kopiancol.com'],
            [
                'name' => 'Admin Kopi Ancol',
                'email' => 'admin@kopiancol.com',
                'password' => Hash::make('admin123'),
                'phone' => '081234567890',
                'address' => 'Jl. Ancol, Jakarta Utara',
                'role' => 'admin',
            ]
        );
        
        // Buat user customer contoh
        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer Test',
                'email' => 'customer@example.com',
                'password' => Hash::make('customer123'),
                'phone' => '081234567891',
                'address' => 'Jl. Contoh No. 123, Jakarta',
                'role' => 'customer',
            ]
        );
        
        $this->command->info('Admin and customer users created successfully!');
    }
}