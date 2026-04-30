<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FreeShippingController extends Controller
{
    /**
     * Menampilkan halaman informasi gratis ongkir
     */
    public function index()
    {
        // Ambil data promo FREESHIP
        $freeShippingPromo = Discount::where('code', 'FREESHIP')
            ->where('is_active', true)
            ->first();
        
        // Hitung progres belanja customer (jika login)
        $cartSubtotal = 0;
        $remainingForFreeShip = 100000;
        $progressPercent = 0;
        
        if (auth()->check()) {
            $cart = session()->get('cart', []);
            foreach ($cart as $item) {
                $cartSubtotal += $item['price'] * $item['quantity'];
            }
            $remainingForFreeShip = max(0, 100000 - $cartSubtotal);
            $progressPercent = $cartSubtotal > 0 ? min(($cartSubtotal / 100000) * 100, 100) : 0;
        }
        
        return view('free-shipping', compact(
            'freeShippingPromo', 
            'cartSubtotal', 
            'remainingForFreeShip', 
            'progressPercent'
        ));
    }
    
    /**
     * API untuk cek status gratis ongkir (AJAX)
     */
    public function checkStatus()
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        $isEligible = $subtotal >= 100000;
        $remaining = max(0, 100000 - $subtotal);
        $progress = $subtotal > 0 ? min(($subtotal / 100000) * 100, 100) : 0;
        
        // Cek apakah promo FREESHIP aktif
        $promoActive = Discount::where('code', 'FREESHIP')
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->exists();
        
        return response()->json([
            'success' => true,
            'eligible' => $isEligible,
            'remaining' => $remaining,
            'remaining_formatted' => 'Rp ' . number_format($remaining, 0, ',', '.'),
            'progress' => $progress,
            'subtotal' => $subtotal,
            'subtotal_formatted' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
            'promo_active' => $promoActive,
            'target' => 100000,
            'target_formatted' => 'Rp 100.000'
        ]);
    }
    
    /**
     * Apply promo gratis ongkir otomatis
     */
    public function applyFreeShipping()
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        if ($subtotal < 100000) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal belanja Rp 100.000 untuk mendapatkan gratis ongkir'
            ], 400);
        }
        
        $freeShipping = Discount::where('code', 'FREESHIP')
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();
        
        if (!$freeShipping) {
            return response()->json([
                'success' => false,
                'message' => 'Promo gratis ongkir sedang tidak tersedia'
            ], 400);
        }
        
        // Simpan diskon ke session
        Session::put('discount', [
            'id' => $freeShipping->id,
            'code' => $freeShipping->code,
            'name' => $freeShipping->name,
            'type' => $freeShipping->type,
            'value' => $freeShipping->value,
            'amount' => $freeShipping->value
        ]);
        
        return response()->json([
            'success' => true,
            'message' => '🎉 Gratis ongkir berhasil digunakan!',
            'discount' => [
                'code' => $freeShipping->code,
                'name' => $freeShipping->name,
                'amount' => $freeShipping->value,
                'amount_formatted' => 'Rp ' . number_format($freeShipping->value, 0, ',', '.')
            ],
            'total' => $subtotal - $freeShipping->value
        ]);
    }
    
    /**
     * Get detail promo gratis ongkir
     */
    public function getDetail()
    {
        $promo = Discount::where('code', 'FREESHIP')
            ->where('is_active', true)
            ->first();
        
        $isActive = false;
        if ($promo) {
            $isActive = $promo->start_date <= now() && $promo->end_date >= now();
        }
        
        return response()->json([
            'success' => true,
            'promo' => [
                'code' => $promo->code ?? 'FREESHIP',
                'name' => $promo->name ?? 'Gratis Ongkir',
                'description' => $promo->description ?? 'Gratis ongkir untuk pembelian minimal Rp 100.000',
                'min_purchase' => $promo->min_purchase ?? 100000,
                'min_purchase_formatted' => 'Rp ' . number_format($promo->min_purchase ?? 100000, 0, ',', '.'),
                'value' => $promo->value ?? 20000,
                'value_formatted' => 'Rp ' . number_format($promo->value ?? 20000, 0, ',', '.'),
                'is_active' => $isActive,
                'start_date' => $promo->start_date ?? null,
                'end_date' => $promo->end_date ?? null
            ]
        ]);
    }
}