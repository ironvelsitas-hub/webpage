<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\DineInorder;
use App\Models\Product;
use App\Models\User;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Salary;
use App\Models\RawMaterial;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;

class DashboardController extends Controller
{
    /**
     * Owner Dashboard - Ringkasan Bisnis Terintegrasi (Order + Dine In)
     */
    public function index()
    {
        // ========== DATA PENJUALAN ORDER (Delivery) ==========
        // Hari Ini
        $todayOrderSales = Order::whereDate('created_at', Carbon::today())->sum('total_amount');
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();
        
        // Minggu Ini
        $weekOrderSales = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total_amount');
        $weekOrders = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        
        // Bulan Ini
        $monthOrderSales = Order::whereMonth('created_at', Carbon::now()->month)->sum('total_amount');
        $monthOrders = Order::whereMonth('created_at', Carbon::now()->month)->count();
        
        // Tahun Ini
        $yearOrderSales = Order::whereYear('created_at', Carbon::now()->year)->sum('total_amount');
        $yearOrders = Order::whereYear('created_at', Carbon::now()->year)->count();
        
        // ========== DATA PENJUALAN DINE IN ==========
        // Hari Ini
        $todayDineInSales = DineInorder::whereDate('created_at', Carbon::today())->sum('total_amount');
        $todayDineIns = DineInorder::whereDate('created_at', Carbon::today())->count();
        
        // Minggu Ini
        $weekDineInSales = DineInorder::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total_amount');
        $weekDineIns = DineInorder::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        
        // Bulan Ini
        $monthDineInSales = DineInorder::whereMonth('created_at', Carbon::now()->month)->sum('total_amount');
        $monthDineIns = DineInorder::whereMonth('created_at', Carbon::now()->month)->count();
        
        // Tahun Ini
        $yearDineInSales = DineInorder::whereYear('created_at', Carbon::now()->year)->sum('total_amount');
        $yearDineIns = DineInorder::whereYear('created_at', Carbon::now()->year)->count();
        
        // ========== TOTAL KESELURUHAN (Order + Dine In) ==========
        $todayTotalSales = $todayOrderSales + $todayDineInSales;
        $todayTotalOrders = $todayOrders + $todayDineIns;
        
        $weekTotalSales = $weekOrderSales + $weekDineInSales;
        $weekTotalOrders = $weekOrders + $weekDineIns;
        
        $monthTotalSales = $monthOrderSales + $monthDineInSales;
        $monthTotalOrders = $monthOrders + $monthDineIns;
        
        $yearTotalSales = $yearOrderSales + $yearDineInSales;
        $yearTotalOrders = $yearOrders + $yearDineIns;
        
        // ========== PRODUK TERLARIS (Dari Order & Dine In) ==========
        $topProducts = $this->getTopProductsCombined();
        
        // ========== GRAFIK PENJUALAN 7 HARI TERAKHIR (Gabungan) ==========
        $salesChart = $this->getCombinedSalesChart();
        
        // ========== STATISTIK STATUS ==========
        // Status Order
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        
        // Status Dine In
        $pendingDineIns = DineInorder::where('status', 'pending')->count();
        $completedDineIns = DineInorder::where('status', 'completed')->count();
        $cancelledDineIns = DineInorder::where('status', 'cancelled')->count();
        
        // Total keseluruhan status
        $totalPending = $pendingOrders + $pendingDineIns;
        $totalProcessing = $processingOrders;
        $totalCompleted = $completedOrders + $completedDineIns;
        $totalCancelled = $cancelledOrders + $cancelledDineIns;
        
        // ========== METODE PEMBAYARAN TERPOPULER ==========
        $paymentMethods = $this->getCombinedPaymentMethodStats();
        
        // ========== PELANGGAN TERAKTIF ==========
        $topCustomers = $this->getTopCustomersCombined();
        
        // ========== STATISTIK CEPAT ==========
        $activeDiscounts = Discount::where('is_active', true)
            ->where('end_date', '>=', Carbon::now())
            ->count();
        
        $totalStaff = User::where('role', 'admin')->count();
        
        // ========== PESANAN TERBARU (Order & Dine In) ==========
        $recentOrders = $this->getRecentOrdersCombined();
        
        // ========== PERSENTASE PERUBAHAN ==========
        $yesterdayTotalSales = Order::whereDate('created_at', Carbon::yesterday())->sum('total_amount') + 
                            DineInorder::whereDate('created_at', Carbon::yesterday())->sum('total_amount');
        
        $salesGrowth = $yesterdayTotalSales > 0 
            ? (($todayTotalSales - $yesterdayTotalSales) / $yesterdayTotalSales) * 100 
            : ($todayTotalSales > 0 ? 100 : 0);
        
        // ========== STATISTIK PENGGUNAAN DISKON ==========
        $totalDiscountUsed = Discount::sum('used_count');
        $totalDiscountAmount = Order::sum('discount_amount');
        
        // Top 5 Diskon Paling Banyak Digunakan (dari tabel discounts)
        $topDiscounts = Discount::where('used_count', '>', 0)
            ->orderBy('used_count', 'desc')
            ->limit(5)
            ->get();
        
        // Top 5 Diskon dari orders (alternatif)
        $topDiscountsFromOrders = Order::select('discount_code', DB::raw('count(*) as total_used'), DB::raw('sum(discount_amount) as total_amount'))
            ->whereNotNull('discount_code')
            ->groupBy('discount_code')
            ->orderBy('total_used', 'desc')
            ->limit(5)
            ->get();
        
        return view('owner.dashboard', compact(
            'todayTotalSales', 'todayTotalOrders',
            'weekTotalSales', 'weekTotalOrders',
            'monthTotalSales', 'monthTotalOrders',
            'yearTotalSales', 'yearTotalOrders',
            'todayOrderSales', 'todayOrders',
            'weekOrderSales', 'weekOrders',
            'monthOrderSales', 'monthOrders',
            'yearOrderSales', 'yearOrders',
            'todayDineInSales', 'todayDineIns',
            'weekDineInSales', 'weekDineIns',
            'monthDineInSales', 'monthDineIns',
            'yearDineInSales', 'yearDineIns',
            'topProducts', 'salesChart',
            'pendingOrders', 'processingOrders', 'completedOrders', 'cancelledOrders',
            'pendingDineIns', 'completedDineIns', 'cancelledDineIns',
            'totalPending', 'totalProcessing', 'totalCompleted', 'totalCancelled',
            'paymentMethods', 'topCustomers', 'activeDiscounts', 'totalStaff', 'recentOrders',
            'salesGrowth',
            'totalDiscountUsed', 'totalDiscountAmount', 'topDiscounts', 'topDiscountsFromOrders'
        ));
    }
    
    /**
     * Halaman Semua Pesanan (Delivery + Dine In)
     */
    public function orders()
    {
        // Ambil data dari Order (Delivery)
        $deliveryOrders = Order::select(
            'id',
            'order_number',
            'customer_name',
            'total_amount',
            'status',
            'payment_status',
            'created_at',
            DB::raw("'Delivery' as order_type")
        )->get();
        
        // Ambil data dari Dine In
        $dineInOrders = DineInorder::select(
            'id',
            'order_number',
            'customer_name',
            'total_amount',
            'status',
            'payment_status',
            'created_at',
            DB::raw("'Dine In' as order_type")
        )->get();
        
        // Gabungkan kedua collection
        $allOrders = $deliveryOrders->concat($dineInOrders);
        
        // Urutkan berdasarkan created_at terbaru
        $allOrders = $allOrders->sortByDesc('created_at');
        
        // Paginate manual
        $perPage = 20;
        $currentPage = request()->get('page', 1);
        $currentItems = $allOrders->slice(($currentPage - 1) * $perPage, $perPage);
        $orders = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $allOrders->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        // Statistik
        $totalOrders = $allOrders->count();
        $pendingOrders = $allOrders->where('status', 'pending')->count();
        $processingOrders = $allOrders->where('status', 'processing')->count();
        $completedOrders = $allOrders->where('status', 'completed')->count();
        $cancelledOrders = $allOrders->where('status', 'cancelled')->count();
        
        return view('owner.orders', compact(
            'orders', 'totalOrders', 'pendingOrders', 
            'processingOrders', 'completedOrders', 'cancelledOrders'
        ));
    }
    
    /**
     * Halaman Detail Pesanan (Menentukan dari jenis order)
     */
    public function orderDetail($id, $type = null)
    {
        if ($type == 'dinein' || request()->get('type') == 'dinein') {
            $order = DineInorder::findOrFail($id);
            $orderType = 'Dine In';
        } else {
            $order = Order::findOrFail($id);
            $orderType = 'Delivery';
        }
        
        return view('owner.order-detail', compact('order', 'orderType'));
    }
    
    /**
     * Halaman Manajemen Produk
     */
    public function products()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(20);
        return view('owner.products', compact('products'));
    }
    
    /**
     * Halaman Manajemen Stok
     */
    public function stock()
    {
        $products = Product::orderBy('name')->get();
        $totalProducts = $products->count();
        $lowStockProducts = $products->where('stock', '<=', 10)->count();
        $outOfStock = $products->where('stock', '<=', 0)->count();
        $totalStockValue = $products->sum(function($product) {
            return $product->stock * $product->price;
        });
        
        return view('owner.stock', compact('products', 'totalProducts', 'lowStockProducts', 'outOfStock', 'totalStockValue'));
    }
    
    /**
     * Tambah Stok Produk
     */
    public function addStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);
        
        $product = Product::findOrFail($request->product_id);
        $product->stock += $request->quantity;
        $product->save();
        
        return redirect()->back()->with('success', 'Stok ' . $product->name . ' berhasil ditambahkan ' . $request->quantity . ' unit');
    }
    
    /**
     * Halaman Manajemen Karyawan
     */
    public function staff()
    {
        // Ambil hanya user dengan role selain 'customer' dan 'owner'
        $staff = User::whereNotIn('role', ['customer', 'owner'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalStaff = $staff->count();
        $activeStaff = $staff->where('is_active', true)->count();
        $pendingStaff = $staff->where('is_active', false)->count();
        
        return view('owner.staff', compact('staff', 'totalStaff', 'activeStaff', 'pendingStaff'));
    }
    
    /**
     * Simpan Karyawan Baru
     */
    public function storeStaff(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,staff,kasir,barista',
            'phone' => 'nullable|string|max:20'
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'is_active' => true
        ]);
        
        return redirect()->back()->with('success', 'Karyawan ' . $user->name . ' berhasil ditambahkan');
    }
    
    /**
     * Update Karyawan
     */
    public function updateStaff(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        if ($request->role == 'customer') {
            return redirect()->back()->with('error', 'Tidak dapat mengubah karyawan menjadi customer');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,staff,kasir,barista',
            'is_active' => 'required|in:0,1'
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->is_active = $request->is_active;
        
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        
        $user->save();
        
        return redirect()->back()->with('success', 'Karyawan ' . $user->name . ' berhasil diupdate');
    }
    
    /**
     * Hapus Karyawan
     */
    public function deleteStaff($id)
    {
        $user = User::findOrFail($id);
        $name = $user->name;
        
        if ($user->role == 'owner') {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun owner');
        }
        
        if ($user->role == 'customer') {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun customer dari halaman ini');
        }
        
        $user->delete();
        
        return redirect()->back()->with('success', 'Karyawan ' . $name . ' berhasil dihapus');
    }
    
/**
 * Halaman Manajemen Promo
 */
public function promos()
{
    // Ambil semua promo tanpa terkecuali
    $promos = Discount::orderBy('created_at', 'desc')->get();
    
    // Hitung promo aktif (masih dalam masa berlaku)
    $activePromos = Discount::where('is_active', true)
        ->where('end_date', '>=', Carbon::now())
        ->count();
    
    $totalPromos = $promos->count();
    
    // Total penggunaan promo
    $totalUsed = $promos->sum('used_count');
    
    // Total diskon yang diberikan
    $totalDiscountGiven = 0;
    foreach ($promos as $promo) {
        if ($promo->type == 'percentage') {
            $totalDiscountGiven += ($promo->used_count * $promo->value);
        } else {
            $totalDiscountGiven += ($promo->used_count * $promo->value);
        }
    }
     // Tambahkan log untuk debugging
    \Log::info('Loading promos page');
    
    $promos = Discount::orderBy('created_at', 'desc')->get();
    
    \Log::info('Total promos found: ' . $promos->count());
    
    return view('owner.promos', compact(
        'promos', 
        'activePromos', 
        'totalPromos', 
        'totalUsed', 
        'totalDiscountGiven'
    ));
    
}    /**
 * Halaman Edit Promo
 */
public function editPromo($id)
{
    $promo = Discount::findOrFail($id);
    return view('owner.promos-edit', compact('promo'));
}

/**
 * Update Promo
 */
/**
 * Update Promo
 */
public function updatePromo(Request $request, $id)
{
    try {
        $promo = Discount::findOrFail($id);
        
        $request->validate([
            'code' => 'required|string|max:50|unique:discounts,code,' . $id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string',
            'is_active' => 'required|in:0,1' // ← TAMBAHKAN VALIDASI INI
        ]);
        
        $promo->update([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'type' => $request->type,
            'value' => $request->value,
            'min_purchase' => $request->min_purchase,
            'max_discount' => $request->max_discount,
            'usage_limit' => $request->usage_limit,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'is_active' => $request->is_active // ← PASTIKAN INI ADA
        ]);
        
        $statusText = $request->is_active == 1 ? 'Aktif' : 'Tidak Aktif';
        
        return redirect()->route('owner.promos')->with('notification', [
            'type' => 'success',
            'title' => '✅ Promo Diperbarui!',
            'message' => 'Promo ' . $promo->code . ' berhasil diperbarui',
            'detail' => "Status: {$statusText} | " . $promo->name
        ]);
        
    } catch (\Exception $e) {
        return redirect()->back()->with('notification', [
            'type' => 'error',
            'title' => '❌ Gagal!',
            'message' => 'Gagal mengupdate promo',
            'detail' => $e->getMessage()
        ]);
    }
}/**

 * Get Promo Data for Edit (AJAX)
 */
public function getPromo($id)
{
    try {
        $promo = Discount::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'promo' => $promo
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Promo tidak ditemukan: ' . $e->getMessage()
        ]);
    }
} 
/**
/**
 * Simpan Promo Baru
 */
public function storePromo(Request $request)
{
    try {
        \Log::info('Store Promo - Request Data:', $request->all());
        
        $request->validate([
            'code' => 'required|string|max:50|unique:discounts,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string',
            'is_active' => 'nullable|in:0,1'
        ]);
        
        $promo = Discount::create([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'type' => $request->type,
            'value' => $request->value,
            'min_purchase' => $request->min_purchase,
            'max_discount' => $request->max_discount,
            'usage_limit' => $request->usage_limit,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'is_active' => $request->is_active ?? 1,
            'used_count' => 0
        ]);
        
        \Log::info('Store Promo - Success, ID: ' . $promo->id);
        
        // Redirect dengan session success biasa (tanpa notification array)
        return redirect()->route('owner.promos')->with('success', '✅ Promo ' . $promo->code . ' berhasil ditambahkan');
        
    } catch (\Exception $e) {
        \Log::error('Store Promo - Error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal menambahkan promo: ' . $e->getMessage());
    }
}
/**
 * Halaman Laporan Keuangan (Gabungan Delivery + Dine In)
 */
public function financial()
{
    // ========== PENDAPATAN ==========
    $deliveryRevenue = Order::sum('total_amount');
    $dineInRevenue = DineInorder::sum('total_amount');
    $totalRevenue = $deliveryRevenue + $dineInRevenue;
    
    // ========== PENGELUARAN ==========
    
    // 1. TOTAL GAJI KARYAWAN REAL (dari tabel salaries)
    $totalGajiKaryawan = Salary::sum('total_salary');
    
    // 2. TOTAL PEMBELIAN BAHAN BAKU REAL (dari tabel purchase_orders)
    $totalPembelianBahanBaku = PurchaseOrder::where('status', 'received')->sum('total_amount');
    
    // 3. TOTAL POTONGAN PROMO & VOUCHER (dari tabel orders) - PASTIKAN INI
    $totalPotonganPromo = Order::sum('discount_amount');
    
    // ⭐ DEBUG: Log untuk mengecek nilai
    \Log::info('Total Potongan Promo: ' . $totalPotonganPromo);
    
    // Total pengeluaran
    $totalExpense = $totalGajiKaryawan + $totalPembelianBahanBaku + $totalPotonganPromo;
    
    // Laba bersih
    $netProfit = $totalRevenue - $totalExpense;
    
    // Persentase masing-masing pengeluaran
    $percentageGaji = $totalExpense > 0 ? ($totalGajiKaryawan / $totalExpense) * 100 : 0;
    $percentageBahanBaku = $totalExpense > 0 ? ($totalPembelianBahanBaku / $totalExpense) * 100 : 0;
    $percentagePromo = $totalExpense > 0 ? ($totalPotonganPromo / $totalExpense) * 100 : 0;
    
    // ========== DATA UNTUK GRAFIK ==========
    $monthlyRevenue = $this->getMonthlyRevenue();
    $monthlyComparison = $this->getMonthlyComparison();
    
    $deliveryPercentage = $totalRevenue > 0 ? ($deliveryRevenue / $totalRevenue) * 100 : 0;
    $dineInPercentage = $totalRevenue > 0 ? ($dineInRevenue / $totalRevenue) * 100 : 0;
    
    $totalOrders = Order::count() + DineInorder::count();
    $totalDeliveryOrders = Order::count();
    $totalDineInOrders = DineInorder::count();
    $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
    
    $profitMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;
    
    return view('owner.financial', compact(
        'totalRevenue', 'totalExpense', 'netProfit',
        'deliveryRevenue', 'dineInRevenue',
        'deliveryPercentage', 'dineInPercentage',
        'monthlyRevenue', 'monthlyComparison',
        'totalOrders', 'totalDeliveryOrders', 'totalDineInOrders', 'averageOrderValue',
        'totalGajiKaryawan', 'totalPembelianBahanBaku', 'totalPotonganPromo',
        'percentageGaji', 'percentageBahanBaku', 'percentagePromo', 'profitMargin'
    ));
}
/**
     * Ambil data pendapatan per bulan (gabungan)
     */
    private function getMonthlyRevenue()
    {
        $monthlyRevenue = [];
        for ($i = 1; $i <= 12; $i++) {
            $deliveryRevenue = Order::whereMonth('created_at', $i)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('total_amount');
            
            $dineInRevenue = DineInorder::whereMonth('created_at', $i)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('total_amount');
            
            $monthlyRevenue[\Carbon\Carbon::create()->month($i)->format('M')] = [
                'delivery' => $deliveryRevenue,
                'dinein' => $dineInRevenue,
                'total' => $deliveryRevenue + $dineInRevenue
            ];
        }
        return $monthlyRevenue;
    }
    
    /**
     * Ambil data pendapatan per hari (30 hari terakhir)
     */
    private function getDailyRevenue()
    {
        $dailyRevenue = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            $deliveryRevenue = Order::whereDate('created_at', $date)->sum('total_amount');
            $dineInRevenue = DineInorder::whereDate('created_at', $date)->sum('total_amount');
            
            $dailyRevenue[] = [
                'date' => $date->format('d/m'),
                'delivery' => $deliveryRevenue,
                'dinein' => $dineInRevenue,
                'total' => $deliveryRevenue + $dineInRevenue
            ];
        }
        return $dailyRevenue;
    }
    
    /**
     * Ambil data perbandingan Delivery vs Dine In per bulan
     */
    private function getMonthlyComparison()
    {
        $comparison = [];
        for ($i = 1; $i <= 12; $i++) {
            $deliveryRevenue = Order::whereMonth('created_at', $i)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('total_amount');
            
            $dineInRevenue = DineInorder::whereMonth('created_at', $i)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('total_amount');
            
            $comparison[] = [
                'month' => \Carbon\Carbon::create()->month($i)->format('M'),
                'delivery' => $deliveryRevenue,
                'dinein' => $dineInRevenue
            ];
        }
        return $comparison;
    }
    
    /**
     * Halaman Dine In Orders
     */
    public function dinein()
    {
        $dineIns = DineInorder::orderBy('created_at', 'desc')->paginate(20);
        return view('owner.dinein', compact('dineIns'));
    }
    
    /**
     * Halaman Laporan Lainnya
     */
    public function reports()
    {
        return view('owner.reports');
    }
    
    /**
     * Ambil 5 produk terlaris dari Order dan Dine In
     */
    private function getTopProductsCombined()
    {
        $combined = [];
        
        try {
            if (DB::getSchemaBuilder()->hasTable('order_items')) {
                $orderProducts = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->select(
                        'order_items.product_name',
                        DB::raw('SUM(order_items.quantity) as total_quantity'),
                        DB::raw('SUM(order_items.price * order_items.quantity) as total_sales')
                    )
                    ->whereMonth('orders.created_at', Carbon::now()->month)
                    ->whereYear('orders.created_at', Carbon::now()->year)
                    ->groupBy('order_items.product_name')
                    ->get();
                
                foreach ($orderProducts as $product) {
                    $combined[$product->product_name] = [
                        'product_name' => $product->product_name,
                        'total_quantity' => (int)$product->total_quantity,
                        'total_sales' => (float)$product->total_sales
                    ];
                }
            }
        } catch (\Exception $e) {}
        
        try {
            if (DB::getSchemaBuilder()->hasTable('dine_in_items')) {
                $dineInProducts = DB::table('dine_in_items')
                    ->join('dine_ins', 'dine_in_items.dine_in_id', '=', 'dine_ins.id')
                    ->select(
                        'dine_in_items.product_name',
                        DB::raw('SUM(dine_in_items.quantity) as total_quantity'),
                        DB::raw('SUM(dine_in_items.price * dine_in_items.quantity) as total_sales')
                    )
                    ->whereMonth('dine_ins.created_at', Carbon::now()->month)
                    ->whereYear('dine_ins.created_at', Carbon::now()->year)
                    ->groupBy('dine_in_items.product_name')
                    ->get();
                
                foreach ($dineInProducts as $product) {
                    if (isset($combined[$product->product_name])) {
                        $combined[$product->product_name]['total_quantity'] += (int)$product->total_quantity;
                        $combined[$product->product_name]['total_sales'] += (float)$product->total_sales;
                    } else {
                        $combined[$product->product_name] = [
                            'product_name' => $product->product_name,
                            'total_quantity' => (int)$product->total_quantity,
                            'total_sales' => (float)$product->total_sales
                        ];
                    }
                }
            }
        } catch (\Exception $e) {}
        
        if (empty($combined)) {
            $combined = $this->getTopProductsFromJsonFallback();
        }
        
        $products = array_values($combined);
        usort($products, function($a, $b) {
            return $b['total_quantity'] - $a['total_quantity'];
        });
        
        return array_slice($products, 0, 5);
    }
    
    /**
     * Fallback: Ambil produk terlaris dari JSON items di tabel orders
     */
    private function getTopProductsFromJsonFallback()
    {
        $productSales = [];
        
        try {
            $orders = Order::whereMonth('created_at', Carbon::now()->month)
                ->whereNotNull('items')
                ->get();
            
            foreach ($orders as $order) {
                $items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
                if (is_array($items)) {
                    foreach ($items as $item) {
                        $productName = $item['name'] ?? $item['product_name'] ?? 'Unknown';
                        $quantity = $item['quantity'] ?? 1;
                        $subtotal = $item['subtotal'] ?? ($item['price'] * $quantity ?? 0);
                        
                        if (!isset($productSales[$productName])) {
                            $productSales[$productName] = [
                                'product_name' => $productName,
                                'total_quantity' => 0,
                                'total_sales' => 0
                            ];
                        }
                        $productSales[$productName]['total_quantity'] += $quantity;
                        $productSales[$productName]['total_sales'] += $subtotal;
                    }
                }
            }
        } catch (\Exception $e) {}
        
        return $productSales;
    }
    
    /**
     * Ambil data grafik penjualan gabungan 7 hari terakhir
     */
    private function getCombinedSalesChart()
    {
        $sales = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            $orderTotal = Order::whereDate('created_at', $date)->sum('total_amount');
            $dineInTotal = DineInorder::whereDate('created_at', $date)->sum('total_amount');
            $total = $orderTotal + $dineInTotal;
            
            $orderCount = Order::whereDate('created_at', $date)->count();
            $dineInCount = DineInorder::whereDate('created_at', $date)->count();
            
            $sales[] = [
                'date' => $date->format('d/m'),
                'order_sales' => (float)$orderTotal,
                'dinein_sales' => (float)$dineInTotal,
                'total_sales' => (float)$total,
                'order_count' => $orderCount,
                'dinein_count' => $dineInCount,
                'total_orders' => $orderCount + $dineInCount
            ];
        }
        return $sales;
    }
    
    /**
     * Ambil statistik metode pembayaran gabungan
     */
    private function getCombinedPaymentMethodStats()
    {
        $combined = [];
        
        try {
            $orderMethods = Order::select('payment_method', DB::raw('count(*) as total'), DB::raw('sum(total_amount) as amount'))
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->whereNotNull('payment_method')
                ->groupBy('payment_method')
                ->get();
            
            foreach ($orderMethods as $method) {
                $combined[$method->payment_method] = [
                    'payment_method' => $method->payment_method,
                    'total' => $method->total,
                    'amount' => $method->amount
                ];
            }
        } catch (\Exception $e) {}
        
        try {
            $dineInMethods = DineInorder::select('payment_method', DB::raw('count(*) as total'), DB::raw('sum(total_amount) as amount'))
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->whereNotNull('payment_method')
                ->groupBy('payment_method')
                ->get();
            
            foreach ($dineInMethods as $method) {
                if (isset($combined[$method->payment_method])) {
                    $combined[$method->payment_method]['total'] += $method->total;
                    $combined[$method->payment_method]['amount'] += $method->amount;
                } else {
                    $combined[$method->payment_method] = [
                        'payment_method' => $method->payment_method,
                        'total' => $method->total,
                        'amount' => $method->amount
                    ];
                }
            }
        } catch (\Exception $e) {}
        
        return collect(array_values($combined));
    }
    
    /**
     * Ambil pelanggan teraktif gabungan
     */
    private function getTopCustomersCombined()
    {
        $combined = [];
        
        try {
            $orderCustomers = Order::select('customer_name', 'customer_email', DB::raw('count(*) as total_orders'), DB::raw('sum(total_amount) as total_spent'))
                ->whereNotNull('customer_name')
                ->groupBy('customer_name', 'customer_email')
                ->get();
            
            foreach ($orderCustomers as $customer) {
                $key = $customer->customer_email ?? $customer->customer_name;
                $combined[$key] = [
                    'customer_name' => $customer->customer_name,
                    'customer_email' => $customer->customer_email,
                    'total_orders' => $customer->total_orders,
                    'total_spent' => $customer->total_spent
                ];
            }
        } catch (\Exception $e) {}
        
        try {
            $dineInCustomers = DineInorder::select('customer_name', 'customer_phone as customer_email', DB::raw('count(*) as total_orders'), DB::raw('sum(total_amount) as total_spent'))
                ->whereNotNull('customer_name')
                ->groupBy('customer_name', 'customer_email')
                ->get();
            
            foreach ($dineInCustomers as $customer) {
                $key = $customer->customer_email ?? $customer->customer_name;
                if (isset($combined[$key])) {
                    $combined[$key]['total_orders'] += $customer->total_orders;
                    $combined[$key]['total_spent'] += $customer->total_spent;
                } else {
                    $combined[$key] = [
                        'customer_name' => $customer->customer_name,
                        'customer_email' => $customer->customer_email,
                        'total_orders' => $customer->total_orders,
                        'total_spent' => $customer->total_spent
                    ];
                }
            }
        } catch (\Exception $e) {}
        
        $customers = array_values($combined);
        usort($customers, function($a, $b) {
            return $b['total_spent'] - $a['total_spent'];
        });
        
        return array_slice($customers, 0, 5);
    }
    
    /**
     * Ambil pesanan terbaru gabungan (Order & Dine In)
     */
    private function getRecentOrdersCombined()
    {
        $combined = collect();
        
        try {
            $orders = Order::select(
                'id', 
                'order_number as number', 
                'customer_name', 
                'total_amount', 
                'status', 
                'payment_status',
                'created_at',
                DB::raw("'Delivery' as type")
            )->orderBy('created_at', 'desc')->limit(5)->get();
            
            $combined = $combined->concat($orders);
        } catch (\Exception $e) {}
        
        try {
            $dineIns = DineInorder::select(
                'id', 
                'order_number as number', 
                'customer_name', 
                'total_amount', 
                'status', 
                'payment_status',
                'created_at',
                DB::raw("'Dine In' as type")
            )->orderBy('created_at', 'desc')->limit(5)->get();
            
            $combined = $combined->concat($dineIns);
        } catch (\Exception $e) {}
        
        return $combined->sortByDesc('created_at')->take(10);
    }
    
    /**
     * Export Laporan Excel (Order + Dine In)
     */
    public function exportReport(Request $request)
    {
        $period = $request->get('period', 'month');
        
        switch ($period) {
            case 'today':
                $orders = Order::whereDate('created_at', Carbon::today())->get();
                $dineIns = DineInorder::whereDate('created_at', Carbon::today())->get();
                break;
            case 'week':
                $orders = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
                $dineIns = DineInorder::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
                break;
            case 'month':
                $orders = Order::whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->get();
                $dineIns = DineInorder::whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->get();
                break;
            case 'year':
                $orders = Order::whereYear('created_at', Carbon::now()->year)->get();
                $dineIns = DineInorder::whereYear('created_at', Carbon::now()->year)->get();
                break;
            default:
                $orders = Order::all();
                $dineIns = DineInorder::all();
        }
        
        $filename = 'laporan_penjualan_' . Carbon::now()->format('Ymd_His') . '.csv';
        $handle = fopen('php://temp', 'w');
        
        fputcsv($handle, ['No', 'Tipe', 'Order Number', 'Customer', 'Total', 'Status', 'Payment', 'Date']);
        
        $index = 1;
        foreach ($orders as $order) {
            fputcsv($handle, [
                $index++,
                'Delivery',
                $order->order_number ?? '-',
                $order->customer_name ?? 'Guest',
                'Rp ' . number_format($order->total_amount ?? 0, 0, ',', '.'),
                $order->status ?? '-',
                $order->payment_status ?? '-',
                $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-'
            ]);
        }
        
        foreach ($dineIns as $dineIn) {
            fputcsv($handle, [
                $index++,
                'Dine In',
                $dineIn->order_number ?? '-',
                $dineIn->customer_name ?? 'Guest',
                'Rp ' . number_format($dineIn->total_amount ?? 0, 0, ',', '.'),
                $dineIn->status ?? '-',
                $dineIn->payment_status ?? '-',
                $dineIn->created_at ? $dineIn->created_at->format('d/m/Y H:i') : '-'
            ]);
        }
        
        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);
        
        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
    
    /**
     * Halaman Manajemen Gaji Karyawan
     */
    public function salaries()
    {
        $employees = User::whereNotIn('role', ['customer', 'owner'])
            ->orderBy('name')
            ->get();
        
        $currentMonth = request()->get('month', Carbon::now()->month);
        $currentYear = request()->get('year', Carbon::now()->year);
        
        $salaries = Salary::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->with('user')
            ->get()
            ->keyBy('user_id');
        
        $totalSalaryThisMonth = Salary::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->sum('total_salary');
        
        $paidSalaryThisMonth = Salary::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->where('status', 'paid')
            ->sum('total_salary');
        
        $pendingSalaryThisMonth = Salary::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->where('status', 'pending')
            ->sum('total_salary');
        
        $totalSalaryThisYear = Salary::where('year', $currentYear)->sum('total_salary');
        $monthlySalaryChart = $this->getMonthlySalaryChart($currentYear);
        
        return view('owner.salaries', compact(
            'employees', 'salaries', 'currentMonth', 'currentYear',
            'totalSalaryThisMonth', 'paidSalaryThisMonth', 'pendingSalaryThisMonth',
            'totalSalaryThisYear', 'monthlySalaryChart'
        ));
    }
    
    /**
     * Simpan / Update Gaji Karyawan
     */
    public function storeSalary(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2030',
            'base_salary' => 'required|numeric|min:0',
            'allowance' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deduction' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);
        
        $totalSalary = $request->base_salary + ($request->allowance ?? 0) + ($request->bonus ?? 0) - ($request->deduction ?? 0);
        
        Salary::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'month' => $request->month,
                'year' => $request->year
            ],
            [
                'base_salary' => $request->base_salary,
                'allowance' => $request->allowance ?? 0,
                'bonus' => $request->bonus ?? 0,
                'deduction' => $request->deduction ?? 0,
                'total_salary' => $totalSalary,
                'notes' => $request->notes,
                'status' => 'pending'
            ]
        );
        
        return redirect()->back()->with('success', 'Data gaji berhasil disimpan');
    }
    
    /**
     * Update Status Gaji (Pending/Paid)
     */
    public function updateSalaryStatus(Request $request, $id)
    {
        try {
            $salary = Salary::findOrFail($id);
            
            $request->validate([
                'status' => 'required|in:pending,paid'
            ]);
            
            $oldStatus = $salary->status;
            $salary->status = $request->status;
            
            if ($request->status == 'paid') {
                $salary->payment_date = Carbon::now();
            } else {
                $salary->payment_date = null;
            }
            
            $salary->save();
            
            return redirect()->back()->with('success', 'Status gaji ' . $salary->user->name . ' berhasil diubah dari ' . $oldStatus . ' menjadi ' . $request->status);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate status gaji: ' . $e->getMessage());
        }
    }
    
    /**
     * Hapus Data Gaji
     */
    public function deleteSalary($id)
    {
        try {
            $salary = Salary::findOrFail($id);
            $employeeName = $salary->user->name;
            $salary->delete();
            
            return redirect()->back()->with('success', 'Data gaji ' . $employeeName . ' berhasil dihapus');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data gaji: ' . $e->getMessage());
        }
    }
    
    /**
     * Data grafik gaji per bulan
     */
    private function getMonthlySalaryChart($year)
    {
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $total = Salary::where('month', $i)
                ->where('year', $year)
                ->sum('total_salary');
            $monthlyData[] = $total;
        }
        return $monthlyData;
    }
    
    /**
     * Laporan Gaji Karyawan (Export)
     */
    public function exportSalaryReport(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        
        $salaries = Salary::where('month', $month)
            ->where('year', $year)
            ->with('user')
            ->get();
        
        $filename = 'laporan_gaji_' . $year . '_' . $month . '.csv';
        $handle = fopen('php://temp', 'w');
        
        fputcsv($handle, ['No', 'Nama Karyawan', 'Role', 'Gaji Pokok', 'Tunjangan', 'Bonus', 'Potongan', 'Total Gaji', 'Status', 'Tanggal Bayar']);
        
        foreach ($salaries as $index => $salary) {
            fputcsv($handle, [
                $index + 1,
                $salary->user->name,
                $salary->user->role,
                'Rp ' . number_format($salary->base_salary, 0, ',', '.'),
                'Rp ' . number_format($salary->allowance, 0, ',', '.'),
                'Rp ' . number_format($salary->bonus, 0, ',', '.'),
                'Rp ' . number_format($salary->deduction, 0, ',', '.'),
                'Rp ' . number_format($salary->total_salary, 0, ',', '.'),
                $salary->status == 'paid' ? 'Sudah Dibayar' : 'Belum Dibayar',
                $salary->payment_date ? $salary->payment_date->format('d/m/Y') : '-'
            ]);
        }
        
        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);
        
        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
    
    /**
     * Halaman Manajemen Bahan Baku
     */
    public function rawMaterials()
    {
        $materials = RawMaterial::orderBy('category')->orderBy('name')->get();
        
        $totalMaterials = $materials->count();
        $lowStockMaterials = $materials->where('stock', '<=', 'min_stock')->count();
        $outOfStock = $materials->where('stock', '<=', 0)->count();
        $totalStockValue = $materials->sum(function($item) {
            return $item->stock * $item->unit_price;
        });
        
        $categories = $materials->groupBy('category')->keys();
        
        return view('owner.raw-materials', compact(
            'materials', 'totalMaterials', 'lowStockMaterials', 
            'outOfStock', 'totalStockValue', 'categories'
        ));
    }
    
    /**
     * Simpan Bahan Baku Baru
     */
    public function storeRawMaterial(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string',
            'unit' => 'required|string',
            'unit_price' => 'required|numeric|min:0',
            'min_stock' => 'nullable|integer|min:0',
            'supplier' => 'nullable|string'
        ]);
        
        RawMaterial::create([
            'name' => $request->name,
            'category' => $request->category,
            'unit' => $request->unit,
            'unit_price' => $request->unit_price,
            'min_stock' => $request->min_stock ?? 10,
            'supplier' => $request->supplier,
            'description' => $request->description,
            'stock' => 0
        ]);
        
        return redirect()->back()->with('success', 'Bahan baku berhasil ditambahkan');
    }
    
    /**
     * Update Bahan Baku
     */
    public function updateRawMaterial(Request $request, $id)
    {
        try {
            $material = RawMaterial::findOrFail($id);
            
            $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'nullable|string',
                'unit' => 'required|string',
                'unit_price' => 'required|numeric|min:0',
                'min_stock' => 'nullable|integer|min:0',
                'supplier' => 'nullable|string',
                'stock' => 'nullable|integer|min:0'
            ]);
            
            $material->update([
                'name' => $request->name,
                'category' => $request->category,
                'unit' => $request->unit,
                'unit_price' => $request->unit_price,
                'min_stock' => $request->min_stock ?? 10,
                'supplier' => $request->supplier,
                'description' => $request->description,
                'stock' => $request->stock ?? $material->stock
            ]);
            
            return redirect()->back()->with('success', 'Bahan baku ' . $material->name . ' berhasil diupdate');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate bahan baku: ' . $e->getMessage());
        }
    }
    
    /**
     * Hapus Bahan Baku
     */
    public function deleteRawMaterial($id)
    {
        $material = RawMaterial::findOrFail($id);
        $material->delete();
        
        return redirect()->back()->with('success', 'Bahan baku berhasil dihapus');
    }
    
    /**
     * Get Raw Material Data for Edit (AJAX)
     */
    public function getRawMaterial($id)
    {
        try {
            $material = RawMaterial::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'material' => $material
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bahan baku tidak ditemukan'
            ]);
        }
    }
    
    /**
     * Halaman Pembelian (Purchase Orders)
     */
    public function purchaseOrders()
    {
        $purchaseOrders = PurchaseOrder::with('items.rawMaterial')
            ->orderBy('order_date', 'desc')
            ->get();
        
        $materials = RawMaterial::where('is_active', true)->orderBy('name')->get();
        
        $totalPending = PurchaseOrder::where('status', 'pending')->count();
        $totalReceived = PurchaseOrder::where('status', 'received')->count();
        $totalSpent = PurchaseOrder::where('status', 'received')->sum('total_amount');
        
        return view('owner.purchase-orders', compact(
            'purchaseOrders', 'materials', 'totalPending', 'totalReceived', 'totalSpent'
        ));
    }
    
    /**
     * Simpan Purchase Order
     */
    public function storePurchaseOrder(Request $request)
    {
        $request->validate([
            'supplier' => 'nullable|string',
            'order_date' => 'required|date',
            'items' => 'required|array',
            'items.*.material_id' => 'required|exists:raw_materials,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0'
        ]);
        
        DB::beginTransaction();
        
        try {
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $totalAmount += $item['quantity'] * $item['price'];
            }
            
            $po = PurchaseOrder::create([
                'po_number' => PurchaseOrder::generatePONumber(),
                'supplier' => $request->supplier,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'order_date' => $request->order_date,
                'expected_date' => $request->expected_date,
                'notes' => $request->notes
            ]);
            
            foreach ($request->items as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'raw_material_id' => $item['material_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price']
                ]);
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Purchase Order berhasil dibuat');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membuat PO: ' . $e->getMessage());
        }
    }
    
    /**
     * Update Status Purchase Order
     */
    public function updatePurchaseOrderStatus(Request $request, $id)
    {
        try {
            $po = PurchaseOrder::findOrFail($id);
            
            $request->validate([
                'status' => 'required|in:pending,approved,received,cancelled'
            ]);
            
            $oldStatus = $po->status;
            $newStatus = $request->status;
            $po->status = $newStatus;
            
            if ($newStatus == 'received') {
                $po->received_date = Carbon::now();
                
                if ($po->item && $po->item->count() > 0) {
                    foreach ($po->item as $item) {
                        if ($item->rawMaterial) {
                            $material = $item->rawMaterial;
                            $material->stock += $item->quantity;
                            $material->save();
                        }
                    }
                }
            }
            
            if ($newStatus == 'cancelled' && $oldStatus == 'received') {
                if ($po->item && $po->item->count() > 0) {
                    foreach ($po->item as $item) {
                        if ($item->rawMaterial) {
                            $material = $item->rawMaterial;
                            $material->stock -= $item->quantity;
                            $material->save();
                        }
                    }
                }
            }
            
            $po->save();
            
            return redirect()->back()->with('success', "Status PO berhasil diubah dari {$oldStatus} menjadi {$newStatus}");
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate status PO: ' . $e->getMessage());
        }
    }
    
    /**
     * Hapus Purchase Order
     */
    public function deletePurchaseOrder($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        
        if ($po->status == 'received') {
            return redirect()->back()->with('error', 'Tidak dapat menghapus PO yang sudah diterima');
        }
        
        $po->delete();
        
        return redirect()->back()->with('success', 'Purchase Order berhasil dihapus');
    }
}