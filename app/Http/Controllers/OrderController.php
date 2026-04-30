<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
/**
 * Process order - Simpan data diskon
 */
public function processOrder(Request $request)
{
    try {
        DB::beginTransaction();
        
        // Validasi input
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'items' => 'required|array',
            'payment_method' => 'required|string'
        ]);
        
        // Generate order number
        $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        
        // Ambil diskon dari session
        $discount = session()->get('discount');
        $discountAmount = $discount['amount'] ?? 0;
        $discountCode = $discount['code'] ?? null;
        $discountId = $discount['id'] ?? null;
        
        // ⭐ UPDATE USED COUNT PADA TABEL DISCOUNTS ⭐
        if ($discountId) {
            $discountModel = Discount::find($discountId);
            if ($discountModel) {
                $discountModel->increment('used_count');
            }
        }
        
        // Hitung subtotal dan siapkan items
        $items = [];
        $subtotal = 0;
        
        foreach ($request->items as $id => $item) {
            $product = Product::find($id);
            if ($product) {
                // CEK STOK SEBELUM ORDER
                if ($product->stock < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stok {$product->name} tidak mencukupi! Stok tersisa: {$product->stock}"
                    ], 400);
                }
                
                $itemSubtotal = $product->price * $item['quantity'];
                $subtotal += $itemSubtotal;
                
                $items[] = [
                    'product_id' => $id,
                    'name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $itemSubtotal
                ];
                
                // KURANGI STOK PRODUK
                $product->stock = $product->stock - $item['quantity'];
                $product->save();
            }
        }
        
        // Total setelah diskon + pajak (10%)
        $tax = $subtotal * 0.1;
        $total = ($subtotal + $tax) - $discountAmount;
        
        // Create order dengan data diskon
        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'items' => json_encode($items),
            'subtotal' => $subtotal,
            'discount_id' => $discountId,
            'discount_code' => $discountCode,
            'discount_amount' => $discountAmount, // ← PASTIKAN INI TERISI
            'total_amount' => $total,
            'payment_method' => $request->payment_method,
            'payment_status' => 'paid',
            'status' => 'pending',
            'delivery_status' => 'pending',
            'notes' => $request->notes ?? null
        ]);
        
        // Hapus diskon dari session setelah checkout
        session()->forget('discount');
        
        // Clear cart
        session()->forget('cart');
        
        DB::commit();
        
        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'payment_status' => 'paid',
            'discount_applied' => $discountAmount > 0,
            'discount_amount' => $discountAmount
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Process Order Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}    
    /**
     * Show order success page
     */
    public function orderSuccess(Request $request)
    {
        try {
            $orderId = $request->get('order_id');
            if (!$orderId) {
                return redirect()->route('shop.index')->with('error', 'Order tidak ditemukan');
            }
            
            $order = Order::findOrFail($orderId);
            return view('shop.order-success', compact('order'));
            
        } catch (\Exception $e) {
            Log::error('Order Success Error: ' . $e->getMessage());
            return redirect()->route('shop.index')->with('error', 'Order tidak ditemukan');
        }
    }
    
    /**
     * Customer: Track order
     */
    public function trackOrder(Request $request)
    {
        $orderNumber = $request->get('order_number');
        
        if ($orderNumber) {
            $order = Order::where('order_number', $orderNumber)->first();
            if ($order) {
                return view('shop.track-order', compact('order'));
            }
            return redirect()->back()->with('error', 'Order tidak ditemukan');
        }
        
        return view('shop.track-order');
    }
    
    /**
     * Admin: Lihat semua orders - DURUTKAN BERDASARKAN STATUS
     */
    public function adminIndex()
    {
        try {
            // Urutkan berdasarkan status (pending, processing, completed, cancelled)
            // Dan berdasarkan created_at terbaru
            $orders = Order::orderByRaw("FIELD(status, 'pending', 'processing', 'completed', 'cancelled')")
                ->orderBy('created_at', 'desc')
                ->get();
            
            $pendingOrders = Order::where('status', 'pending')->count();
            $processingOrders = Order::where('status', 'processing')->count();
            $completedOrders = Order::where('status', 'completed')->count();
            $cancelledOrders = Order::where('status', 'cancelled')->count();
            $todayOrders = Order::whereDate('created_at', today())->count();
            $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
            
            return view('admin.orders.index', compact(
                'orders', 
                'pendingOrders', 
                'processingOrders', 
                'completedOrders',
                'cancelledOrders',
                'todayOrders',
                'totalRevenue'
            ));
        } catch (\Exception $e) {
            Log::error('Admin Orders Error: ' . $e->getMessage());
            return view('admin.orders.index')->with('error', 'Gagal memuat data orders');
        }
    }
    
/**
 * Admin: Update status pesanan (Dengan pengembalian stok jika dibatalkan)
 */
public function updateStatus(Request $request, Order $order)
{
    try {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);
        
        DB::beginTransaction();
        
        $oldStatus = $order->status;
        $newStatus = $request->status;
        
        $items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
        
        // ========== PERBAIKAN: Jika pesanan dibatalkan, kembalikan stok ==========
        if ($newStatus == 'cancelled' && $oldStatus != 'cancelled') {
            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->stock = $product->stock + $item['quantity'];
                    $product->save();
                    
                    // Log untuk debugging
                    \Log::info("Stok dikembalikan (Order Cancel): {$product->name} +{$item['quantity']}, stok sekarang: {$product->stock}");
                }
            }
        }
        
        // ========== Jika pesanan dibatalkan lalu diaktifkan lagi, kurangi stok ==========
        if ($oldStatus == 'cancelled' && $newStatus != 'cancelled') {
            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                if ($product && $product->stock >= $item['quantity']) {
                    $product->stock = $product->stock - $item['quantity'];
                    $product->save();
                    
                    \Log::info("Stok dikurangi (Order Reactivated): {$product->name} -{$item['quantity']}, stok sekarang: {$product->stock}");
                }
            }
        }
        
        $order->update(['status' => $newStatus]);
        
        DB::commit();
        
        $statusMessages = [
            'pending' => 'Pesanan masuk ke antrian',
            'processing' => 'Pesanan sedang diproses',
            'completed' => 'Pesanan selesai',
            'cancelled' => 'Pesanan dibatalkan - Stok dikembalikan'
        ];
        
        return redirect()->back()->with('success', $statusMessages[$newStatus]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Update Status Error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal mengupdate status: ' . $e->getMessage());
    }
}    
    /**
     * Admin: Update status pembayaran
     */
    public function updatePayment(Request $request, Order $order)
    {
        try {
            $request->validate([
                'payment_status' => 'required|in:unpaid,paid'
            ]);
            
            $order->update(['payment_status' => $request->payment_status]);
            
            $paymentMessages = [
                'unpaid' => 'Status pembayaran: Belum Dibayar',
                'paid' => 'Status pembayaran: Sudah Dibayar'
            ];
            
            return redirect()->back()->with('success', $paymentMessages[$request->payment_status]);
            
        } catch (\Exception $e) {
            Log::error('Update Payment Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengupdate status pembayaran');
        }
    }
    
    /**
     * Admin: Update status pengiriman
     */
    public function updateDeliveryStatus(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delivery_status = $request->delivery_status;
            $order->save();
            
            return redirect()->back()->with('success', 'Status pengiriman diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate status pengiriman');
        }
    }
    
    /**
     * Admin: Detail order
     */
    public function show($id)
    {
        try {
            $order = Order::findOrFail($id);
            return view('admin.orders.show', compact('order'));
        } catch (\Exception $e) {
            return redirect()->route('admin.orders')->with('error', 'Order tidak ditemukan');
        }
    }
    
/**
 * Admin: Hapus order - Kembalikan stok jika pesanan belum selesai
 */
public function destroy($id)
{
    try {
        DB::beginTransaction();
        
        $order = Order::findOrFail($id);
        $orderNumber = $order->order_number;
        
        // Ambil items dari order
        $items = is_string($order->item) ? json_decode($order->item, true) : $order->item;
        
        // ========== PERBAIKAN: Kembalikan stok jika pesanan belum selesai ==========
        if ($order->status != 'completed' && !empty($items)) {
            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->stock = $product->stock + $item['quantity'];
                    $product->save();
                    
                    \Log::info("Stok dikembalikan (Order Dihapus): {$product->name} +{$item['quantity']}, stok sekarang: {$product->stock}");
                }
            }
        }
        
        $order->delete();
        
        DB::commit();
        
        return redirect()->route('admin.orders')->with('success', "Pesanan {$orderNumber} berhasil dihapus");
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Delete Order Error: ' . $e->getMessage());
        return redirect()->route('admin.orders')->with('error', 'Gagal menghapus pesanan: ' . $e->getMessage());
    }
}    
    /**
     * Customer: Detail order untuk customer
     */
    public function customerOrderDetail($id)
    {
        try {
            $order = Order::findOrFail($id);
            return view('customer.order-detail', compact('order'));
        } catch (\Exception $e) {
            return redirect()->route('customer.orders')->with('error', 'Order tidak ditemukan');
        }
    }
    
    /**
     * Check order status for AJAX request (dengan confirmation)
     */
    public function checkOrderStatus(Request $request)
    {
        try {
            $orderId = $request->get('order_id');
            $order = Order::find($orderId);
            
            if ($order) {
                return response()->json([
                    'success' => true,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'payment_method' => $order->payment_method,
                    'delivery_status' => $order->delivery_status ?? 'pending',
                    'confirmed_at' => $order->confirmed_at,
                    'total_amount' => $order->total_amount
                ]);
            }
            
            return response()->json(['success' => false, 'message' => 'Order not found']);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * Confirm order received by customer
     */
    public function confirmOrderReceived(Request $request)
    {
        try {
            $orderId = $request->order_id;
            $order = Order::find($orderId);
            
            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Order tidak ditemukan']);
            }
            
            if ($order->confirmed_at) {
                return response()->json(['success' => false, 'message' => 'Pesanan sudah dikonfirmasi sebelumnya']);
            }
            
            $order->confirmed_at = now();
            $order->save();
            
            return response()->json(['success' => true, 'message' => 'Pesanan berhasil dikonfirmasi']);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function checkNewOrders(Request $request)
    {
        // Get orders from last 30 seconds
        $newOrders = Order::where('created_at', '>', now()->subSeconds(30))
            ->where('is_viewed', false)
            ->get();
        
        return response()->json(['newOrders' => $newOrders]);
    }
    
    public function markOrderAsViewed(Request $request)
    {
        $order = Order::find($request->order_id);
        if ($order) {
            $order->update(['is_viewed' => true]);
        }
        
        return response()->json(['success' => true]);
    }
}