<?php
// app/Http/Controllers/DiscountController.php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DiscountController extends Controller
{
    // Cek dan apply diskon
    public function applyDiscount(Request $request)
    {
        $request->validate([
            'discount_code' => 'required|string|max:50'
        ]);

        $code = strtoupper($request->discount_code);
        $discount = Discount::where('code', $code)->first();

        if (!$discount) {
            return response()->json([
                'success' => false,
                'message' => 'Kode diskon tidak ditemukan'
            ], 404);
        }

        if (!$discount->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Kode diskon sudah kadaluarsa atau tidak berlaku'
            ], 400);
        }

        // Ambil subtotal dari cart
        $cart = session()->get('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        if ($subtotal < $discount->min_purchase) {
            return response()->json([
                'success' => false,
                'message' => "Minimal belanja Rp " . number_format($discount->min_purchase, 0, ',', '.')
            ], 400);
        }

        // Simpan diskon ke session
        $discountAmount = $discount->calculateDiscount($subtotal);
        
        Session::put('discount', [
            'id' => $discount->id,
            'code' => $discount->code,
            'name' => $discount->name,
            'type' => $discount->type,
            'value' => $discount->value,
            'amount' => $discountAmount
        ]);

        return response()->json([
            'success' => true,
            'message' => "Kode {$discount->code} berhasil digunakan!",
            'discount' => [
                'code' => $discount->code,
                'name' => $discount->name,
                'amount' => $discountAmount,
                'amount_formatted' => 'Rp ' . number_format($discountAmount, 0, ',', '.')
            ],
            'total' => $subtotal - $discountAmount
        ]);
    }

    // Hapus diskon
    public function removeDiscount()
    {
        Session::forget('discount');
        
        return response()->json([
            'success' => true,
            'message' => 'Diskon dihapus'
        ]);
    }

    // Get active discounts (untuk ditampilkan di halaman)
    public function getActiveDiscounts()
    {
        $discounts = Discount::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();
        
        return response()->json([
            'success' => true,
            'discounts' => $discounts
        ]);
    }
}