<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahIndonesiaSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🌍 Memulai seeder data wilayah Indonesia...');
        
        // Truncate tables terlebih dahulu
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('indonesia_villages')->truncate();
        DB::table('indonesia_districts')->truncate();
        DB::table('indonesia_cities')->truncate();
        DB::table('indonesia_provinces')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $this->seedProvinces();
        $this->seedRegencies();
        $this->seedDistricts();
        $this->seedVillages();
        
        $this->command->info('✅ Seeder wilayah Indonesia selesai!');
    }
    
    private function seedProvinces()
    {
        $provinces = [
            ['id' => 11, 'code' => '11', 'name' => 'ACEH'],
            ['id' => 12, 'code' => '12', 'name' => 'SUMATERA UTARA'],
            ['id' => 13, 'code' => '13', 'name' => 'SUMATERA BARAT'],
            ['id' => 14, 'code' => '14', 'name' => 'RIAU'],
            ['id' => 15, 'code' => '15', 'name' => 'JAMBI'],
            ['id' => 16, 'code' => '16', 'name' => 'SUMATERA SELATAN'],
            ['id' => 17, 'code' => '17', 'name' => 'BENGKULU'],
            ['id' => 18, 'code' => '18', 'name' => 'LAMPUNG'],
            ['id' => 19, 'code' => '19', 'name' => 'KEPULAUAN BANGKA BELITUNG'],
            ['id' => 21, 'code' => '21', 'name' => 'KEPULAUAN RIAU'],
            ['id' => 31, 'code' => '31', 'name' => 'DKI JAKARTA'],
            ['id' => 32, 'code' => '32', 'name' => 'JAWA BARAT'],
            ['id' => 33, 'code' => '33', 'name' => 'JAWA TENGAH'],
            ['id' => 34, 'code' => '34', 'name' => 'DI YOGYAKARTA'],
            ['id' => 35, 'code' => '35', 'name' => 'JAWA TIMUR'],
            ['id' => 36, 'code' => '36', 'name' => 'BANTEN'],
            ['id' => 51, 'code' => '51', 'name' => 'BALI'],
            ['id' => 52, 'code' => '52', 'name' => 'NUSA TENGGARA BARAT'],
            ['id' => 53, 'code' => '53', 'name' => 'NUSA TENGGARA TIMUR'],
            ['id' => 61, 'code' => '61', 'name' => 'KALIMANTAN BARAT'],
            ['id' => 62, 'code' => '62', 'name' => 'KALIMANTAN TENGAH'],
            ['id' => 63, 'code' => '63', 'name' => 'KALIMANTAN SELATAN'],
            ['id' => 64, 'code' => '64', 'name' => 'KALIMANTAN TIMUR'],
            ['id' => 65, 'code' => '65', 'name' => 'KALIMANTAN UTARA'],
            ['id' => 71, 'code' => '71', 'name' => 'SULAWESI UTARA'],
            ['id' => 72, 'code' => '72', 'name' => 'SULAWESI TENGAH'],
            ['id' => 73, 'code' => '73', 'name' => 'SULAWESI SELATAN'],
            ['id' => 74, 'code' => '74', 'name' => 'SULAWESI TENGGARA'],
            ['id' => 75, 'code' => '75', 'name' => 'GORONTALO'],
            ['id' => 76, 'code' => '76', 'name' => 'SULAWESI BARAT'],
            ['id' => 81, 'code' => '81', 'name' => 'MALUKU'],
            ['id' => 82, 'code' => '82', 'name' => 'MALUKU UTARA'],
            ['id' => 91, 'code' => '91', 'name' => 'PAPUA BARAT'],
            ['id' => 94, 'code' => '94', 'name' => 'PAPUA'],
        ];
        
        foreach ($provinces as $province) {
            DB::table('indonesia_provinces')->updateOrInsert(
                ['id' => $province['id']],
                [
                    'code' => $province['code'],
                    'name' => $province['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
        
        $this->command->info('   ✅ Provinces seeded');
    }
    
    private function seedRegencies()
    {
        $regencies = [
            // NUSA TENGGARA TIMUR (Province ID: 53)
            ['id' => 5301, 'province_id' => 53, 'code' => '5301', 'name' => 'KABUPATEN SUMBA BARAT'],
            ['id' => 5302, 'province_id' => 53, 'code' => '5302', 'name' => 'KABUPATEN SUMBA TIMUR'],
            ['id' => 5303, 'province_id' => 53, 'code' => '5303', 'name' => 'KABUPATEN KUPANG'],
            ['id' => 5304, 'province_id' => 53, 'code' => '5304', 'name' => 'KABUPATEN TIMOR TENGAH SELATAN'],
            ['id' => 5305, 'province_id' => 53, 'code' => '5305', 'name' => 'KABUPATEN TIMOR TENGAH UTARA'],
            ['id' => 5306, 'province_id' => 53, 'code' => '5306', 'name' => 'KABUPATEN BELU'],
            ['id' => 5307, 'province_id' => 53, 'code' => '5307', 'name' => 'KABUPATEN ALOR'],
            ['id' => 5308, 'province_id' => 53, 'code' => '5308', 'name' => 'KABUPATEN LEMBATA'],
            ['id' => 5309, 'province_id' => 53, 'code' => '5309', 'name' => 'KABUPATEN FLORES TIMUR'],
            ['id' => 5310, 'province_id' => 53, 'code' => '5310', 'name' => 'KABUPATEN SIKKA'],
            ['id' => 5311, 'province_id' => 53, 'code' => '5311', 'name' => 'KABUPATEN ENDE'],
            ['id' => 5312, 'province_id' => 53, 'code' => '5312', 'name' => 'KABUPATEN NGADA'],
            ['id' => 5313, 'province_id' => 53, 'code' => '5313', 'name' => 'KABUPATEN MANGGARAI'],
            ['id' => 5314, 'province_id' => 53, 'code' => '5314', 'name' => 'KABUPATEN ROTE NDAO'],
            ['id' => 5315, 'province_id' => 53, 'code' => '5315', 'name' => 'KABUPATEN MANGGARAI BARAT'],
            ['id' => 5316, 'province_id' => 53, 'code' => '5316', 'name' => 'KABUPATEN SUMBA TENGAH'],
            ['id' => 5317, 'province_id' => 53, 'code' => '5317', 'name' => 'KABUPATEN SUMBA BARAT DAYA'],
            ['id' => 5318, 'province_id' => 53, 'code' => '5318', 'name' => 'KABUPATEN NAGEKEO'],
            ['id' => 5319, 'province_id' => 53, 'code' => '5319', 'name' => 'KABUPATEN MANGGARAI TIMUR'],
            ['id' => 5320, 'province_id' => 53, 'code' => '5320', 'name' => 'KABUPATEN SABU RAIJUA'],
            ['id' => 5321, 'province_id' => 53, 'code' => '5321', 'name' => 'KABUPATEN MALAKA'],
            ['id' => 5371, 'province_id' => 53, 'code' => '5371', 'name' => 'KOTA KUPANG'],
            
            // DKI JAKARTA (Province ID: 31)
            ['id' => 3171, 'province_id' => 31, 'code' => '3171', 'name' => 'KOTA JAKARTA SELATAN'],
            ['id' => 3172, 'province_id' => 31, 'code' => '3172', 'name' => 'KOTA JAKARTA TIMUR'],
            ['id' => 3173, 'province_id' => 31, 'code' => '3173', 'name' => 'KOTA JAKARTA PUSAT'],
            ['id' => 3174, 'province_id' => 31, 'code' => '3174', 'name' => 'KOTA JAKARTA BARAT'],
            ['id' => 3175, 'province_id' => 31, 'code' => '3175', 'name' => 'KOTA JAKARTA UTARA'],
            ['id' => 3176, 'province_id' => 31, 'code' => '3176', 'name' => 'KOTA KEPULAUAN SERIBU'],
        ];
        
        foreach ($regencies as $regency) {
            DB::table('indonesia_cities')->updateOrInsert(
                ['id' => $regency['id']],
                [
                    'province_id' => $regency['province_id'],
                    'code' => $regency['code'],
                    'name' => $regency['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
        
        $this->command->info('   ✅ Regencies seeded');
    }
    
    private function seedDistricts()
    {
        $districts = [
            ['id' => 537101, 'regency_id' => 5371, 'code' => '537101', 'name' => 'KELAPA LIMA'],
            ['id' => 537102, 'regency_id' => 5371, 'code' => '537102', 'name' => 'KOTA LAMA'],
            ['id' => 537103, 'regency_id' => 5371, 'code' => '537103', 'name' => 'KOTA RAJA'],
            ['id' => 537104, 'regency_id' => 5371, 'code' => '537104', 'name' => 'MAULAFA'],
            ['id' => 537105, 'regency_id' => 5371, 'code' => '537105', 'name' => 'OEBOBO'],
            ['id' => 537106, 'regency_id' => 5371, 'code' => '537106', 'name' => 'ALAK'],
            
            ['id' => 531301, 'regency_id' => 5313, 'code' => '531301', 'name' => 'WAE RII'],
            ['id' => 531302, 'regency_id' => 5313, 'code' => '531302', 'name' => 'RUTENG'],
            ['id' => 531303, 'regency_id' => 5313, 'code' => '531303', 'name' => 'SATAR MESE'],
            ['id' => 531304, 'regency_id' => 5313, 'code' => '531304', 'name' => 'CIBAL'],
            ['id' => 531305, 'regency_id' => 5313, 'code' => '531305', 'name' => 'REOK'],
            
            ['id' => 317101, 'regency_id' => 3171, 'code' => '317101', 'name' => 'TEBET'],
            ['id' => 317102, 'regency_id' => 3171, 'code' => '317102', 'name' => 'SETIA BUDI'],
            ['id' => 317103, 'regency_id' => 3171, 'code' => '317103', 'name' => 'MAMPANG PRAPATAN'],
            ['id' => 317104, 'regency_id' => 3171, 'code' => '317104', 'name' => 'PANCORAN'],
            ['id' => 317105, 'regency_id' => 3171, 'code' => '317105', 'name' => 'KEBAYORAN LAMA'],
            ['id' => 317106, 'regency_id' => 3171, 'code' => '317106', 'name' => 'KEBAYORAN BARU'],
            ['id' => 317107, 'regency_id' => 3171, 'code' => '317107', 'name' => 'PASAR MINGGU'],
            ['id' => 317108, 'regency_id' => 3171, 'code' => '317108', 'name' => 'JAGAKARSA'],
            ['id' => 317109, 'regency_id' => 3171, 'code' => '317109', 'name' => 'CILANDAK'],
            ['id' => 317110, 'regency_id' => 3171, 'code' => '317110', 'name' => 'PESANGGRAHAN'],
            
            ['id' => 517101, 'regency_id' => 5171, 'code' => '517101', 'name' => 'DENPASAR SELATAN'],
            ['id' => 517102, 'regency_id' => 5171, 'code' => '517102', 'name' => 'DENPASAR TIMUR'],
            ['id' => 517103, 'regency_id' => 5171, 'code' => '517103', 'name' => 'DENPASAR BARAT'],
            ['id' => 517104, 'regency_id' => 5171, 'code' => '517104', 'name' => 'DENPASAR UTARA'],
        ];
        
        foreach ($districts as $district) {
            DB::table('indonesia_districts')->updateOrInsert(
                ['id' => $district['id']],
                [
                    'city_id' => $district['regency_id'],
                    'code' => $district['code'],
                    'name' => $district['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
        
        $this->command->info('   ✅ Districts seeded');
    }
    
    private function seedVillages()
    {
        $villages = [
            ['id' => 531302001, 'district_id' => 531302, 'code' => '531302001', 'name' => 'WAE BELANG'],
            ['id' => 531302002, 'district_id' => 531302, 'code' => '531302002', 'name' => 'BENTENG WAJU'],
            ['id' => 531302003, 'district_id' => 531302, 'code' => '531302003', 'name' => 'GOLO LON'],
            ['id' => 531302004, 'district_id' => 531302, 'code' => '531302004', 'name' => 'PONG LEO'],
            ['id' => 531302005, 'district_id' => 531302, 'code' => '531302005', 'name' => 'LIA NALE'],
            
            ['id' => 537101001, 'district_id' => 537101, 'code' => '537101001', 'name' => 'KELAPA LIMA'],
            ['id' => 537101002, 'district_id' => 537101, 'code' => '537101002', 'name' => 'LASIANA'],
            ['id' => 537101003, 'district_id' => 537101, 'code' => '537101003', 'name' => 'OESAPA'],
            ['id' => 537101004, 'district_id' => 537101, 'code' => '537101004', 'name' => 'TDM'],
            ['id' => 537101005, 'district_id' => 537101, 'code' => '537101005', 'name' => 'FATULULI'],
            
            ['id' => 317106001, 'district_id' => 317106, 'code' => '317106001', 'name' => 'GANDARIA SELATAN'],
            ['id' => 317106002, 'district_id' => 317106, 'code' => '317106002', 'name' => 'GANDARIA UTARA'],
            ['id' => 317106003, 'district_id' => 317106, 'code' => '317106003', 'name' => 'CIPETE UTARA'],
            ['id' => 317106004, 'district_id' => 317106, 'code' => '317106004', 'name' => 'CIPETE SELATAN'],
            ['id' => 317106005, 'district_id' => 317106, 'code' => '317106005', 'name' => 'PULO'],
            ['id' => 317106006, 'district_id' => 317106, 'code' => '317106006', 'name' => 'MELAWAI'],
            ['id' => 317106007, 'district_id' => 317106, 'code' => '317106007', 'name' => 'KUNINGAN TIMUR'],
            ['id' => 317106008, 'district_id' => 317106, 'code' => '317106008', 'name' => 'KUNINGAN BARAT'],
            ['id' => 317106009, 'district_id' => 317106, 'code' => '317106009', 'name' => 'KARET KUNINGAN'],
            ['id' => 317106010, 'district_id' => 317106, 'code' => '317106010', 'name' => 'KARET SEMANGGI'],
            
            ['id' => 517101001, 'district_id' => 517101, 'code' => '517101001', 'name' => 'SERANGAN'],
            ['id' => 517101002, 'district_id' => 517101, 'code' => '517101002', 'name' => 'PEMOGAN'],
            ['id' => 517101003, 'district_id' => 517101, 'code' => '517101003', 'name' => 'PEDUNGAN'],
            ['id' => 517101004, 'district_id' => 517101, 'code' => '517101004', 'name' => 'SESETAN'],
            ['id' => 517101005, 'district_id' => 517101, 'code' => '517101005', 'name' => 'SANUR KAUH'],
            ['id' => 517101006, 'district_id' => 517101, 'code' => '517101006', 'name' => 'SANUR KAJAH'],
            ['id' => 517101007, 'district_id' => 517101, 'code' => '517101007', 'name' => 'RENON'],
        ];
        
        foreach ($villages as $village) {
            DB::table('indonesia_villages')->updateOrInsert(
                ['id' => $village['id']],
                [
                    'district_id' => $village['district_id'],
                    'code' => $village['code'],
                    'name' => $village['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
        
        $this->command->info('   ✅ Villages seeded');
    }
}