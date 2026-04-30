<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class IndonesiaRegionSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Mengambil data provinsi...');
        
        // Ambil provinsi
        $provinces = Http::get('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json')->json();
        
        foreach ($provinces as $province) {
            DB::table('indonesia_provinces')->updateOrInsert(
                ['id' => $province['id']],
                [
                    'name' => $province['name'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
            $this->command->info("Provinsi: {$province['name']}");
        }
        
        $this->command->info('Mengambil data kabupaten/kota...');
        
        // Ambil kabupaten per provinsi
        foreach ($provinces as $province) {
            $regencies = Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$province['id']}.json")->json();
            
            if ($regencies) {
                foreach ($regencies as $regency) {
                    DB::table('indonesia_cities')->updateOrInsert(
                        ['id' => $regency['id']],
                        [
                            'province_id' => $province['id'],
                            'name' => $regency['name'],
                            'created_at' => now(),
                            'updated_at' => now()
                        ]
                    );
                }
            }
            $this->command->info("Kabupaten untuk {$province['name']} selesai");
        }
        
        $this->command->info('Seeder selesai!');
    }
}