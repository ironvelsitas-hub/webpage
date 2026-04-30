<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Espresso Blend',
                'description' => 'Strong and aromatic espresso blend with chocolate notes',
                'price' => 25000,
                'stock' => 50,
                'category' => 'Espresso',
                'is_active' => true
            ],
            [
                'name' => 'Arabica Gayo',
                'description' => 'Premium Arabica from Gayo highlands with fruity notes',
                'price' => 35000,
                'stock' => 40,
                'category' => 'Single Origin',
                'is_active' => true
            ],
            [
                'name' => 'Robusta Flores',
                'description' => 'Bold Robusta with earthy and spicy notes',
                'price' => 20000,
                'stock' => 60,
                'category' => 'Single Origin',
                'is_active' => true
            ],
            [
                'name' => 'Cappuccino Powder',
                'description' => 'Instant cappuccino mix with creamy foam',
                'price' => 15000,
                'stock' => 100,
                'category' => 'Instant',
                'is_active' => true
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}