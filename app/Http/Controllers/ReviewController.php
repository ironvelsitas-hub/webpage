<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Tampilkan form review untuk produk yang sudah dibeli
     */
    public function create($productId)
    {
        $product = Product::findOrFail($productId);
        
        // CEK APAKAH USER SUDAH MEMBELI PRODUK INI
        $user = Auth::user();
        $hasPurchased = false;
        
        // Ambil semua order user yang sudah completed
        $orders = Order::where('customer_email', $user->email)
            ->where('status', 'completed')
            ->get();
        
        foreach ($orders as $order) {
            // Decode items dari JSON
            $items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
            
            if (is_array($items)) {
                foreach ($items as $item) {
                    if (isset($item['product_id']) && $item['product_id'] == $productId) {
                        $hasPurchased = true;
                        break 2;
                    }
                }
            }
        }
        
        // Debug log
        Log::info("User {$user->email} check review for product {$productId}: hasPurchased = " . ($hasPurchased ? 'YES' : 'NO'));
        
        if (!$hasPurchased) {
            return redirect()->route('product.detail', $productId)->with('error', 'Anda hanya bisa mereview produk yang sudah Anda beli dan pesanan sudah selesai.');
        }
        
        // Cek apakah sudah pernah review
        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();
        
        if ($existingReview) {
            return redirect()->route('product.detail', $productId)->with('info', 'Anda sudah pernah mereview produk ini.');
        }
        
        return view('customer.review-create', compact('product'));
    }
    
    /**
     * Simpan review
     */
    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);
        
        $product = Product::findOrFail($productId);
        $user = Auth::user();
        
        // CEK APAKAH USER SUDAH MEMBELI PRODUK INI
        $hasPurchased = false;
        $orderId = null;
        
        $orders = Order::where('customer_email', $user->email)
            ->where('status', 'completed')
            ->get();
        
        foreach ($orders as $order) {
            $items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
            
            if (is_array($items)) {
                foreach ($items as $item) {
                    if (isset($item['product_id']) && $item['product_id'] == $productId) {
                        $hasPurchased = true;
                        $orderId = $order->id;
                        break 2;
                    }
                }
            }
        }
        
        if (!$hasPurchased) {
            return redirect()->back()->with('error', 'Anda hanya bisa mereview produk yang sudah Anda beli.');
        }
        
        // Cek apakah sudah pernah review
        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();
        
        if ($existingReview) {
            return redirect()->back()->with('error', 'Anda sudah pernah mereview produk ini.');
        }
        
        DB::beginTransaction();
        
        try {
            $review = Review::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'order_id' => $orderId,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'status' => 'pending',
                'is_verified_purchase' => true,
            ]);
            
            DB::commit();
            
            return redirect()->route('product.detail', $productId)->with('success', 'Terima kasih! Review Anda akan ditampilkan setelah disetujui admin.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store review error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan review: ' . $e->getMessage());
        }
    }
    
    /**
     * Lihat review saya
     */
    public function myReviews()
    {
        $reviews = Review::where('user_id', Auth::id())
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('customer.reviews', compact('reviews'));
    }
    
    /**
     * Edit review
     */
    public function edit($id)
    {
        $review = Review::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        if ($review->status == 'approved') {
            return redirect()->back()->with('error', 'Review yang sudah disetujui tidak dapat diedit.');
        }
        
        return view('customer.review-edit', compact('review'));
    }
    
    /**
     * Update review
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);
        
        $review = Review::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        if ($review->status == 'approved') {
            return redirect()->back()->with('error', 'Review yang sudah disetujui tidak dapat diedit.');
        }
        
        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);
        
        return redirect()->route('customer.reviews')->with('success', 'Review berhasil diupdate.');
    }
    
    /**
     * Hapus review
     */
    public function destroy($id)
    {
        $review = Review::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $review->delete();
        
        return redirect()->route('customer.reviews')->with('success', 'Review berhasil dihapus.');
    }
    
    /**
     * Admin: Lihat semua review
     */
    public function adminIndex()
    {
        $reviews = Review::with(['user', 'product'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $pendingCount = Review::where('status', 'pending')->count();
        $approvedCount = Review::where('status', 'approved')->count();
        $rejectedCount = Review::where('status', 'rejected')->count();
        
        return view('admin.reviews.index', compact('reviews', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }
    
    /**
     * Admin: Approve review
     */
    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['status' => 'approved']);
        
        return redirect()->back()->with('success', 'Review berhasil disetujui.');
    }
    
    /**
     * Admin: Reject review
     */
    public function reject($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['status' => 'rejected']);
        
        return redirect()->back()->with('success', 'Review ditolak.');
    }
}