<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Tampilkan semua produk (halaman produk)
     */
    public function index()
    {
        try {
            $products = Product::where('is_active', true)->get();
            return view('shop.products', compact('products'));
        } catch (\Exception $e) {
            Log::error('Error loading products: ' . $e->getMessage());
            return view('shop.products', ['products' => collect([])]);
        }
    }
    
    /**
     * Tampilkan halaman utama (tanpa produk)
     */
    public function home()
    {
        return view('shop.index');
    }

    /**
     * Admin: Lihat semua produk
     */
    public function adminIndex()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Admin: Form tambah produk
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Admin: Simpan produk baru
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'category' => 'required|string|max:100',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
            ]);

            $product = new Product($request->except('image'));
            
            if ($request->hasFile('image')) {
                $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
                $request->image->move(public_path('images/products'), $imageName);
                $product->image = 'images/products/' . $imageName;
            }
            
            $product->save();
            
            return redirect()->route('admin.products')->with('success', 'Produk berhasil ditambahkan!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan produk: ' . $e->getMessage());
        }
    }

    /**
     * Admin: Form edit produk
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Admin: Update produk - DIPERBAIKI
     */
    public function update(Request $request, Product $product)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'category' => 'required|string|max:100',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
            ]);

            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->category = $request->category;
            $product->is_active = $request->has('is_active') ? 1 : 0;

            // Upload gambar baru jika ada
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($product->image && file_exists(public_path($product->image))) {
                    unlink(public_path($product->image));
                }
                
                $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
                $request->image->move(public_path('images/products'), $imageName);
                $product->image = 'images/products/' . $imageName;
            }

            $product->save();

            return redirect()->route('admin.products')->with('success', 'Produk berhasil diupdate!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update produk: ' . $e->getMessage());
        }
    }

    /**
     * Admin: Hapus produk
     */
    public function destroy(Product $product)
    {
        try {
            // Hapus gambar jika ada
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            
            $product->delete();
            return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal hapus produk: ' . $e->getMessage());
        }
    }
    
    /**
     * Admin: Update stok produk
     */
    public function updateStock(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->stock = $request->stock;
            $product->save();
            
            return redirect()->back()->with('success', 'Stok berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update stok!');
        }
    }
    
    /**
     * Toggle status produk (aktif/nonaktif)
     */
    public function toggleStatus(Product $product)
    {
        $product->is_active = !$product->is_active;
        $product->save();
        
        $status = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Produk berhasil {$status}");
    }
    
    /**
     * Menampilkan form dine in untuk produk tertentu
     */
    public function showDineInForm($id)
    {
        $product = Product::findOrFail($id);
        return view('shop.dinein', compact('product'));
    }
    
    /**
     * Tampilkan detail produk - DENGAN REVIEW
     */
    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Ambil review yang sudah disetujui
            $reviews = Review::where('product_id', $id)
                ->where('status', 'approved')
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $averageRating = Review::where('product_id', $id)
                ->where('status', 'approved')
                ->avg('rating') ?? 0;
            
            $totalReviews = $reviews->count();
            
            // Rekomendasi produk terkait (berdasarkan kategori yang sama)
            $relatedProducts = Product::where('category', $product->category)
                ->where('id', '!=', $product->id)
                ->where('is_active', true)
                ->limit(4)
                ->get();
            
            // Cek apakah user sudah pernah review
            $hasReviewed = false;
            if (auth()->check()) {
                $hasReviewed = Review::where('user_id', auth()->id())
                    ->where('product_id', $id)
                    ->exists();
            }
            
            return view('shop.product-detail', compact(
                'product', 
                'relatedProducts', 
                'reviews', 
                'averageRating', 
                'totalReviews', 
                'hasReviewed'
            ));
        } catch (\Exception $e) {
            Log::error('Product detail error: ' . $e->getMessage());
            return redirect()->route('shop.products')->with('error', 'Produk tidak ditemukan');
        }
    }
    /**
 * Halaman Premium Quality
 */
public function premiumQuality()
{
    return view('shop.premium-quality');
}
}