<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    public function run()
    {
        // Cek apakah owner sudah ada, jika belum buat
        User::updateOrCreate(
            ['email' => 'owner@kopiancol.com'],
            [
                'name' => 'Iron (Owner)',
                'password' => Hash::make('owner123'),
                'role' => 'owner',
                'phone' => '081246135710',
                'is_active' => true
            ]
        );
        
        // Tambahan: buat admin default jika diperlukan
        User::updateOrCreate(
            ['email' => 'admin@kopiancol.com'],
            [
                'name' => 'Admin Kopi Ancol',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'is_active' => true
            ]
        );
        
        // Tambahan: buat customer demo jika diperlukan
        User::updateOrCreate(
            ['email' => 'customer@kopiancol.com'],
            [
                'name' => 'Customer Demo',
                'password' => Hash::make('customer123'),
                'role' => 'customer',
                'phone' => '081298765432',
                'is_active' => true
            ]
        );
        
        $this->command->info('Owner, Admin, dan Customer demo berhasil dibuat!');
        $this->command->info('Email owner: owner@kopiancol.com | Password: owner123');
        $this->command->info('Email admin: admin@kopiancol.com | Password: admin123');
        $this->command->info('Email customer: customer@kopiancol.com | Password: customer123');
    }
}