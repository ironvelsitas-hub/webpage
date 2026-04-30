<?php
// database/seeders/DiscountSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Discount;
use Carbon\Carbon;

class DiscountSeeder extends Seeder
{
    public function run()
    {
        $discounts = [
            [
                'code' => 'WELCOME10',
                'name' => 'Diskon Welcome 10%',
                'type' => 'percentage',
                'value' => 10,
                'min_purchase' => 50000,
                'max_discount' => 50000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(3),
                'description' => 'Diskon 10% untuk pembelian minimal Rp50.000'
            ],
            [
                'code' => 'NEWUSER20',
                'name' => 'Diskon Member Baru 20%',
                'type' => 'percentage',
                'value' => 20,
                'min_purchase' => 75000,
                'max_discount' => 75000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(6),
                'description' => 'Diskon khusus member baru 20%'
            ],
            [
                'code' => 'FREESHIP',
                'name' => 'Gratis Ongkir',
                'type' => 'fixed',
                'value' => 20000,
                'min_purchase' => 100000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(1),
                'description' => 'Potongan ongkir Rp20.000'
            ],
            [
                'code' => 'COFFEE30',
                'name' => 'Diskon Kopi 30%',
                'type' => 'percentage',
                'value' => 30,
                'min_purchase' => 150000,
                'max_discount' => 100000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(2),
                'description' => 'Diskon 30% khusus pembelian kopi'
            ]
        ];

        foreach ($discounts as $discount) {
            Discount::updateOrCreate(['code' => $discount['code']], $discount);
        }
    }
}