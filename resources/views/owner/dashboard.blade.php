@extends('layouts.owner')

@section('content')
<style>
    /* Modern Owner Dashboard Styles */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
        --warning-gradient: linear-gradient(135deg, #ffe259 0%, #ffa751 100%);
        --danger-gradient: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        --info-gradient: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
        --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
    }
    
    .dashboard-title {
        margin-bottom: 30px;
        padding: 20px 0 15px 0;
        border-bottom: 2px solid #e0e0e0;
        position: relative;
    }
    
    .dashboard-title h3 {
        font-weight: 800;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 8px;
    }
    
    .dashboard-title p {
        color: #7f8c8d;
        font-size: 14px;
    }
    
    .dashboard-title::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        border-radius: 3px;
    }
    
    .stats-card {
        background: white;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    }
    
    .stats-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.15);
    }
    
    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        position: absolute;
        top: 24px;
        right: 24px;
        background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);
        transition: all 0.3s ease;
    }
    
    .stats-card:hover .stats-icon {
        transform: scale(1.1) rotate(5deg);
    }
    
    .stats-value {
        font-size: 32px;
        font-weight: 800;
        background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 8px;
    }
    
    .stats-label {
        font-size: 14px;
        color: #7f8c8d;
        font-weight: 500;
        margin-bottom: 12px;
        letter-spacing: 0.3px;
    }
    
    .stats-trend {
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        background: #f8f9fa;
    }
    
    .trend-up {
        color: #27ae60;
    }
    
    .trend-down {
        color: #e74c3c;
    }
    
    .chart-container {
        background: white;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        margin-bottom: 20px;
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .chart-container:hover {
        box-shadow: 0 15px 50px rgba(0,0,0,0.12);
    }
    
    .badge-delivery {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    
    .badge-dinein {
        background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%);
        color: white;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    
    .btn-export {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40,167,69,0.3);
        color: white;
    }
    
    .split-card {
        display: flex;
        gap: 15px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 2px solid #f0f0f0;
    }
    
    .split-item {
        flex: 1;
        text-align: center;
        padding: 8px;
        border-radius: 12px;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }
    
    .split-item:hover {
        background: #e9ecef;
        transform: translateY(-2px);
    }
    .split-label {
        font-size: 11px;
        color: #7f8c8d;
        margin-bottom: 6px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .split-value {
        font-size: 18px;
        font-weight: 800;
    }
    
    .split-value.delivery {
        color: #17a2b8;
    }
    
    .split-value.dinein {
        color: #6f42c1;
    }
    
    .empty-state {
        text-align: center;
        padding: 50px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 20px;
    }
    
    .empty-state i {
        font-size: 56px;
        color: #C49A6C;
        margin-bottom: 15px;
        opacity: 0.7;
    }
    
    /* Modern Table Styles */
    .modern-table {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .modern-table thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 15px;
        border: none;
    }
    
    .modern-table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .modern-table tbody tr:hover {
        background: #f8f9fa;
        transform: translateX(5px);
    }
    
    .modern-table tbody td {
        padding: 15px;
        vertical-align: middle;
    }
    
    /* Progress Bar Styles */
    .progress-modern {
        height: 10px;
        border-radius: 10px;
        background-color: #e9ecef;
        overflow: hidden;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
    }
    
    .progress-bar-modern {
        border-radius: 10px;
        transition: width 0.6s ease;
        position: relative;
        overflow: hidden;
    }
    
    .progress-bar-modern::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background: linear-gradient(90deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.1) 100%);
        animation: shimmer 2s infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    /* Badge Styles */
    .badge-modern {
        padding: 6px 14px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 11px;
        letter-spacing: 0.3px;
    }
    
    /* Card Header Styles */
    .card-header-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .card-header-custom h5 {
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .card-header-custom h5 i {
        color: #667eea;
    }
    
    /* Notification Bell untuk Owner */
    .notification-bell-owner {
        position: relative;
        cursor: pointer;
        margin-right: 15px;
    }
    
    .notification-badge-owner {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 10px;
        font-weight: bold;
        animation: pulse 1.5s infinite;
    }
    
    .notification-dropdown-owner {
        width: 380px;
        max-height: 450px;
        overflow-y: auto;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .notification-item-owner {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        transition: background 0.2s;
        cursor: pointer;
    }
    
    .notification-item-owner:hover {
        background: #f8f9fa;
    }
    
    .notification-item-owner.unread {
        background: #fff3cd;
        border-left: 3px solid #ffc107;
    }
    
    .notification-icon-report {
        width: 40px;
        height: 40px;
        background: #17a2b8;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
    }
    
    .notification-icon-report i {
        color: white;
        font-size: 18px;
    }
    
    .notification-content {
        flex: 1;
    }
    
    .notification-title {
        font-weight: 600;
        margin-bottom: 3px;
    }
    
    .notification-time {
        font-size: 10px;
        color: #999;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.7; }
        100% { transform: scale(1); opacity: 1; }
    }
    
    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .stats-card, .chart-container {
        animation: fadeInUp 0.6s ease-out;
    }
    
    .row > div:nth-child(1) .stats-card { animation-delay: 0.1s; }
    .row > div:nth-child(2) .stats-card { animation-delay: 0.2s; }
    .row > div:nth-child(3) .stats-card { animation-delay: 0.3s; }
    .row > div:nth-child(4) .stats-card { animation-delay: 0.4s; }
</style>

<!-- Tambahkan div untuk notifikasi di header - TAMBAHKAN INI -->
<div class="d-flex justify-content-between align-items-center">
    <div class="dashboard-title">
        <h3><i class="fas fa-chart-line"></i> Dashboard Owner</h3>
        <p>Selamat datang kembali, {{ Auth::user()->name }}! Berikut adalah ringkasan performa bisnis Anda secara real-time</p>
    </div>
    
    <!-- Notification Bell untuk Laporan - TAMBAHKAN INI -->
    <div class="d-flex align-items-center no-print">
        <div class="notification-bell-owner" onclick="toggleReportNotifications()">
            <i class="fas fa-bell fa-lg"></i>
            <span class="notification-badge-owner" id="reportNotificationCount" style="display: none;">0</span>
        </div>
    </div>
</div>

<!-- Ringkasan Penjualan Total (Gabungan) -->
<div class="row mb-4">
    <div class="col-12 mb-3">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-chart-simple" style="color: #667eea; font-size: 20px;"></i>
            <h4 class="mb-0 fw-bold" style="color: #2c3e50;">Ringkasan Penjualan</h4>
            <span class="badge bg-light text-dark ms-2">Delivery + Dine In</span>
        </div>
    </div>
    
    <!-- Hari Ini -->
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-sun"></i>
            </div>
            <div class="stats-value">Rp {{ number_format($todayTotalSales ?? 0, 0, ',', '.') }}</div>
            <div class="stats-label">Penjualan Hari Ini</div>
            <div class="stats-trend {{ ($salesGrowth ?? 0) >= 0 ? 'trend-up' : 'trend-down' }}">
                <i class="fas {{ ($salesGrowth ?? 0) >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                {{ number_format(abs($salesGrowth ?? 0), 1) }}% dari kemarin
            </div>
            <div class="split-card">
                <div class="split-item">
                    <div class="split-label">
                        <i class="fas fa-truck"></i> Delivery
                    </div>
                    <div class="split-value delivery">
                        Rp {{ number_format($todayOrderSales ?? 0, 0, ',', '.') }}
                    </div>
                    <small class="text-muted">{{ $todayOrders ?? 0 }} pesanan</small>
                </div>
                <div class="split-item">
                    <div class="split-label">
                        <i class="fas fa-utensils"></i> Dine In
                    </div>
                    <div class="split-value dinein">
                        Rp {{ number_format($todayDineInSales ?? 0, 0, ',', '.') }}
                    </div>
                    <small class="text-muted">{{ $todayDineIns ?? 0 }} pesanan</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Minggu Ini -->
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-calendar-week"></i>
            </div>
            <div class="stats-value">Rp {{ number_format($weekTotalSales ?? 0, 0, ',', '.') }}</div>
            <div class="stats-label">Penjualan Minggu Ini</div>
            <div class="split-card">
                <div class="split-item">
                    <div class="split-label">
                        <i class="fas fa-truck"></i> Delivery
                    </div>
                    <div class="split-value delivery">Rp {{ number_format($weekOrderSales ?? 0, 0, ',', '.') }}</div>
                    <small>{{ $weekOrders ?? 0 }} pesanan</small>
                </div>
                <div class="split-item">
                    <div class="split-label">
                        <i class="fas fa-utensils"></i> Dine In
                    </div>
                    <div class="split-value dinein">Rp {{ number_format($weekDineInSales ?? 0, 0, ',', '.') }}</div>
                    <small>{{ $weekDineIns ?? 0 }} pesanan</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bulan Ini -->
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stats-value">Rp {{ number_format($monthTotalSales ?? 0, 0, ',', '.') }}</div>
            <div class="stats-label">Penjualan Bulan Ini</div>
            <div class="split-card">
                <div class="split-item">
                    <div class="split-label">
                        <i class="fas fa-truck"></i> Delivery
                    </div>
                    <div class="split-value delivery">Rp {{ number_format($monthOrderSales ?? 0, 0, ',', '.') }}</div>
                    <small>{{ $monthOrders ?? 0 }} pesanan</small>
                </div>
                <div class="split-item">
                    <div class="split-label">
                        <i class="fas fa-utensils"></i> Dine In
                    </div>
                    <div class="split-value dinein">Rp {{ number_format($monthDineInSales ?? 0, 0, ',', '.') }}</div>
                    <small>{{ $monthDineIns ?? 0 }} pesanan</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tahun Ini -->
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-calendar-year"></i>
            </div>
            <div class="stats-value">Rp {{ number_format($yearTotalSales ?? 0, 0, ',', '.') }}</div>
            <div class="stats-label">Penjualan Tahun Ini</div>
            <div class="split-card">
                <div class="split-item">
                    <div class="split-label">
                        <i class="fas fa-truck"></i> Delivery
                    </div>
                    <div class="split-value delivery">Rp {{ number_format($yearOrderSales ?? 0, 0, ',', '.') }}</div>
                    <small>{{ $yearOrders ?? 0 }} pesanan</small>
                </div>
                <div class="split-item">
                    <div class="split-label">
                        <i class="fas fa-utensils"></i> Dine In
                    </div>
                    <div class="split-value dinein">Rp {{ number_format($yearDineInSales ?? 0, 0, ',', '.') }}</div>
                    <small>{{ $yearDineIns ?? 0 }} pesanan</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Penjualan Gabungan -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="chart-container">
            <div class="card-header-custom">
                <h5>
                    <i class="fas fa-chart-line"></i> 
                    Tren Penjualan 7 Hari Terakhir
                </h5>
                <a href="{{ route('owner.export', ['period' => 'week']) }}" class="btn btn-export btn-sm">
                    <i class="fas fa-download"></i> Export Data
                </a>
            </div>
            @if(!empty($salesChart) && count($salesChart) > 0)
                <canvas id="salesChart" style="height: 320px;"></canvas>
                <div class="mt-4 text-center">
                    <span class="badge-delivery me-3"><i class="fas fa-truck"></i> Delivery</span>
                    <span class="badge-dinein"><i class="fas fa-utensils"></i> Dine In</span>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-chart-line"></i>
                    <p class="mt-2">Belum ada数据 penjualan untuk ditampilkan</p>
                    <small class="text-muted">Data akan muncul setelah ada transaksi</small>
                </div>
            @endif
        </div>
    </div>
    <div class="col-lg-4">
        <div class="chart-container">
            <div class="card-header-custom">
                <h5>
                    <i class="fas fa-chart-pie"></i> 
                    Status Pesanan
                </h5>
            </div>
            @if(($totalPending ?? 0) > 0 || ($totalProcessing ?? 0) > 0 || ($totalCompleted ?? 0) > 0 || ($totalCancelled ?? 0) > 0)
                <canvas id="statusChart" style="height: 220px;"></canvas>
                <div class="mt-4">
                    <div class="d-flex justify-content-between align-items-center small mb-2 p-2 rounded" style="background: #f8f9fa;">
                        <span><i class="fas fa-circle text-warning"></i> Pending</span>
                        <span class="fw-bold">{{ $totalPending ?? 0 }} pesanan</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center small mb-2 p-2 rounded" style="background: #f8f9fa;">
                        <span><i class="fas fa-circle text-info"></i> Processing</span>
                        <span class="fw-bold">{{ $totalProcessing ?? 0 }} pesanan</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center small mb-2 p-2 rounded" style="background: #f8f9fa;">
                        <span><i class="fas fa-circle text-success"></i> Completed</span>
                        <span class="fw-bold">{{ $totalCompleted ?? 0 }} pesanan</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center small p-2 rounded" style="background: #f8f9fa;">
                        <span><i class="fas fa-circle text-danger"></i> Cancelled</span>
                        <span class="fw-bold">{{ $totalCancelled ?? 0 }} pesanan</span>
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-chart-pie"></i>
                    <p class="mt-2">Belum ada data status pesanan</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Produk Terlaris & Metode Pembayaran -->
<div class="row mb-4">
    <div class="col-lg-6 mb-3">
        <div class="chart-container">
            <div class="card-header-custom">
                <h5>
                    <i class="fas fa-trophy"></i> 
                    Top 5 Produk Terlaris
                </h5>
                <span class="badge bg-warning text-dark"><i class="fas fa-fire"></i> Best Seller</span>
            </div>
            @if(!empty($topProducts) && count($topProducts) > 0)
                <div class="table-responsive">
                    <table class="table modern-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-box"></i> Produk</th>
                                <th class="text-center"><i class="fas fa-chart-simple"></i> Terjual</th>
                                <th class="text-end"><i class="fas fa-money-bill"></i> Total Penjualan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $index => $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($index == 0)
                                            <i class="fas fa-crown text-warning"></i>
                                        @elseif($index == 1)
                                            <i class="fas fa-medal text-secondary"></i>
                                        @elseif($index == 2)
                                            <i class="fas fa-medal text-bronze" style="color: #cd7f32;"></i>
                                        @else
                                            <i class="fas fa-box text-muted"></i>
                                        @endif
                                        <strong>{{ $product['product_name'] ?? $product->product_name ?? '-' }}</strong>
                                    </div>
                                </div>
                                <td class="text-center">
                                    <span class="badge bg-primary rounded-pill">{{ number_format($product['total_quantity'] ?? $product->total_quantity ?? 0) }}x</span>
                                </td>
                                <td class="text-end fw-bold text-success">
                                    Rp {{ number_format($product['total_sales'] ?? $product->total_sales ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-trophy"></i>
                    <p class="mt-2">Belum ada data produk terlaris</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Metode Pembayaran -->
    <div class="col-lg-6 mb-3">
        <div class="chart-container">
            <div class="card-header-custom">
                <h5>
                    <i class="fas fa-credit-card"></i> 
                    Metode Pembayaran
                </h5>
                <span class="badge bg-info">Distribusi Pembayaran</span>
            </div>
            
            @php
                $availablePayments = [
                    'qris' => ['name' => 'QRIS', 'icon' => 'fa-qrcode', 'color' => '#17a2b8'],
                    'virtual_account' => ['name' => 'Virtual Account', 'icon' => 'fa-university', 'color' => '#6f42c1'],
                    'ewallet' => ['name' => 'E-Wallet', 'icon' => 'fa-wallet', 'color' => '#fd7e14'],
                    'cod' => ['name' => 'COD', 'icon' => 'fa-hand-holding-usd', 'color' => '#20c997']
                ];
                
                $paymentData = [];
                foreach($availablePayments as $key => $payment) {
                    $paymentData[$key] = [
                        'name' => $payment['name'],
                        'icon' => $payment['icon'],
                        'color' => $payment['color'],
                        'total' => 0,
                        'amount' => 0,
                        'percentage' => 0
                    ];
                }
                
                if(!empty($paymentMethods) && count($paymentMethods) > 0) {
                    foreach($paymentMethods as $method) {
                        $methodName = strtolower($method->payment_method ?? $method['payment_method'] ?? '');
                        
                        foreach($availablePayments as $key => $payment) {
                            if(strpos($methodName, $key) !== false || strpos($methodName, str_replace('_', ' ', $key)) !== false) {
                                $paymentData[$key]['total'] = $method->total ?? $method['total'] ?? 0;
                                $paymentData[$key]['amount'] = $method->amount ?? $method['amount'] ?? 0;
                                break;
                            }
                        }
                    }
                }
                
                $maxTotal = max(array_column($paymentData, 'total'));
                foreach($paymentData as $key => $data) {
                    $paymentData[$key]['percentage'] = $maxTotal > 0 ? ($data['total'] / $maxTotal) * 100 : 0;
                }
            @endphp
            
            @foreach($paymentData as $payment)
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 36px; height: 36px; background: {{ $payment['color'] }}20; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas {{ $payment['icon'] }}" style="color: {{ $payment['color'] }}; font-size: 18px;"></i>
                        </div>
                        <div>
                            <span class="fw-semibold">{{ $payment['name'] }}</span>
                            <br>
                            <small class="text-muted">{{ number_format($payment['total']) }} pesanan</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="fw-bold text-success">Rp {{ number_format($payment['amount'], 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="progress-modern">
                    <div class="progress-bar-modern" style="width: {{ min($payment['percentage'], 100) }}%; background-color: {{ $payment['color'] }}; height: 100%;"></div>
                </div>
            </div>
            @endforeach
            
            @if(empty($paymentMethods) || count($paymentMethods) == 0)
            <div class="empty-state">
                <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                <p class="mb-1">Belum ada data metode pembayaran</p>
                <small class="text-muted">Data akan muncul setelah ada pesanan</small>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Top 5 Diskon -->
<div class="row mb-4">
    <div class="col-12">
        <div class="chart-container">
            <div class="card-header-custom">
                <h5>
                    <i class="fas fa-ticket-alt"></i> 
                    Top 5 Diskon Paling Sering Digunakan
                </h5>
                <span class="badge bg-success"><i class="fas fa-chart-simple"></i> Performance</span>
            </div>
            
            @php
                $allPromos = App\Models\Discount::orderBy('used_count', 'desc')->limit(5)->get();
            @endphp
            
            @if($allPromos->count() > 0)
                <div class="table-responsive">
                    <table class="table modern-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> Kode Promo</th>
                                <th><i class="fas fa-tag"></i> Nama Promo</th>
                                <th><i class="fas fa-info-circle"></i> Tipe</th>
                                <th class="text-center"><i class="fas fa-chart-simple"></i> Digunakan</th>
                                <th class="text-end"><i class="fas fa-gift"></i> Nilai Diskon</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allPromos as $promo)
                            @php
                                $nilaiDiskon = $promo->type == 'percentage' ? $promo->value . '%' : 'Rp ' . number_format($promo->value, 0, ',', '.');
                                $usedCount = $promo->used_count ?? 0;
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-ticket-alt" style="color: #C49A6C;"></i>
                                        <strong class="text-primary">{{ $promo->code }}</strong>
                                    </div>
                                </td>
                                <td>{{ $promo->name }}</td>
                                <td>
                                    @if($promo->type == 'percentage')
                                        <span class="badge-modern" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                            <i class="fas fa-percent"></i> Persentase
                                        </span>
                                    @else
                                        <span class="badge-modern" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white;">
                                            <i class="fas fa-money-bill"></i> Nominal
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($usedCount > 0)
                                        <span class="badge bg-success rounded-pill">
                                            <i class="fas fa-check-circle"></i> {{ number_format($usedCount) }}x
                                        </span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill">
                                            <i class="fas fa-clock"></i> 0x
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <strong class="text-primary">{{ $nilaiDiskon }}</strong>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Total Penggunaan Promo:</td>
                                <td class="text-end">
                                    <span class="badge bg-success" style="font-size: 14px; padding: 8px 16px;">
                                        <i class="fas fa-chart-line"></i> {{ number_format($allPromos->sum('used_count')) }}x
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                @if($allPromos->sum('used_count') == 0)
                <div class="alert alert-warning mt-3" style="border-radius: 16px; border-left: 4px solid #ffc107;">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <strong>Belum ada promo yang digunakan!</strong> Customer akan terlihat di sini setelah menggunakan kode promo.
                    <a href="{{ route('owner.promos') }}" class="alert-link ms-2">Buat promo sekarang <i class="fas fa-arrow-right"></i></a>
                </div>
                @else
                <div class="alert alert-info mt-3" style="border-radius: 16px; border-left: 4px solid #17a2b8; background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Informasi:</strong> Data diambil dari <strong>Manajemen Promo & Voucher</strong>. 
                    Jumlah penggunaan akan bertambah setiap kali customer menggunakan promo.
                </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                    <p class="mb-2">Belum ada promo yang dibuat</p>
                    <a href="{{ route('owner.promos') }}" class="btn btn-primary mt-2" style="border-radius: 30px;">
                        <i class="fas fa-plus"></i> Buat Promo Sekarang
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Export Laporan -->
<div class="row mb-4">
    <div class="col-12">
        <div class="chart-container">
            <div class="card-header-custom">
                <h5>
                    <i class="fas fa-file-export"></i> 
                    Export Laporan
                </h5>
                <span class="badge bg-primary"><i class="fas fa-download"></i> Unduh Data</span>
            </div>
            <div class="row g-2">
                <div class="col-auto">
                    <a href="{{ route('owner.export', ['period' => 'today']) }}" class="btn btn-outline-primary btn-sm" style="border-radius: 30px; padding: 8px 20px;">
                        <i class="fas fa-sun"></i> Hari Ini
                    </a>
                </div>
                <div class="col-auto">
                    <a href="{{ route('owner.export', ['period' => 'week']) }}" class="btn btn-outline-primary btn-sm" style="border-radius: 30px; padding: 8px 20px;">
                        <i class="fas fa-calendar-week"></i> Minggu Ini
                    </a>
                </div>
                <div class="col-auto">
                    <a href="{{ route('owner.export', ['period' => 'month']) }}" class="btn btn-outline-primary btn-sm" style="border-radius: 30px; padding: 8px 20px;">
                        <i class="fas fa-calendar-alt"></i> Bulan Ini
                    </a>
                </div>
                <div class="col-auto">
                    <a href="{{ route('owner.export', ['period' => 'year']) }}" class="btn btn-outline-primary btn-sm" style="border-radius: 30px; padding: 8px 20px;">
                        <i class="fas fa-calendar-year"></i> Tahun Ini
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Penjualan Gabungan (Delivery + Dine In)
    @if(!empty($salesChart) && count($salesChart) > 0)
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'bar',
        data: {
            labels: @json(array_column($salesChart, 'date')),
            datasets: [
                {
                    label: 'Delivery',
                    data: @json(array_column($salesChart, 'order_sales')),
                    backgroundColor: 'rgba(23, 162, 184, 0.7)',
                    borderColor: '#17a2b8',
                    borderWidth: 1,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(23, 162, 184, 0.9)'
                },
                {
                    label: 'Dine In',
                    data: @json(array_column($salesChart, 'dinein_sales')),
                    backgroundColor: 'rgba(111, 66, 193, 0.7)',
                    borderColor: '#6f42c1',
                    borderWidth: 1,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(111, 66, 193, 0.9)'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { 
                    position: 'top',
                    labels: {
                        font: { size: 12, weight: 'bold' },
                        usePointStyle: true,
                        boxWidth: 10
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    },
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    padding: 12,
                    cornerRadius: 8
                }
            },
            scales: {
                x: { 
                    stacked: false,
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                },
                y: {
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        },
                        font: { size: 11 }
                    },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                }
            }
        }
    });
    @endif
    
    // Grafik Status Pesanan Gabungan
    @if(($totalPending ?? 0) > 0 || ($totalProcessing ?? 0) > 0 || ($totalCompleted ?? 0) > 0 || ($totalCancelled ?? 0) > 0)
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Processing', 'Completed', 'Cancelled'],
            datasets: [{
                data: [{{ $totalPending ?? 0 }}, {{ $totalProcessing ?? 0 }}, {{ $totalCompleted ?? 0 }}, {{ $totalCancelled ?? 0 }}],
                backgroundColor: ['#FFC107', '#17A2B8', '#28A745', '#DC3545'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { 
                    position: 'bottom',
                    labels: {
                        font: { size: 11 },
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} pesanan (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });
    @endif
    
// ========== NOTIFIKASI LAPORAN UNTUK OWNER ==========
let reportNotifications = [];
let unreadReportCount = 0;

// Inisialisasi notifikasi laporan
function initReportNotifications() {
    checkUnreadReports();
    startReportPolling();
}

// Cek laporan belum dibaca - PERBAIKI URL
function checkUnreadReports() {
    fetch('/owner/reports/unread-count')
        .then(response => response.json())
        .then(data => {
            unreadReportCount = data.count;
            updateReportBadge();
        })
        .catch(error => console.log('Error checking reports:', error));
}

// Update badge notifikasi
function updateReportBadge() {
    const badge = document.getElementById('reportNotificationCount');
    if (unreadReportCount > 0) {
        badge.textContent = unreadReportCount;
        badge.style.display = 'block';
    } else {
        badge.style.display = 'none';
    }
}

// Toggle dropdown notifikasi
function toggleReportNotifications() {
    let dropdown = document.getElementById('reportNotificationsDropdown');
    
    if (dropdown) {
        dropdown.remove();
    } else {
        loadReportNotifications();
    }
}

// Load daftar laporan belum dibaca - PERBAIKI URL
function loadReportNotifications() {
    fetch('/owner/reports/unread')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.reports.length > 0) {
                createReportDropdown(data.reports);
                reportNotifications = data.reports;
            } else {
                createEmptyReportDropdown();
            }
        })
        .catch(error => console.log('Error loading reports:', error));
}

// Buat dropdown notifikasi
function createReportDropdown(reports) {
    const bell = document.querySelector('.notification-bell-owner');
    const dropdown = document.createElement('div');
    dropdown.id = 'reportNotificationsDropdown';
    dropdown.className = 'dropdown-menu show notification-dropdown-owner';
    dropdown.style.position = 'absolute';
    dropdown.style.top = (bell.offsetTop + 35) + 'px';
    dropdown.style.right = '0';
    
    let html = `
        <div class="dropdown-header bg-light py-2 px-3" style="border-radius: 16px 16px 0 0;">
            <strong><i class="fas fa-file-alt"></i> Laporan Masuk</strong>
            <small class="text-muted">(${reports.length} baru)</small>
        </div>
    `;
    
    reports.forEach(report => {
        const time = new Date(report.created_at).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'});
        const date = new Date(report.created_at).toLocaleDateString('id-ID');
        
        html += `
            <div class="notification-item-owner unread" onclick="markReportAsRead(${report.id})">
                <div class="d-flex align-items-center">
                    <div class="notification-icon-report">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">${report.title}</div>
                        <div class="small text-muted">Dibuat oleh: ${report.creator.name}</div>
                        <div class="notification-time">
                            <i class="fas fa-calendar-alt"></i> ${date} | ${time}
                        </div>
                        <div class="mt-1">
                            <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    html += `
        <div class="dropdown-footer text-center p-2 border-top">
            <a href="/owner/reports" class="btn btn-sm btn-link">Lihat Semua Laporan</a>
        </div>
    `;
    
    dropdown.innerHTML = html;
    bell.parentNode.style.position = 'relative';
    bell.parentNode.appendChild(dropdown);
    
    // Tutup dropdown saat klik di luar
    setTimeout(() => {
        document.addEventListener('click', function closeDropdown(e) {
            if (!bell.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.remove();
                document.removeEventListener('click', closeDropdown);
            }
        });
    }, 100);
}

// Buat dropdown kosong
function createEmptyReportDropdown() {
    const bell = document.querySelector('.notification-bell-owner');
    const dropdown = document.createElement('div');
    dropdown.id = 'reportNotificationsDropdown';
    dropdown.className = 'dropdown-menu show notification-dropdown-owner';
    dropdown.style.position = 'absolute';
    dropdown.style.top = (bell.offsetTop + 35) + 'px';
    dropdown.style.right = '0';
    dropdown.innerHTML = `
        <div class="text-center p-4">
            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
            <p class="mb-0 small text-muted">Tidak ada laporan baru</p>
        </div>
    `;
    
    bell.parentNode.style.position = 'relative';
    bell.parentNode.appendChild(dropdown);
    
    setTimeout(() => {
        document.addEventListener('click', function closeDropdown(e) {
            if (!bell.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.remove();
                document.removeEventListener('click', closeDropdown);
            }
        });
    }, 100);
}

// Tandai laporan sudah dibaca - PERBAIKI URL (yang ini yang utama)
function markReportAsRead(reportId) {
    fetch('/owner/reports/' + reportId + '/mark-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            unreadReportCount--;
            updateReportBadge();
            
            // Buka halaman detail laporan
            window.location.href = '/owner/reports/' + reportId;
        } else {
            console.error('Failed to mark as read:', data);
        }
    })
    .catch(error => console.log('Error marking as read:', error));
}

// Polling untuk cek laporan baru
function startReportPolling() {
    setInterval(() => {
        checkUnreadReports();
    }, 15000); // Cek setiap 15 detik
}

// Inisialisasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    initReportNotifications();
});    
    // Suara notifikasi
    function playReportSound() {
        try {
            const audio = new Audio('https://www.soundjay.com/misc/sounds/bell-ringing-05.mp3');
            audio.volume = 0.3;
            audio.play().catch(e => console.log('Audio play failed:', e));
        } catch(e) {}
    }
    
    // Inisialisasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        initReportNotifications();
    });
</script>
@endsection