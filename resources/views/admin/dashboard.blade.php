@extends('layouts.admin')

@section('content')
<style>
    .stats-card {
        border-radius: 15px;
        transition: all 0.3s ease;
        border: none;
        overflow: hidden;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .stats-card .card-body {
        padding: 20px;
    }
    .stats-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.2);
    }
    .recent-orders-list {
        max-height: 400px;
        overflow-y: auto;
    }
    .order-item {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }
    .order-item:hover {
        background: #FFF8F0;
        border-left-color: #FF6B35;
        transform: translateX(5px);
    }
    .quick-action-btn {
        padding: 15px;
        border-radius: 12px;
        transition: all 0.3s ease;
        text-align: center;
    }
    .quick-action-btn:hover {
        transform: translateY(-3px);
    }
    .welcome-section {
        background: linear-gradient(135deg, #2C1810 0%, #4A2C1A 100%);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        color: white;
    }
</style>

<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-2">
                <i class="fas fa-mug-hot"></i> Selamat Datang, Admin!
            </h3>
            <p class="mb-0 opacity-75">by : Iron Velsitas</p>
        </div>
        <div class="text-end">
            <i class="fas fa-chart-line fa-3x opacity-50"></i>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 col-6 mb-3">
        <div class="card stats-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title opacity-75 mb-1">Total Produk</h6>
                        <h2 class="mb-0 fw-bold">{{ \App\Models\Product::count() }}</h2>
                        <small class="opacity-75">Produk tersedia</small>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-box fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card stats-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title opacity-75 mb-1">Total Pesanan</h6>
                        <h2 class="mb-0 fw-bold">{{ \App\Models\Order::count() }}</h2>
                        <small class="opacity-75">Semua pesanan</small>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card stats-card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title opacity-75 mb-1">Pending</h6>
                        <h2 class="mb-0 fw-bold">{{ \App\Models\Order::where('status', 'pending')->count() }}</h2>
                        <small class="opacity-75">Menunggu diproses</small>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card stats-card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title opacity-75 mb-1">Pendapatan</h6>
                        <h2 class="mb-0 fw-bold fs-5">Rp {{ number_format(\App\Models\Order::where('status', 'completed')->sum('total_amount'), 0, ',', '.') }}</h2>
                        <small class="opacity-75">Total pendapatan</small>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Second Row Statistics (Dine In) -->
<div class="row mb-4">
    <div class="col-md-3 col-6 mb-3">
        <div class="card stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title opacity-75 mb-1">Total Dine In</h6>
                        <h2 class="mb-0 fw-bold">{{ \App\Models\DineInOrder::count() }}</h2>
                        <small class="opacity-75">Pesanan di tempat</small>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-utensils fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title opacity-75 mb-1">Dine In Pending</h6>
                        <h2 class="mb-0 fw-bold">{{ \App\Models\DineInOrder::where('status', 'pending')->count() }}</h2>
                        <small class="opacity-75">Menunggu diproses</small>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-hourglass-half fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title opacity-75 mb-1">Dine In Today</h6>
                        <h2 class="mb-0 fw-bold">{{ \App\Models\DineInOrder::whereDate('created_at', today())->count() }}</h2>
                        <small class="opacity-75">Hari ini</small>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-calendar-day fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card stats-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title opacity-75 mb-1">Dine In Revenue</h6>
                        <h2 class="mb-0 fw-bold fs-5">Rp {{ number_format(\App\Models\DineInOrder::where('status', 'completed')->sum('total_amount'), 0, ',', '.') }}</h2>
                        <small class="opacity-75">Pendapatan dine in</small>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Orders -->
    <div class="col-md-7 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-history text-primary"></i> Pesanan Terbaru
                </h5>
                <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-link">Lihat Semua <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="card-body p-0">
                @php $recentOrders = \App\Models\Order::latest()->limit(5)->get(); @endphp
                @if($recentOrders->count() > 0)
                    <div class="recent-orders-list">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr class="order-item">
                                    <td>
                                        <strong>{{ $order->order_number }}</strong>
                                        <br>
                                        <small class="text-muted">#{{ $order->id }}</small>
                                    </td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td class="fw-bold text-success">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        @if($order->status == 'completed')
                                            <span class="badge bg-success">✅ Completed</span>
                                        @elseif($order->status == 'pending')
                                            <span class="badge bg-warning text-dark">🟡 Pending</span>
                                        @elseif($order->status == 'processing')
                                            <span class="badge bg-info">🔵 Processing</span>
                                        @else
                                            <span class="badge bg-danger">❌ Cancelled</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                        <p class="text-muted">Belum ada pesanan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-md-5 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-bolt text-warning"></i> Aksi Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <a href="{{ route('admin.products.create') }}" class="quick-action-btn btn btn-primary w-100 d-block">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                            <div class="fw-bold">Tambah Produk</div>
                            <small class="opacity-75">Tambahkan produk baru</small>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.orders') }}" class="quick-action-btn btn btn-info w-100 d-block">
                            <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                            <div class="fw-bold">Lihat Pesanan</div>
                            <small class="opacity-75">Kelola pesanan</small>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.dinein') }}" class="quick-action-btn btn btn-success w-100 d-block">
                            <i class="fas fa-utensils fa-2x mb-2"></i>
                            <div class="fw-bold">Dine In Orders</div>
                            <small class="opacity-75">Pesanan di tempat</small>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.products') }}" class="quick-action-btn btn btn-secondary w-100 d-block">
                            <i class="fas fa-boxes fa-2x mb-2"></i>
                            <div class="fw-bold">Manajemen Stok</div>
                            <small class="opacity-75">Update stok produk</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Low Stock Alert -->
@php
    $lowStockProducts = \App\Models\Product::where('stock', '<', 10)->where('stock', '>', 0)->get();
    $outOfStockProducts = \App\Models\Product::where('stock', '<=', 0)->get();
@endphp

@if($lowStockProducts->count() > 0 || $outOfStockProducts->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle"></i> Peringatan Stok Produk
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($lowStockProducts->count() > 0)
                    <div class="col-md-6">
                        <h6 class="text-warning">⚠️ Stok Menipis ({{ $lowStockProducts->count() }} produk)</h6>
                        <ul class="list-group list-group-flush">
                            @foreach($lowStockProducts as $product)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $product->name }}
                                <span class="badge bg-warning text-dark">Stok: {{ $product->stock }}</span>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i> Restock
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    @if($outOfStockProducts->count() > 0)
                    <div class="col-md-6">
                        <h6 class="text-danger">❌ Stok Habis ({{ $outOfStockProducts->count() }} produk)</h6>
                        <ul class="list-group list-group-flush">
                            @foreach($outOfStockProducts as $product)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $product->name }}
                                <span class="badge bg-danger">Habis</span>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-edit"></i> Tambah Stok
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection