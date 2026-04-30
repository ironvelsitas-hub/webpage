@extends('layouts.admin')

@section('content')
<style>
    /* Previous styles remain the same */
    .stats-card {
        border-radius: 12px;
        transition: all 0.3s ease;
        margin-bottom: 20px;
        border: none;
    }
    .order-card {
        border-radius: 12px;
        transition: all 0.3s ease;
        margin-bottom: 20px;
        border: none;
        box-shadow: 0 2px 8px rgba(214, 28, 28, 0.08);
    }
    .order-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(199, 9, 9, 0.15);
    }
    .order-header {
        background: linear-gradient(135deg, #dd5925 0%, #221107 100%);
        color: white;
        padding: 12px 20px;
        border-radius: 12px 12px 0 0;
    }
    .badge-pending { background: #FFC107; color: #856404; padding: 5px 10px; border-radius: 20px; font-size: 11px; }
    .badge-processing { background: #17A2B8; color: white; padding: 5px 10px; border-radius: 20px; font-size: 11px; }
    .badge-completed { background: #28A745; color: white; padding: 5px 10px; border-radius: 20px; font-size: 11px; }
    .badge-cancelled { background: #DC3545; color: white; padding: 5px 10px; border-radius: 20px; font-size: 11px; }
    .badge-paid { background: #28A745; color: white; padding: 5px 10px; border-radius: 20px; font-size: 11px; }
    .badge-unpaid { background: #DC3545; color: white; padding: 5px 10px; border-radius: 20px; font-size: 11px; }
    .badge-delivered { background: #28A745; color: white; padding: 5px 10px; border-radius: 20px; font-size: 11px; }
    .badge-shipped { background: #17A2B8; color: white; padding: 5px 10px; border-radius: 20px; font-size: 11px; }
    .badge-pending-delivery { background: #6C757D; color: white; padding: 5px 10px; border-radius: 20px; font-size: 11px; }
    
    /* Filter Section */
    .filter-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        border: 1px solid #e0e0e0;
    }
    
    /* Print Styles */
    @media print {
        body * {
            visibility: hidden;
        }
        .print-area, .print-area * {
            visibility: visible;
        }
        .print-area {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 20px;
            background: white;
        }
        .no-print {
            display: none !important;
        }
    }
    
    /* Row Status Colors for Cards */
    .card-pending { border-left: 4px solid #FFC107; }
    .card-processing { border-left: 4px solid #17A2B8; }
    .card-completed { border-left: 4px solid #28A745; }
    .card-cancelled { border-left: 4px solid #DC3545; }
    
    /* Notification Styles */
    .notification-bell {
        position: relative;
        cursor: pointer;
        margin-right: 15px;
    }
    
    .notification-badge {
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
    
    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.2);
            opacity: 0.7;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    .notification-dropdown {
        width: 350px;
        max-height: 400px;
        overflow-y: auto;
    }
    
    .notification-item {
        padding: 10px;
        border-bottom: 1px solid #eee;
        transition: background 0.2s;
        cursor: pointer;
    }
    
    .notification-item:hover {
        background: #f8f9fa;
    }
    
    .notification-item.unread {
        background: #fff3cd;
        border-left: 3px solid #ffc107;
    }
    
    .notification-time {
        font-size: 10px;
        color: #6c757d;
    }
    
    /* Toast Notification */
    .toast-notification {
        position: fixed;
        top: 70px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        animation: slideInRight 0.3s ease;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    /* New Order Highlight */
    .order-card.new-order {
        animation: highlight 2s ease;
    }
    
    @keyframes highlight {
        0% {
            background: #fff3cd;
            transform: scale(1);
        }
        50% {
            background: #ffeaa7;
            transform: scale(1.02);
        }
        100% {
            background: white;
            transform: scale(1);
        }
    }
    
    /* Sound Icon */
    .sound-toggle {
        cursor: pointer;
        margin-left: 10px;
        color: #6c757d;
    }
    
    .sound-toggle:hover {
        color: #2C1810;
    }
    
    /* Delete button loading state */
    .btn-delete-loading {
        opacity: 0.7;
        pointer-events: none;
    }
    
    /* Custom confirmation modal */
    .confirm-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .confirm-modal-content {
        background: white;
        border-radius: 16px;
        max-width: 400px;
        width: 90%;
        animation: modalSlideIn 0.3s ease;
    }
    
    @keyframes modalSlideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-shopping-cart" style="color: #e71010;"></i> Manajemen Pesanan
    </h1>
    <div class="d-flex align-items-center no-print">
        <!-- Notification Bell -->
        <div class="notification-bell" onclick="toggleNotifications()">
            <i class="fas fa-bell fa-lg"></i>
            <span class="notification-badge" id="notificationCount">0</span>
        </div>
        
        <!-- Sound Toggle -->
        <div class="sound-toggle" onclick="toggleSound()" title="On/Off Notifikasi Suara">
            <i class="fas fa-volume-up" id="soundIcon"></i>
        </div>
        
        <button class="btn btn-sm btn-success ms-2" onclick="exportToExcel()">
            <i class="fas fa-file-excel"></i> Export Excel
        </button>
        <button class="btn btn-sm btn-primary ms-1" onclick="window.print()">
            <i class="fas fa-print"></i> Print
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4 no-print">
    <div class="col-md-3">
        <div class="card stats-card bg-primary text-white">
            <div class="card-body">
                <h6 class="card-title">Total Pesanan</h6>
                <h3 class="mb-0">{{ $orders->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card bg-warning text-dark">
            <div class="card-body">
                <h6 class="card-title">Pending</h6>
                <h3 class="mb-0">{{ $pendingOrders }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card bg-info text-white">
            <div class="card-body">
                <h6 class="card-title">Processing</h6>
                <h3 class="mb-0">{{ $processingOrders }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card bg-success text-white">
            <div class="card-body">
                <h6 class="card-title">Completed</h6>
                <h3 class="mb-0">{{ $completedOrders }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-section no-print">
    <div class="row align-items-end g-2">
        <div class="col-md-3">
            <label class="form-label small fw-bold text-muted">STATUS PESANAN</label>
            <select id="statusFilter" class="form-select form-select-sm" onchange="filterOrders()">
                <option value="all">Semua Status</option>
                <option value="pending">🟡 Pending</option>
                <option value="processing">🔵 Processing</option>
                <option value="completed">✅ Completed</option>
                <option value="cancelled">❌ Cancelled</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-muted">STATUS PEMBAYARAN</label>
            <select id="paymentFilter" class="form-select form-select-sm" onchange="filterOrders()">
                <option value="all">Semua Pembayaran</option>
                <option value="unpaid">💳 Belum Dibayar</option>
                <option value="paid">✅ Sudah Dibayar</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-muted">STATUS PENGIRIMAN</label>
            <select id="deliveryFilter" class="form-select form-select-sm" onchange="filterOrders()">
                <option value="all">Semua Pengiriman</option>
                <option value="pending">⏳ Pending</option>
                <option value="shipped">🚚 Shipped</option>
                <option value="delivered">✅ Delivered</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-muted">CARI PESANAN</label>
            <div class="input-group">
                <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari order, nama, atau no telepon..." onkeyup="filterOrders()">
                <button class="btn btn-sm btn-secondary" onclick="resetFilters()">
                    <i class="fas fa-undo-alt"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Orders Grid - Card View -->
<div class="row" id="ordersGrid">
    @forelse($orders as $order)
    @php
        $items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
        $totalItems = 0;
        foreach($items as $item) {
            $totalItems += $item['quantity'];
        }
        
        $statusClass = '';
        $statusText = '';
        $cardClass = '';
        switch($order->status) {
            case 'pending': 
                $statusClass = 'badge-pending'; 
                $statusText = 'Pending';
                $cardClass = 'card-pending';
                break;
            case 'processing': 
                $statusClass = 'badge-processing'; 
                $statusText = 'Processing';
                $cardClass = 'card-processing';
                break;
            case 'completed': 
                $statusClass = 'badge-completed'; 
                $statusText = 'Completed';
                $cardClass = 'card-completed';
                break;
            default: 
                $statusClass = 'badge-cancelled'; 
                $statusText = 'Cancelled';
                $cardClass = 'card-cancelled';
        }
        
        $paymentClass = $order->payment_status == 'paid' ? 'badge-paid' : 'badge-unpaid';
        $paymentText = $order->payment_status == 'paid' ? 'Paid' : 'Unpaid';
        
        $deliveryClass = 'badge-pending-delivery';
        $deliveryText = 'Pending';
        if(($order->delivery_status ?? 'pending') == 'shipped') {
            $deliveryClass = 'badge-shipped';
            $deliveryText = 'Shipped';
        } elseif(($order->delivery_status ?? 'pending') == 'delivered') {
            $deliveryClass = 'badge-delivered';
            $deliveryText = 'Delivered';
        }
        
        // Check if order is new (created in last 5 minutes and not viewed)
        $isNew = $order->created_at->diffInMinutes(now()) < 5 && !session('viewed_order_' . $order->id);
    @endphp
    <div class="col-md-6 col-lg-4 order-item" 
         data-status="{{ $order->status }}" 
         data-payment="{{ $order->payment_status }}" 
         data-delivery="{{ $order->delivery_status ?? 'pending' }}"
         data-search="{{ strtolower($order->order_number . ' ' . $order->customer_name . ' ' . $order->customer_phone) }}"
         data-order-id="{{ $order->id }}"
         data-order-number="{{ $order->order_number }}"
         data-order-name="{{ $order->customer_name }}"
         data-order-time="{{ $order->created_at->format('H:i:s') }}">
        <div class="order-card {{ $cardClass }} {{ $isNew ? 'new-order' : '' }}">
            <div class="order-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-receipt"></i>
                        <strong>{{ $order->order_number }}</strong>
                        <br>
                        <small>#{{ $order->id }}</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-light text-dark">{{ $order->payment_method ?? 'N/A' }}</span>
                        <br>
                        <small>{{ $order->created_at->format('H:i:s') }}</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Customer Info -->
                <div class="mb-2">
                    <i class="fas fa-user"></i> 
                    <strong>{{ $order->customer_name }}</strong>
                    <br>
                    <small class="text-muted">{{ $order->customer_phone }}</small>
                    <br>
                    <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                </div>
                
                <!-- Address -->
                <div class="mb-2">
                    <i class="fas fa-map-marker-alt"></i>
                    <small class="text-muted">{{ Str::limit($order->customer_address, 50) }}</small>
                </div>
                
                <!-- Items -->
                <div class="bg-light rounded p-2 mb-3">
                    <small class="text-muted">📦 Items Ordered:</small>
                    @foreach($items as $item)
                    <div class="d-flex justify-content-between py-1">
                        <div>
                            <strong>{{ $item['quantity'] }}x</strong> {{ $item['name'] }}
                        </div>
                        <div>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Total -->
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <i class="fas fa-coins"></i> <strong>Total:</strong>
                        <br>
                        <small class="text-muted">{{ $totalItems }} item(s)</small>
                    </div>
                    <div>
                        <h5 class="text-success mb-0">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h5>
                    </div>
                </div>
                
                <!-- Status Badges -->
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        <span class="{{ $statusClass }}">{{ $statusText }}</span>
                    </div>
                    <div>
                        <span class="{{ $paymentClass }}">{{ $paymentText }}</span>
                    </div>
                    <div>
                        <span class="{{ $deliveryClass }}">{{ $deliveryText }}</span>
                    </div>
                </div>
                
                <!-- Notes -->
                @if($order->notes)
                <div class="alert alert-info py-1 mb-2">
                    <small><i class="fas fa-sticky-note"></i> {{ Str::limit($order->notes, 50) }}</small>
                </div>
                @endif
                
                <!-- Actions -->
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>🟡 Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>🔵 Processing</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                            </select>
                        </form>
                    </div>
                    <div class="col-6">
                        <form action="{{ route('admin.orders.payment', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="payment_status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>💳 Unpaid</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>✅ Paid</option>
                            </select>
                        </form>
                    </div>
                </div>
                
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <form action="{{ route('admin.orders.delivery', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="delivery_status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="pending" {{ ($order->delivery_status ?? 'pending') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                <option value="shipped" {{ ($order->delivery_status ?? 'pending') == 'shipped' ? 'selected' : '' }}>🚚 Shipped</option>
                                <option value="delivered" {{ ($order->delivery_status ?? 'pending') == 'delivered' ? 'selected' : '' }}>✅ Delivered</option>
                            </select>
                        </form>
                    </div>
                    <div class="col-6">
                        <!-- PERBAIKAN: Tombol Hapus dengan JavaScript -->
                        <button type="button" class="btn btn-sm btn-danger w-100" onclick="confirmDeleteOrder({{ $order->id }}, '{{ addslashes($order->order_number) }}')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-info flex-grow-1" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">
                        <i class="fas fa-eye"></i> Detail
                    </button>
                    <a href="https://wa.me/{{ $order->customer_phone }}?text=Halo%20{{ urlencode($order->customer_name) }}%2C%20pesanan%20anda%20dengan%20nomor%20{{ $order->order_number }}%20sedang%20kami%20proses." class="btn btn-sm btn-success flex-grow-1" target="_blank">
                        <i class="fab fa-whatsapp"></i> Chat
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Detail -->
    <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: #2C1810; color: white;">
                    <h5 class="modal-title">Detail Pesanan - {{ $order->order_number }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <strong><i class="fas fa-user"></i> Informasi Customer</strong>
                                </div>
                                <div class="card-body">
                                    <p class="mb-1"><strong>Nama:</strong> {{ $order->customer_name }}</p>
                                    <p class="mb-1"><strong>Email:</strong> {{ $order->customer_email ?? '-' }}</p>
                                    <p class="mb-1"><strong>Telepon:</strong> {{ $order->customer_phone }}</p>
                                    <p class="mb-0"><strong>Alamat:</strong> {{ $order->customer_address }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <strong><i class="fas fa-info-circle"></i> Informasi Order</strong>
                                </div>
                                <div class="card-body">
                                    <p class="mb-1"><strong>Status:</strong> <span class="{{ $statusClass }}">{{ $statusText }}</span></p>
                                    <p class="mb-1"><strong>Pembayaran:</strong> <span class="{{ $paymentClass }}">{{ $paymentText }}</span></p>
                                    <p class="mb-1"><strong>Pengiriman:</strong> <span class="{{ $deliveryClass }}">{{ $deliveryText }}</span></p>
                                    <p class="mb-0"><strong>Tanggal:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header bg-light">
                            <strong><i class="fas fa-box"></i> Item Pesanan</strong>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                    <tr>
                                        <td>{{ $item['name'] ?? '-' }}</td>
                                        <td class="text-center">{{ $item['quantity'] ?? 0 }}x</td>
                                        <td class="text-end">Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</td>
                                        <td class="text-end">Rp {{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Total:</td>
                                        <td class="text-end fw-bold text-success">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    
                    @if($order->notes)
                    <div class="alert alert-info mt-3">
                        <strong><i class="fas fa-sticky-note"></i> Catatan:</strong><br>
                        {{ $order->notes }}
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="https://wa.me/{{ $order->customer_phone }}?text=Halo%20{{ urlencode($order->customer_name) }}%2C%20pesanan%20anda%20dengan%20nomor%20{{ $order->order_number }}%20sedang%20kami%20proses." class="btn btn-success" target="_blank">
                        <i class="fab fa-whatsapp"></i> Chat Customer
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle fa-2x mb-2"></i>
            <h5>Belum ada pesanan</h5>
            <p>Pesanan akan muncul setelah customer checkout</p>
        </div>
    </div>
    @endforelse
</div>

<script>
    // Notifications System (same as before)
    let lastOrderCount = {{ $orders->count() }};
    let soundEnabled = localStorage.getItem('notificationSound') !== 'false';
    let notificationList = [];
    let unreadCount = 0;
    
    // Initialize notifications
    function initNotifications() {
        loadNotifications();
        updateNotificationBadge();
        startPolling();
        
        if (!soundEnabled) {
            document.getElementById('soundIcon').className = 'fas fa-volume-mute';
        }
    }
    
    // Load stored notifications
    function loadNotifications() {
        const stored = localStorage.getItem('orderNotifications');
        if (stored) {
            notificationList = JSON.parse(stored);
            unreadCount = notificationList.filter(n => !n.read).length;
        }
    }
    
    // Save notifications
    function saveNotifications() {
        localStorage.setItem('orderNotifications', JSON.stringify(notificationList));
    }
    
    // Add new notification
    function addNotification(orderData) {
        const notification = {
            id: Date.now(),
            orderId: orderData.id,
            orderNumber: orderData.number,
            customerName: orderData.name,
            time: new Date().toISOString(),
            read: false
        };
        
        notificationList.unshift(notification);
        unreadCount++;
        saveNotifications();
        updateNotificationBadge();
        showToast(orderData);
        
        if (soundEnabled) {
            playNotificationSound();
        }
        
        highlightNewOrder(orderData.id);
    }
    
    // Update notification badge
    function updateNotificationBadge() {
        const badge = document.getElementById('notificationCount');
        if (unreadCount > 0) {
            badge.textContent = unreadCount;
            badge.style.display = 'block';
        } else {
            badge.style.display = 'none';
        }
    }
    
    // Show toast notification
    function showToast(orderData) {
        const toastContainer = document.createElement('div');
        toastContainer.className = 'toast-notification';
        toastContainer.innerHTML = `
            <div class="toast show" role="alert">
                <div class="toast-header" style="background: #28a745; color: white;">
                    <i class="fas fa-shopping-cart me-2"></i>
                    <strong class="me-auto">Pesanan Baru!</strong>
                    <small>Baru saja</small>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    <strong>${orderData.name}</strong> telah melakukan pesanan
                    <br>
                    <small>No. Order: ${orderData.number}</small>
                    <hr class="my-1">
                    <button class="btn btn-sm btn-primary" onclick="scrollToOrder(${orderData.id})">
                        <i class="fas fa-eye"></i> Lihat Pesanan
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(toastContainer);
        
        setTimeout(() => {
            toastContainer.remove();
        }, 5000);
    }
    
    // Play notification sound
    function playNotificationSound() {
        try {
            const audio = new Audio('https://www.soundjay.com/misc/sounds/bell-ringing-05.mp3');
            audio.volume = 0.5;
            audio.play().catch(e => console.log('Audio play failed:', e));
        } catch(e) {
            console.log('Sound not supported');
        }
    }
    
    // Highlight new order
    function highlightNewOrder(orderId) {
        const orderCard = document.querySelector(`.order-item[data-order-id="${orderId}"] .order-card`);
        if (orderCard) {
            orderCard.classList.add('new-order');
            setTimeout(() => {
                orderCard.classList.remove('new-order');
            }, 3000);
        }
    }
    
    // Scroll to specific order
    function scrollToOrder(orderId) {
        const orderElement = document.querySelector(`.order-item[data-order-id="${orderId}"]`);
        if (orderElement) {
            orderElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            highlightNewOrder(orderId);
        }
    }
    
    // Toggle notifications dropdown
    function toggleNotifications() {
        let dropdown = document.getElementById('notificationsDropdown');
        
        if (dropdown) {
            dropdown.remove();
        } else {
            createNotificationsDropdown();
        }
    }
    
    // Create notifications dropdown
    function createNotificationsDropdown() {
        const bell = document.querySelector('.notification-bell');
        const dropdown = document.createElement('div');
        dropdown.id = 'notificationsDropdown';
        dropdown.className = 'dropdown-menu show notification-dropdown';
        dropdown.style.position = 'absolute';
        dropdown.style.top = bell.offsetTop + 30 + 'px';
        dropdown.style.right = '0';
        
        if (notificationList.length === 0) {
            dropdown.innerHTML = '<div class="text-center p-3">Tidak ada notifikasi</div>';
        } else {
            let html = '<div class="dropdown-header">Notifikasi Pesanan</div>';
            notificationList.forEach(notif => {
                html += `
                    <div class="notification-item ${!notif.read ? 'unread' : ''}" onclick="markAsRead(${notif.id})">
                        <div>
                            <strong>${notif.customerName}</strong>
                            <div class="small">Pesanan baru: ${notif.orderNumber}</div>
                            <div class="notification-time">${new Date(notif.time).toLocaleTimeString()}</div>
                        </div>
                    </div>
                `;
            });
            dropdown.innerHTML = html;
        }
        
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
    
    // Mark notification as read
    function markAsRead(notificationId) {
        const notification = notificationList.find(n => n.id === notificationId);
        if (notification && !notification.read) {
            notification.read = true;
            unreadCount--;
            updateNotificationBadge();
            saveNotifications();
            scrollToOrder(notification.orderId);
        }
        
        const dropdown = document.getElementById('notificationsDropdown');
        if (dropdown) dropdown.remove();
    }
    
    // Toggle sound
    function toggleSound() {
        soundEnabled = !soundEnabled;
        localStorage.setItem('notificationSound', soundEnabled);
        const icon = document.getElementById('soundIcon');
        icon.className = soundEnabled ? 'fas fa-volume-up' : 'fas fa-volume-mute';
    }
    
    // Polling for new orders
    function startPolling() {
        setInterval(checkNewOrders, 10000);
    }
    
    function checkNewOrders() {
        fetch('{{ route("admin.orders.check-new") }}')
            .then(response => response.json())
            .then(data => {
                if (data.newOrders && data.newOrders.length > 0) {
                    data.newOrders.forEach(order => {
                        addNotification({
                            id: order.id,
                            number: order.order_number,
                            name: order.customer_name
                        });
                    });
                    location.reload();
                }
            })
            .catch(error => console.log('Polling error:', error));
    }
    
    // Mark order as viewed
    function markOrderAsViewed(orderId) {
        fetch('{{ route("admin.orders.mark-viewed") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ order_id: orderId })
        });
    }
    
    // Filter functions
    function filterOrders() {
        const status = document.getElementById('statusFilter').value;
        const payment = document.getElementById('paymentFilter').value;
        const delivery = document.getElementById('deliveryFilter').value;
        const search = document.getElementById('searchInput').value.toLowerCase();
        const items = document.querySelectorAll('.order-item');
        let visibleCount = 0;
        
        items.forEach(item => {
            const itemStatus = item.getAttribute('data-status');
            const itemPayment = item.getAttribute('data-payment');
            const itemDelivery = item.getAttribute('data-delivery');
            const itemSearch = item.getAttribute('data-search');
            
            let show = true;
            if (status !== 'all' && itemStatus !== status) show = false;
            if (payment !== 'all' && itemPayment !== payment) show = false;
            if (delivery !== 'all' && itemDelivery !== delivery) show = false;
            if (search && !itemSearch.includes(search)) show = false;
            
            item.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });
    }
    
    function resetFilters() {
        document.getElementById('statusFilter').value = 'all';
        document.getElementById('paymentFilter').value = 'all';
        document.getElementById('deliveryFilter').value = 'all';
        document.getElementById('searchInput').value = '';
        filterOrders();
    }
    
    function exportToExcel() {
        const items = document.querySelectorAll('.order-item');
        let csv = [['No', 'Order Number', 'Customer Name', 'Phone', 'Address', 'Total', 'Status', 'Payment', 'Delivery', 'Date']];
        let no = 1;
        
        items.forEach(item => {
            if (item.style.display !== 'none') {
                const orderCard = item.querySelector('.order-card');
                const orderHeader = orderCard.querySelector('.order-header');
                const orderBody = orderCard.querySelector('.card-body');
                
                const orderNumber = orderHeader.querySelector('strong')?.innerText || '';
                const customerName = orderBody.querySelector('.mb-2 strong')?.innerText || '';
                const customerPhone = orderBody.querySelector('.mb-2 .text-muted')?.innerText || '';
                const address = orderBody.querySelector('.fa-map-marker-alt + small')?.innerText || '';
                const total = orderBody.querySelector('.text-success')?.innerText || '';
                const statusBadge = orderBody.querySelector('.badge-pending, .badge-processing, .badge-completed, .badge-cancelled')?.innerText || '';
                const paymentBadge = orderBody.querySelectorAll('.badge-paid, .badge-unpaid')[0]?.innerText || '';
                const deliveryBadge = orderBody.querySelectorAll('.badge-delivered, .badge-shipped, .badge-pending-delivery')[0]?.innerText || '';
                const date = orderHeader.querySelector('small')?.innerText || '';
                
                csv.push([no++, orderNumber, customerName, customerPhone, address, total, statusBadge, paymentBadge, deliveryBadge, date]);
            }
        });
        
        const blob = new Blob([csv.map(row => row.join(',')).join('\n')], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'orders_' + new Date().toISOString().slice(0,19) + '.csv';
        a.click();
        URL.revokeObjectURL(url);
    }
    
    // ========== PERBAIKAN: Fungsi Hapus Pesanan ==========
    function confirmDeleteOrder(orderId, orderNumber) {
        // Buat custom confirmation modal
        const overlay = document.createElement('div');
        overlay.className = 'confirm-modal-overlay';
        overlay.innerHTML = `
            <div class="confirm-modal-content">
                <div class="modal-header" style="background: #dc3545; color: white; border-radius: 16px 16px 0 0;">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" onclick="this.closest('.confirm-modal-overlay').remove()"></button>
                </div>
                <div class="modal-body p-4">
                    <p>Apakah Anda yakin ingin menghapus pesanan <strong>${orderNumber}</strong>?</p>
                    <p class="text-danger mb-0"><small>Tindakan ini tidak dapat dibatalkan!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="this.closest('.confirm-modal-overlay').remove()">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="button" class="btn btn-danger" onclick="deleteOrder(${orderId})">
                        <i class="fas fa-trash"></i> Ya, Hapus
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(overlay);
        
        // Tutup modal jika klik di luar
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                overlay.remove();
            }
        });
    }
    
    function deleteOrder(orderId) {
        // Tampilkan loading pada tombol
        const confirmModal = document.querySelector('.confirm-modal-overlay');
        const deleteBtn = confirmModal?.querySelector('.btn-danger');
        if (deleteBtn) {
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghapus...';
            deleteBtn.disabled = true;
        }
        
        // Kirim request DELETE
        fetch('{{ url("admin/orders") }}/' + orderId, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hapus card dari DOM
                const orderCard = document.querySelector(`.order-item[data-order-id="${orderId}"]`);
                if (orderCard) {
                    orderCard.remove();
                }
                
                // Tampilkan notifikasi sukses
                showSuccessToast('Pesanan berhasil dihapus');
                
                // Update statistik (opsional)
                updateStatistics();
                
                // Tutup modal konfirmasi
                if (confirmModal) {
                    confirmModal.remove();
                }
            } else {
                throw new Error(data.message || 'Gagal menghapus pesanan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorToast(error.message);
            
            // Reset tombol
            if (deleteBtn) {
                deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Ya, Hapus';
                deleteBtn.disabled = false;
            }
        });
    }
    
    function showSuccessToast(message) {
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.innerHTML = `
            <div class="toast show" role="alert">
                <div class="toast-header" style="background: #28a745; color: white;">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong class="me-auto">Berhasil!</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
    
    function showErrorToast(message) {
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.innerHTML = `
            <div class="toast show" role="alert">
                <div class="toast-header" style="background: #dc3545; color: white;">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong class="me-auto">Gagal!</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
    
    function updateStatistics() {
        // Update jumlah pesanan yang terlihat
        const visibleOrders = document.querySelectorAll('.order-item').length;
        const statsCards = document.querySelectorAll('.stats-card h3');
        if (statsCards.length > 0) {
            statsCards[0].textContent = visibleOrders;
        }
        
        // Hitung ulang status
        let pending = 0, processing = 0, completed = 0;
        document.querySelectorAll('.order-item').forEach(item => {
            const status = item.getAttribute('data-status');
            if (status === 'pending') pending++;
            else if (status === 'processing') processing++;
            else if (status === 'completed') completed++;
        });
        
        if (statsCards.length > 1) statsCards[1].textContent = pending;
        if (statsCards.length > 2) statsCards[2].textContent = processing;
        if (statsCards.length > 3) statsCards[3].textContent = completed;
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initNotifications();
        
        // Mark orders as viewed when modal opens
        document.querySelectorAll('[data-bs-target^="#orderModal"]').forEach(button => {
            button.addEventListener('click', function() {
                const modalId = this.getAttribute('data-bs-target');
                const orderItem = this.closest('.order-item');
                if (orderItem) {
                    const orderId = orderItem.getAttribute('data-order-id');
                    markOrderAsViewed(orderId);
                }
            });
        });
    });
</script>
@endsection