<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('shop.cart');
    }
    
    public function add(Request $request)
    {
        $product = Product::find($request->id);
        
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found');
        }
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->id])) {
            $cart[$request->id]['quantity']++;
        } else {
            $cart[$request->id] = [
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->price,
                'image' => $product->image
            ];
        }
        
        session()->put('cart', $cart);
        
        return redirect()->back()->with('success', 'Product added to cart');
    }
    
    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');
            $cart[$request->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }
        
        return redirect()->back();
    }
    
    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
        }
        
        return redirect()->back();
    }
    
    public function checkout()
    {
        // Cek apakah user sudah login
        if (!auth()->check()) {
            // Simpan URL yang ingin dituju setelah login
            session()->put('url.intended', route('checkout'));
            
            // Redirect ke halaman login dengan pesan
            return redirect()->route('login')->with('warning', 'Silakan login terlebih dahulu untuk melanjutkan checkout.');
        }
        
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong');
        }
        
        return view('shop.checkout');
    }
    // Di CartController.php tambahkan method ini
public function getCartWithDiscount()
{
    $cart = session()->get('cart', []);
    $subtotal = 0;
    foreach ($cart as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    
    $discount = session()->get('discount');
    $discountAmount = $discount['amount'] ?? 0;
    $total = $subtotal - $discountAmount;
    
    return response()->json([
        'subtotal' => $subtotal,
        'subtotal_formatted' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
        'discount' => $discount,
        'discount_amount' => $discountAmount,
        'discount_amount_formatted' => 'Rp ' . number_format($discountAmount, 0, ',', '.'),
        'total' => $total,
        'total_formatted' => 'Rp ' . number_format($total, 0, ',', '.')
    ]);
}
}