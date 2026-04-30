<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RawMaterial;

class RawMaterialSeeder extends Seeder
{
    public function run()
    {
        $materials = [
            [
                'name' => 'Biji Kopi Arabica',
                'category' => 'Kopi',
                'unit' => 'kg',
                'min_stock' => 10,
                'supplier' => 'Petani Kopi Colol',
                'description' => 'Biji kopi arabica kualitas premium',
                'is_active' => true,
                'stock' => 50
            ],
            [
                'name' => 'Biji Kopi Robusta',
                'category' => 'Kopi',
                'unit' => 'kg',
                'min_stock' => 10,
                'supplier' => 'Petani Kopi Ruteng',
                'description' => 'Biji kopi robusta pilihan',
                'is_active' => true,
                'stock' => 40
            ],
            [
                'name' => 'Susu UHT Full Cream',
                'category' => 'Susu',
                'unit' => 'liter',
                'min_stock' => 20,
                'supplier' => 'Diamond',
                'description' => 'Susu UHT full cream 1 liter',
                'is_active' => true,
                'stock' => 30
            ],
            [
                'name' => 'Gula Pasir',
                'category' => 'Gula',
                'unit' => 'kg',
                'min_stock' => 15,
                'supplier' => 'Gulaku',
                'description' => 'Gula pasir premium',
                'is_active' => true,
                'stock' => 25
            ],
            [
                'name' => 'Sirup Vanilla',
                'category' => 'Syrup',
                'unit' => 'botol',
                'min_stock' => 5,
                'supplier' => 'Monin',
                'description' => 'Sirup vanilla 500ml',
                'is_active' => true,
                'stock' => 10
            ],
            [
                'name' => 'Coklat Bubuk',
                'category' => 'Lainnya',
                'unit' => 'kg',
                'min_stock' => 5,
                'supplier' => 'Van Houten',
                'description' => 'Coklat bubuk untuk minuman',
                'is_active' => true,
                'stock' => 8
            ]
        ];

        foreach ($materials as $material) {
            RawMaterial::updateOrCreate(
                ['name' => $material['name']],
                $material
            );
        }
    }
}