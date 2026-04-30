<?php
// database/seeders/BranchSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    public function run()
    {
        $branches = [
            [
                'name' => 'Kopi Ancol - Colol',
                'slug' => 'colol',
                'address' => 'Jl. Ruteng-Elar, Colol, Manggarai Timur, Nusa Tenggara Timur',
                'phone' => '+62 812-4613-5710',
                'email' => 'colol@kopiancol.com',
                'latitude' => '-8.5352',
                'longitude' => '120.4629',
                'description' => 'Cabang utama Kopi Ancol yang menyajikan kopi premium dengan pemandangan alam pegunungan yang indah.',
                'open_time' => '07:00',
                'close_time' => '22:00',
                'order_position' => 1,
                'is_active' => true
            ],
            [
                'name' => 'Kopi Ancol - Ruteng',
                'slug' => 'ruteng',
                'address' => 'Jl. Ahmad Yani No. 45, Ruteng, Manggarai, Nusa Tenggara Timur',
                'phone' => '+62 812-4613-5711',
                'email' => 'ruteng@kopiancol.com',
                'latitude' => '-8.6180',
                'longitude' => '120.4644',
                'description' => 'Cabang kedua di pusat kota Ruteng dengan suasana modern dan nyaman.',
                'open_time' => '08:00',
                'close_time' => '23:00',
                'order_position' => 2,
                'is_active' => true
            ],
            [
                'name' => 'Kopi Ancol - Labuan Bajo',
                'slug' => 'labuan-bajo',
                'address' => 'Jl. Soekarno Hatta, Labuan Bajo, Manggarai Barat, Nusa Tenggara Timur',
                'phone' => '+62 812-4613-5712',
                'email' => 'labuanbajo@kopiancol.com',
                'latitude' => '-8.4962',
                'longitude' => '119.8873',
                'description' => 'Nikmati kopi terbaik sambil menikmati sunset di Labuan Bajo.',
                'open_time' => '07:00',
                'close_time' => '00:00',
                'order_position' => 3,
                'is_active' => true
            ]
        ];

        foreach ($branches as $branch) {
            Branch::updateOrCreate(['slug' => $branch['slug']], $branch);
        }
    }
}