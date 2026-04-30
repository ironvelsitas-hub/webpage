@extends('layouts.owner')

@section('content')
<style>
    .dashboard-title {
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .dashboard-title h3 {
        font-weight: 700;
        color: #2C1810;
    }
    
    .stats-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }
    
    .stats-value {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 5px;
    }
    
    .stats-label {
        font-size: 14px;
        color: #666;
    }
    
    .chart-container {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }
    
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    
    .status-pending {
        background: #ffc107;
        color: #856404;
    }
    
    .status-processing {
        background: #17a2b8;
        color: white;
    }
    
    .status-completed {
        background: #28a745;
        color: white;
    }
    
    .status-cancelled {
        background: #dc3545;
        color: white;
    }
    
    .payment-paid {
        background: #28a745;
        color: white;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 11px;
        display: inline-block;
    }
    
    .payment-unpaid {
        background: #dc3545;
        color: white;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 11px;
        display: inline-block;
    }
    
    .badge-delivery {
        background: #17a2b8;
        color: white;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 10px;
    }
    
    .badge-dinein {
        background: #6f42c1;
        color: white;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 10px;
    }
    
    .discount-badge {
        background: #28a745;
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 10px;
        display: inline-block;
    }
    
    .discount-amount {
        color: #28a745;
        font-weight: 600;
        font-size: 12px;
    }
    
    .original-price {
        text-decoration: line-through;
        color: #999;
        font-size: 11px;
    }
    
    .btn-detail {
        background: #C49A6C;
        color: white;
        border: none;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 12px;
    }
    
    .btn-detail:hover {
        background: #A67C52;
        color: white;
    }
    
    .table th {
        background: #f8f9fa;
        font-weight: 600;
    }
    
    .filter-section {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    
    .filter-btn {
        background: #f0f0f0;
        border: none;
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 13px;
        transition: all 0.3s ease;
    }
    
    .filter-btn.active {
        background: #C49A6C;
        color: white;
    }
    
    .filter-btn:hover {
        background: #A67C52;
        color: white;
    }
    
    /* Pagination Custom */
    .pagination-custom {
        display: flex;
        gap: 5px;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .pagination-custom li {
        display: inline-block;
    }
    
    .pagination-custom a,
    .pagination-custom span {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 8px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        background: #f8f9fa;
        color: #2C1810;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    
    .pagination-custom a:hover {
        background: #C49A6C;
        color: white;
    }
    
    .pagination-custom .active span {
        background: linear-gradient(135deg, #C49A6C, #A67C52);
        color: white;
    }
    
    .pagination-custom .disabled span {
        background: #e9ecef;
        color: #adb5bd;
        cursor: not-allowed;
    }
    
    .pagination-custom .page-prev span,
    .pagination-custom .page-next span {
        font-size: 11px;
        gap: 4px;
    }
    
    .pagination-custom .page-prev span i,
    .pagination-custom .page-next span i {
        font-size: 10px;
    }
</style>

<div class="dashboard-title">
    <h3><i class="fas fa-shopping-cart"></i> Semua Pesanan</h3>
    <p>Daftar lengkap semua pesanan (Delivery + Dine In)</p>
</div>

<!-- Statistik Ringkasan -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-value">{{ $totalOrders ?? 0 }}</div>
            <div class="stats-label">Total Pesanan</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-value text-warning">{{ $pendingOrders ?? 0 }}</div>
            <div class="stats-label">Pending</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-value text-info">{{ $processingOrders ?? 0 }}</div>
            <div class="stats-label">Processing</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-value text-success">{{ $completedOrders ?? 0 }}</div>
            <div class="stats-label">Completed</div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-section">
    <button class="filter-btn active" onclick="filterOrders('all')">Semua</button>
    <button class="filter-btn" onclick="filterOrders('delivery')">🚚 Delivery</button>
    <button class="filter-btn" onclick="filterOrders('dinein')">🍽️ Dine In</button>
    <button class="filter-btn" onclick="filterOrders('pending')">⏳ Pending</button>
    <button class="filter-btn" onclick="filterOrders('completed')">✅ Completed</button>
</div>

<!-- Tabel Pesanan dengan Diskon -->
<div class="chart-container">
    <div class="table-responsive">
        <table class="table table-hover" id="ordersTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipe</th>
                    <th>Order Number</th>
                    <th>Customer</th>
                    <th>Subtotal</th>
                    <th>Diskon</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Pembayaran</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                @php
                    // Inisialisasi variabel
                    $subtotal = 0;
                    $discountAmount = 0;
                    $discountCode = null;
                    $totalAmount = $order->total_amount ?? 0;
                    
                    // Cek apakah order memiliki data diskon
                    if(isset($order->discount_amount) && $order->discount_amount > 0) {
                        $discountAmount = $order->discount_amount;
                        $discountCode = $order->discount_code ?? 'Diskon';
                        // Subtotal = total + diskon - pajak (asumsi pajak 10% dari total setelah diskon)
                        // Atau jika ada subtotal langsung
                        if(isset($order->subtotal) && $order->subtotal > 0) {
                            $subtotal = $order->subtotal;
                        } else {
                            // Estimasi subtotal
                            $subtotal = $totalAmount + $discountAmount;
                        }
                    } else {
                        // Tidak ada diskon
                        $subtotal = $totalAmount;
                        $discountAmount = 0;
                        $discountCode = null;
                    }
                    
                    // Untuk Dine In, biasanya tidak ada diskon
                    if($order->order_type == 'Dine In') {
                        $subtotal = $totalAmount;
                        $discountAmount = 0;
                        $discountCode = null;
                    }
                @endphp
                <tr data-type="{{ $order->order_type == 'Delivery' ? 'delivery' : 'dinein' }}" 
                    data-status="{{ $order->status }}">
                    <td>#{{ $order->id }}</td>
                    <td>
                        @if($order->order_type == 'Delivery')
                            <span class="badge-delivery"><i class="fas fa-truck"></i> Delivery</span>
                        @else
                            <span class="badge-dinein"><i class="fas fa-utensils"></i> Dine In</span>
                        @endif
                    </td>
                    <td><strong>{{ $order->order_number }}</strong></td>
                    <td>
                        {{ $order->customer_name }}<br>
                        <small class="text-muted">{{ $order->customer_phone ?? '-' }}</small>
                    </td>
                    <td>
                        <span class="original-price">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </td>
                    <td>
                        @if($discountAmount > 0)
                            <div>
                                <span class="discount-badge">
                                    <i class="fas fa-tag"></i> {{ $discountCode }}
                                </span>
                                <div class="discount-amount">
                                    - Rp {{ number_format($discountAmount, 0, ',', '.') }}
                                </div>
                            </div>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <strong>Rp {{ number_format($totalAmount, 0, ',', '.') }}</strong>
                    </td>
                    <td>
                        @php
                            $statusClass = 'status-pending';
                            if($order->status == 'processing') $statusClass = 'status-processing';
                            elseif($order->status == 'completed') $statusClass = 'status-completed';
                            elseif($order->status == 'cancelled') $statusClass = 'status-cancelled';
                        @endphp
                        <span class="status-badge {{ $statusClass }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>
                        @if($order->payment_status == 'paid')
                            <span class="payment-paid"><i class="fas fa-check-circle"></i> Paid</span>
                        @else
                            <span class="payment-unpaid"><i class="fas fa-clock"></i> Unpaid</span>
                        @endif
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('owner.order.detail', $order->id) }}" class="btn-detail btn-sm">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p>Belum ada pesanan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links('pagination::bootstrap-4') }}
    </div>
</div>

<script>
    function filterOrders(filter) {
        const rows = document.querySelectorAll('#ordersTable tbody tr');
        const buttons = document.querySelectorAll('.filter-btn');
        
        // Update active button
        buttons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.textContent.trim().toLowerCase().includes(filter) || 
                (filter === 'all' && btn.textContent.trim() === 'Semua')) {
                btn.classList.add('active');
            }
        });
        
        rows.forEach(row => {
            const type = row.getAttribute('data-type');
            const status = row.getAttribute('data-status');
            
            if (filter === 'all') {
                row.style.display = '';
            } else if (filter === 'delivery') {
                row.style.display = type === 'delivery' ? '' : 'none';
            } else if (filter === 'dinein') {
                row.style.display = type === 'dinein' ? '' : 'none';
            } else if (filter === 'pending') {
                row.style.display = status === 'pending' ? '' : 'none';
            } else if (filter === 'completed') {
                row.style.display = status === 'completed' ? '' : 'none';
            } else {
                row.style.display = '';
            }
        });
    }
</script>
@endsection