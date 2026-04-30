<?php

namespace App\Http\Controllers;

use App\Models\DineInOrder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DineInController extends Controller
{
    /**
     * Store dine in order dari customer - DIPERBAIKI STOK NYA
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'table_number' => 'required|integer|min:1|max:50',
                'customer_name' => 'nullable|string|max:100',
                'notes' => 'nullable|string'
            ]);
            
            $product = Product::findOrFail($request->product_id);
            
            // CEK STOK
            if ($product->stock < $request->quantity) {
                return redirect()->back()->with('error', "Maaf, stok {$product->name} tidak mencukupi! Stok tersisa: {$product->stock}");
            }
            
            DB::beginTransaction();
            
            // KURANGI STOK PRODUK
            $stockSebelum = $product->stock;
            $product->stock = $product->stock - $request->quantity;
            $product->save();
            
            // Log untuk debugging
            Log::info("Stok berkurang (Dine In): {$product->name} dari {$stockSebelum} menjadi {$product->stock}");
            
            $items = [[
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity' => $request->quantity,
                'price' => $product->price,
                'subtotal' => $product->price * $request->quantity
            ]];
            
            $totalAmount = $product->price * $request->quantity;
            $orderNumber = 'DINE-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            
            // ============ PEMBAYARAN OTOMATIS PAID ============
            // Untuk Dine In, payment_status otomatis paid karena pembayaran di tempat
            $order = DineInOrder::create([
                'order_number' => $orderNumber,
                'customer_name' => $request->customer_name ?? 'Guest',
                'table_number' => $request->table_number,
                'items' => json_encode($items),
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
                'status' => 'pending',
                'payment_status' => 'paid' // ← OTOMATIS PAID untuk Dine In
            ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', "✅ Pesanan berhasil! Order #: {$order->order_number}");
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Dine In Order Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Admin: Lihat semua dine in orders
     */
    public function adminIndex()
    {
        try {
            $orders = DineInOrder::orderBy('created_at', 'desc')->get();
            $pendingOrders = DineInOrder::where('status', 'pending')->count();
            $processingOrders = DineInOrder::where('status', 'processing')->count();
            $completedOrders = DineInOrder::where('status', 'completed')->count();
            $todayOrders = DineInOrder::whereDate('created_at', today())->count();
            $totalRevenue = DineInOrder::where('status', 'completed')->sum('total_amount');
            
            return view('admin.dinein', compact('orders', 'pendingOrders', 'processingOrders', 'completedOrders', 'todayOrders', 'totalRevenue'));
        } catch (\Exception $e) {
            Log::error('Admin Dine In Error: ' . $e->getMessage());
            return view('admin.dinein')->with('error', 'Gagal memuat data pesanan');
        }
    }
    
/**
 * Admin: Update status pesanan dine in (Dengan pengembalian stok jika dibatalkan)
 */
public function updateStatus(Request $request, $id)
{
    try {
        $order = DineInOrder::findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;
        
        DB::beginTransaction();
        
        $items = is_string($order->item) ? json_decode($order->item, true) : $order->item;
        
        // ========== PERBAIKAN: Jika pesanan dibatalkan, kembalikan stok ==========
        if ($newStatus == 'cancelled' && $oldStatus != 'cancelled') {
            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->stock = $product->stock + $item['quantity'];
                    $product->save();
                    
                    \Log::info("Stok dikembalikan (Dine In Cancel): {$product->name} +{$item['quantity']}, stok sekarang: {$product->stock}");
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
                    
                    \Log::info("Stok dikurangi (Dine In Reactivated): {$product->name} -{$item['quantity']}, stok sekarang: {$product->stock}");
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
        \Log::error('Update Dine In Status Error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal mengupdate status: ' . $e->getMessage());
    }
}    
    /**
     * Admin: Update status pembayaran
     */
    public function updatePayment(Request $request, $id)
    {
        try {
            $order = DineInOrder::findOrFail($id);
            $order->update(['payment_status' => $request->payment_status]);
            
            return redirect()->back()->with('success', 'Status pembayaran diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate status pembayaran');
        }
    }
    
    /**
/**
 * Admin: Hapus pesanan dine in - Kembalikan stok
 */
public function destroy($id)
{
    try {
        DB::beginTransaction();
        
        $order = DineInOrder::findOrFail($id);
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
                    
                    \Log::info("Stok dikembalikan (Dine In Dihapus): {$product->name} +{$item['quantity']}, stok sekarang: {$product->stock}");
                }
            }
        }
        
        $order->delete();
        
        DB::commit();
        
        return redirect()->back()->with('success', "Pesanan {$orderNumber} berhasil dihapus");
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Delete Dine In Order Error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal menghapus pesanan: ' . $e->getMessage());
    }
}    public function checkNewOrders(Request $request)
{
    $newOrders = DineInOrder::where('created_at', '>', now()->subSeconds(30))
        ->where('is_viewed', false)
        ->get();
    
    return response()->json(['newOrders' => $newOrders]);
}

public function markOrderAsViewed(Request $request)
{
    $order = DineInOrder::find($request->order_id);
    if ($order) {
        $order->update(['is_viewed' => true]);
    }
    
    return response()->json(['success' => true]);
}
}