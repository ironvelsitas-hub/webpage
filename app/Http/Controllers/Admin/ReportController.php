<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\DineInOrder;
use App\Models\Product;
use App\Models\User;
use App\Models\Salary;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class ReportController extends Controller
{
    /**
     * Halaman utama laporan
     */
    public function index()
    {
        $reports = Report::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.reports.index', compact('reports'));
    }

    /**
     * Form buat laporan baru
     */
    public function create()
    {
        return view('admin.reports.create');
    }

    /**
     * Generate laporan berdasarkan periode
     */
    public function generate(Request $request)
    {
        $request->validate([
            'period' => 'required|in:today,week,month,year,custom',
            'start_date' => 'required_if:period,custom|nullable|date',
            'end_date' => 'required_if:period,custom|nullable|date|after_or_equal:start_date'
        ]);

        // Tentukan periode
        switch ($request->period) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                $periodText = 'Hari Ini - ' . $startDate->format('d/m/Y');
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $periodText = 'Minggu Ini - ' . $startDate->format('d/m/Y') . ' s/d ' . $endDate->format('d/m/Y');
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $periodText = 'Bulan Ini - ' . $startDate->format('F Y');
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                $periodText = 'Tahun Ini - ' . $startDate->format('Y');
                break;
            default:
                $startDate = Carbon::parse($request->start_date);
                $endDate = Carbon::parse($request->end_date);
                $periodText = $startDate->format('d/m/Y') . ' s/d ' . $endDate->format('d/m/Y');
        }

        // ========== DATA PENJUALAN ==========
        // Order Delivery
        $deliveryOrders = Order::whereBetween('created_at', [$startDate, $endDate->endOfDay()])->get();
        $deliveryRevenue = $deliveryOrders->sum('total_amount');
        $deliveryCount = $deliveryOrders->count();
        
        // Dine In
        $dineInOrders = DineInOrder::whereBetween('created_at', [$startDate, $endDate->endOfDay()])->get();
        $dineInRevenue = $dineInOrders->sum('total_amount');
        $dineInCount = $dineInOrders->count();
        
        // Total
        $totalRevenue = $deliveryRevenue + $dineInRevenue;
        $totalOrders = $deliveryCount + $dineInCount;

        // ========== DATA PRODUK TERLARIS ==========
        $topProducts = $this->getTopProducts($startDate, $endDate);

        // ========== DATA METODE PEMBAYARAN ==========
        $paymentMethods = $this->getPaymentMethods($startDate, $endDate);

        // ========== DATA PENGELUARAN ==========
        // Gaji Karyawan
        $totalGaji = Salary::whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'paid')
            ->sum('total_salary');
        
        // Estimasi pengeluaran lainnya
        $pembelianBahanBaku = $totalRevenue * 0.5;
        $sewaOperasional = $totalRevenue * 0.15;
        $marketing = $totalRevenue * 0.05;
        $lainLain = $totalRevenue * 0.05;
        
        $totalExpense = $totalGaji + $pembelianBahanBaku + $sewaOperasional + $marketing + $lainLain;
        $netProfit = $totalRevenue - $totalExpense;
        $profitMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;

        // ========== DATA HARIAN ==========
        $dailyData = $this->getDailyData($startDate, $endDate);

        // ========== SIMPAN KE DATABASE ==========
 $report = Report::create([
            'title' => 'Laporan ' . $periodText,
            'description' => 'Laporan ' . $periodText . ' yang dibuat oleh admin',
            'type' => 'sales',
            'period_start' => $startDate,
            'period_end' => $endDate,
            'total_revenue' => $totalRevenue,
            'total_expense' => $totalExpense,
            'net_profit' => $netProfit,
            'total_orders' => $totalOrders,
            'data' => [
                'delivery_revenue' => $deliveryRevenue,
                'delivery_count' => $deliveryCount,
                'dinein_revenue' => $dineInRevenue,
                'dinein_count' => $dineInCount,
                'top_products' => $topProducts,
                'payment_methods' => $paymentMethods,
                'daily_data' => $dailyData,
                'profit_margin' => $profitMargin,
                'total_gaji' => $totalGaji
            ],
            'created_by' => auth()->id(),
            'status' => 'submitted',
            'is_read' => false  // Set belum dibaca
        ]);

        return redirect()->route('admin.reports.show', $report->id)
            ->with('success', 'Laporan berhasil digenerate dan telah dikirim ke Owner untuk disetujui');
    }
    /**
     * Get unread reports count untuk notifikasi
     */
    public function getUnreadCount()
    {
        $count = Report::where('status', 'submitted')
            ->where('is_read', false)
            ->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Get unread reports (AJAX)
     */
    public function getUnreadReports()
    {
        $reports = Report::where('status', 'submitted')
            ->where('is_read', false)
            ->with('creator')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'reports' => $reports,
            'count' => $reports->count()
        ]);
    }

    /**
     * Lihat detail laporan
     */
    public function show($id)
    {
        $report = Report::with('creator')->findOrFail($id);
        return view('admin.reports.show', compact('report'));
    }

    /**
     * Approve laporan oleh owner
     */
    public function approve($id)
    {
        $report = Report::findOrFail($id);
        $report->status = 'approved';
        $report->save();

        return redirect()->back()->with('success', 'Laporan telah disetujui');
    }

    /**
     * Hapus laporan
     */
    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return redirect()->route('admin.reports.index')->with('success', 'Laporan berhasil dihapus');
    }

    /**
     * Export laporan ke CSV
     */
    public function export($id)
    {
        $report = Report::with('creator')->findOrFail($id);
        $data = $report->data;

        $filename = 'laporan_' . $report->id . '_' . date('Ymd_His') . '.csv';
        $handle = fopen('php://temp', 'w');

        // Header
        fputcsv($handle, ['LAPORAN KEUANGAN KOPI ANCOL']);
        fputcsv($handle, []);
        fputcsv($handle, ['Periode', $report->period_start->format('d/m/Y') . ' - ' . $report->period_end->format('d/m/Y')]);
        fputcsv($handle, ['Tanggal Dibuat', $report->created_at->format('d/m/Y H:i:s')]);
        fputcsv($handle, ['Dibuat Oleh', $report->creator->name]);
        fputcsv($handle, []);
        fputcsv($handle, ['RINGKASAN']);
        fputcsv($handle, ['Total Pendapatan', 'Rp ' . number_format($report->total_revenue, 0, ',', '.')]);
        fputcsv($handle, ['Total Pengeluaran', 'Rp ' . number_format($report->total_expense, 0, ',', '.')]);
        fputcsv($handle, ['Laba Bersih', 'Rp ' . number_format($report->net_profit, 0, ',', '.')]);
        fputcsv($handle, ['Total Pesanan', $report->total_orders]);
        fputcsv($handle, []);
        fputcsv($handle, ['RINCIAN PENDAPATAN']);
        fputcsv($handle, ['Delivery', 'Rp ' . number_format($data['delivery_revenue'] ?? 0, 0, ',', '.')]);
        fputcsv($handle, ['Dine In', 'Rp ' . number_format($data['dinein_revenue'] ?? 0, 0, ',', '.')]);
        
        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

/**
 * Ambil produk terlaris
 */
private function getTopProducts($startDate, $endDate)
{
    $products = [];
    
    // ========== DARI ORDER (DELIVERY) ==========
    try {
        // Cek apakah tabel order_items ada
        if (DB::getSchemaBuilder()->hasTable('order_items')) {
            $orderItems = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->select('order_items.product_name', DB::raw('SUM(order_items.quantity) as total_quantity'))
                ->whereBetween('orders.created_at', [$startDate, $endDate->endOfDay()])
                ->groupBy('order_items.product_name')
                ->get();
            
            foreach ($orderItems as $item) {
                $products[$item->product_name] = ($products[$item->product_name] ?? 0) + $item->total_quantity;
            }
        }
    } catch (\Exception $e) {
        \Log::error('Error getting order items: ' . $e->getMessage());
    }
    
    // ========== DARI DINE IN ==========
    try {
        // Cek nama tabel yang benar untuk dine in items
        $dineInItemsTable = 'dine_in_items';
        
        // Jika tabel tidak ada, coba dengan nama alternatif
        if (!DB::getSchemaBuilder()->hasTable($dineInItemsTable)) {
            // Cek apakah ada tabel dine_ins dengan kolom items (JSON)
            if (DB::getSchemaBuilder()->hasTable('dine_ins')) {
                // Ambil dari JSON items di tabel dine_ins
                $dineIns = DB::table('dine_ins')
                    ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
                    ->get();
                
                foreach ($dineIns as $dineIn) {
                    $items = json_decode($dineIn->items, true);
                    if (is_array($items)) {
                        foreach ($items as $item) {
                            $productName = $item['name'] ?? $item['product_name'] ?? 'Unknown';
                            $quantity = $item['quantity'] ?? 1;
                            $products[$productName] = ($products[$productName] ?? 0) + $quantity;
                        }
                    }
                }
            }
        } else {
            // Tabel dine_in_items ada, gunakan query normal
            $dineInItems = DB::table($dineInItemsTable)
                ->join('dine_ins', $dineInItemsTable . '.dine_in_id', '=', 'dine_ins.id')
                ->select($dineInItemsTable . '.product_name', DB::raw('SUM(' . $dineInItemsTable . '.quantity) as total_quantity'))
                ->whereBetween('dine_ins.created_at', [$startDate, $endDate->endOfDay()])
                ->groupBy($dineInItemsTable . '.product_name')
                ->get();
            
            foreach ($dineInItems as $item) {
                $products[$item->product_name] = ($products[$item->product_name] ?? 0) + $item->total_quantity;
            }
        }
    } catch (\Exception $e) {
        \Log::error('Error getting dine in items: ' . $e->getMessage());
    }
    
    // ========== FALLBACK: Ambil dari order items JSON ==========
    if (empty($products)) {
        try {
            $orders = Order::whereBetween('created_at', [$startDate, $endDate->endOfDay()])
                ->whereNotNull('items')
                ->get();
            
            foreach ($orders as $order) {
                $items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
                if (is_array($items)) {
                    foreach ($items as $item) {
                        $productName = $item['name'] ?? $item['product_name'] ?? 'Unknown';
                        $quantity = $item['quantity'] ?? 1;
                        $products[$productName] = ($products[$productName] ?? 0) + $quantity;
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error getting orders JSON: ' . $e->getMessage());
        }
    }
    
    arsort($products);
    return array_slice($products, 0, 5);
}
/**
 * Ambil metode pembayaran
 */
private function getPaymentMethods($startDate, $endDate)
{
    $methods = [];
    
    // Dari Order Delivery
    try {
        $orderMethods = Order::select('payment_method', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->whereNotNull('payment_method')
            ->groupBy('payment_method')
            ->get();
        
        foreach ($orderMethods as $method) {
            $methods[$method->payment_method] = ($methods[$method->payment_method] ?? 0) + $method->total;
        }
    } catch (\Exception $e) {
        \Log::error('Error getting order payment methods: ' . $e->getMessage());
    }
    
    // Dari Dine In
    try {
        $dineInMethods = DineInOrder::select('payment_method', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->whereNotNull('payment_method')
            ->groupBy('payment_method')
            ->get();
        
        foreach ($dineInMethods as $method) {
            $methods[$method->payment_method] = ($methods[$method->payment_method] ?? 0) + $method->total;
        }
    } catch (\Exception $e) {
        \Log::error('Error getting dinein payment methods: ' . $e->getMessage());
    }
    
    return $methods;
}

/**
 * Ambil data harian
 */
private function getDailyData($startDate, $endDate)
{
    $daily = [];
    $currentDate = clone $startDate;
    
    while ($currentDate <= $endDate) {
        $deliveryRevenue = Order::whereDate('created_at', $currentDate)->sum('total_amount');
        $dineInRevenue = DineInOrder::whereDate('created_at', $currentDate)->sum('total_amount');
        
        $daily[] = [
            'date' => $currentDate->format('d/m/Y'),
            'delivery' => $deliveryRevenue,
            'dinein' => $dineInRevenue,
            'total' => $deliveryRevenue + $dineInRevenue
        ];
        
        $currentDate->addDay();
    }
    
    return $daily;
}}