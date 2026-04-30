<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    // Ambil semua provinsi dari API EMSIFA
    public function getProvinces()
    {
        try {
            $response = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json([]);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }
    
    // Ambil kabupaten/kota berdasarkan ID provinsi
    public function getRegencies($provinceId)
    {
        try {
            $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$provinceId}.json");
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json([]);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }
    
    // Ambil kecamatan berdasarkan ID kabupaten/kota
    public function getDistricts($regencyId)
    {
        try {
            $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$regencyId}.json");
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json([]);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }
    
    // Ambil desa/kelurahan berdasarkan ID kecamatan
    public function getVillages($districtId)
    {
        try {
            $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$districtId}.json");
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json([]);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }
    
    // Hitung ongkos kirim
    public function calculateShipping(Request $request)
    {
        $shippingCosts = [
            ['courier' => 'jne', 'courier_name' => 'JNE', 'service' => 'REG', 'cost' => 15000, 'estimated_days' => 2],
            ['courier' => 'jne', 'courier_name' => 'JNE', 'service' => 'YES', 'cost' => 25000, 'estimated_days' => 1],
            ['courier' => 'tiki', 'courier_name' => 'TIKI', 'service' => 'REG', 'cost' => 14000, 'estimated_days' => 3],
            ['courier' => 'sicepat', 'courier_name' => 'SiCepat', 'service' => 'REG', 'cost' => 12000, 'estimated_days' => 2],
        ];
        
        return response()->json([
            'success' => true,
            'shipping_costs' => $shippingCosts
        ]);
    }
}