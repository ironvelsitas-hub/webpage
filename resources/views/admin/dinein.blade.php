@extends('layouts.admin')

@section('content')
<style>
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
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .order-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }
    .order-header {
        background: linear-gradient(135deg, #2C1810 0%, #4A2C1A 100%);
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
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.7; }
        100% { transform: scale(1); opacity: 1; }
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
    
    .toast-notification {
        position: fixed;
        top: 70px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        animation: slideInRight 0.3s ease;
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    .order-card.new-order {
        animation: highlight 2s ease;
    }
    
    @keyframes highlight {
        0% { background: #fff3cd; transform: scale(1); }
        50% { background: #ffeaa7; transform: scale(1.02); }
        100% { background: white; transform: scale(1); }
    }
    
    .sound-toggle {
        cursor: pointer;
        margin-left: 10px;
        color: #6c757d;
    }
    
    .sound-toggle:hover {
        color: #2C1810;
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
        .btn, .nav, .sidebar, .navbar-top, .filter-section, .stats-card {
            display: none !important;
        }
    }
    
    /* Struk Styles */
    .struk-container {
        max-width: 350px;
        margin: 0 auto;
        background: white;
        padding: 20px;
        font-family: monospace;
        font-size: 12px;
    }
    .struk-header {
        text-align: center;
        margin-bottom: 15px;
        border-bottom: 1px dashed #000;
        padding-bottom: 10px;
    }
    .struk-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: bold;
    }
    .struk-header p {
        margin: 5px 0;
        font-size: 11px;
    }
    .struk-divider {
        border-top: 1px dashed #000;
        margin: 10px 0;
    }
    .struk-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }
    .struk-total {
        border-top: 1px solid #000;
        margin-top: 10px;
        padding-top: 10px;
        font-weight: bold;
    }
    .struk-footer {
        text-align: center;
        margin-top: 15px;
        border-top: 1px dashed #000;
        padding-top: 10px;
        font-size: 10px;
    }
    
    /* New Order Badge */
    .new-order-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: bold;
        animation: bounce 1s infinite;
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
</style>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-utensils" style="color: #FF6B35;"></i> Dine In Orders
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
        
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary ms-2">
            <i class="fas fa-arrow-left"></i> Back
        </a>
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

<!-- Orders Grid -->
<div class="row no-print" id="ordersGrid">
    @forelse($orders as $order)
    @php
        $items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
        $totalItems = 0;
        foreach($items as $item) {
            $totalItems += $item['quantity'];
        }
        $isNew = $order->created_at->diffInMinutes(now()) < 5 && !session('dinein_viewed_' . $order->id);
    @endphp
    <div class="col-md-6 col-lg-4 order-item" 
         data-order-id="{{ $order->id }}"
         data-order-number="{{ $order->order_number }}"
         data-customer-name="{{ $order->customer_name }}"
         data-table-number="{{ $order->table_number }}">
        <div class="order-card {{ $isNew ? 'new-order' : '' }}">
            @if($isNew)
            <div class="new-order-badge">NEW</div>
            @endif
            <div class="order-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-receipt"></i>
                        <strong>{{ $order->order_number }}</strong>
                        <br>
                        <small>#{{ $order->id }}</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-light text-dark">Meja {{ $order->table_number }}</span>
                        <br>
                        <small>{{ $order->created_at->format('H:i:s') }}</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <i class="fas fa-user"></i> 
                    <strong>{{ $order->customer_name }}</strong>
                    <br>
                    <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                </div>
                
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
                
                @if($order->notes)
                <div class="alert alert-info py-1 mb-2">
                    <small><i class="fas fa-sticky-note"></i> {{ $order->notes }}</small>
                </div>
                @endif
                
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <form action="{{ route('admin.dinein.status', $order->id) }}" method="POST" class="status-form">
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
                        <form action="{{ route('admin.dinein.payment', $order->id) }}" method="POST" class="payment-form">
                            @csrf
                            @method('PATCH')
                            <select name="payment_status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>💳 Pending</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>✅ Paid</option>
                            </select>
                        </form>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-info flex-grow-1" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}" onclick="markAsRead({{ $order->id }})">
                        <i class="fas fa-eye"></i> Detail
                    </button>
                    <button type="button" class="btn btn-sm btn-success" onclick="printStruk({{ $order->id }})">
                        <i class="fas fa-print"></i> Struk
                    </button>
                    <form action="{{ route('admin.dinein.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Hapus pesanan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Detail -->
    <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: #2C1810; color: white;">
                    <h5 class="modal-title">Detail Pesanan - {{ $order->order_number }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2"><strong>Order Number:</strong> {{ $order->order_number }}</div>
                    <div class="mb-2"><strong>Customer:</strong> {{ $order->customer_name }}</div>
                    <div class="mb-2"><strong>Meja:</strong> {{ $order->table_number }}</div>
                    <div class="mb-2"><strong>Waktu:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</div>
                    <hr>
                    <strong>Items:</strong>
                    <table class="table table-sm mt-2">
                        <thead>
                            <tr><th>Qty</th><th>Product</th><th class="text-end">Subtotal</th></tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>{{ $item['quantity'] }}x</td>
                                <td>{{ $item['name'] }}</td>
                                <td class="text-end">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr><th colspan="2" class="text-end">Total:</th>
                                <th class="text-end">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                    @if($order->notes)
                        <hr>
                        <strong>Notes:</strong>
                        <p class="text-muted">{{ $order->notes }}</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="printStruk({{ $order->id }})">
                        <i class="fas fa-print"></i> Cetak Struk
                    </button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle fa-2x mb-2"></i>
            <h5>Belum ada pesanan dine in</h5>
            <p>Pesanan akan muncul di sini ketika customer melakukan pemesanan di tempat.</p>
        </div>
    </div>
    @endforelse
</div>

<!-- Area Cetak Struk (Hidden) -->
@foreach($orders as $order)
@php
    $items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
@endphp
<div id="strukArea{{ $order->id }}" style="display: none;">
    <div class="struk-container">
        <div class="struk-header">
            <h3>KOPI ANCOL</h3>
            <p>Jl. ruteng-elar, colol, Manggarai Timur</p>
            <p>Telp: 0812-4613-5710</p>
            <p>{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
        </div>
        
        <div class="struk-divider"></div>
        
        <div class="struk-row">
            <span>No. Order:</span>
            <span><strong>{{ $order->order_number }}</strong></span>
        </div>
        <div class="struk-row">
            <span>Meja:</span>
            <span><strong>{{ $order->table_number }}</strong></span>
        </div>
        <div class="struk-row">
            <span>Customer:</span>
            <span><strong>{{ $order->customer_name }}</strong></span>
        </div>
        
        <div class="struk-divider"></div>
        
        @foreach($items as $item)
        <div class="struk-row">
            <span>{{ $item['name'] }} x{{ $item['quantity'] }}</span>
            <span>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
        </div>
        @endforeach
        
        <div class="struk-divider"></div>
        
        <div class="struk-row struk-total">
            <span><strong>TOTAL</strong></span>
            <span><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></span>
        </div>
        
        @if($order->payment_status == 'paid')
        <div class="struk-row">
            <span>Status Pembayaran:</span>
            <span><strong class="text-success">LUNAS</strong></span>
        </div>
        @else
        <div class="struk-row">
            <span>Status Pembayaran:</span>
            <span><strong class="text-danger">BELUM BAYAR</strong></span>
        </div>
        @endif
        
        <div class="struk-footer">
            <p>Terima kasih telah berkunjung!</p>
            <p>☕ Selamat menikmati kopi Anda ☕</p>
            <p>~ Keep Calm & Drink Coffee ~</p>
        </div>
    </div>
</div>
@endforeach

<script>
    // Notification System for Dine In Orders
    let lastOrderCount = {{ $orders->count() }};
    let soundEnabled = localStorage.getItem('dineinSoundEnabled') !== 'false';
    let notificationList = JSON.parse(localStorage.getItem('dineinNotifications') || '[]');
    let unreadCount = notificationList.filter(n => !n.read).length;
    
    // Initialize
    function init() {
        updateNotificationBadge();
        if (soundEnabled) {
            document.getElementById('soundIcon').className = 'fas fa-volume-up';
        } else {
            document.getElementById('soundIcon').className = 'fas fa-volume-mute';
        }
        startPolling();
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
    
    // Add new notification
    function addNotification(orderData) {
        const notification = {
            id: Date.now(),
            orderId: orderData.id,
            orderNumber: orderData.orderNumber,
            customerName: orderData.customerName,
            tableNumber: orderData.tableNumber,
            time: new Date().toISOString(),
            read: false
        };
        
        notificationList.unshift(notification);
        unreadCount++;
        localStorage.setItem('dineinNotifications', JSON.stringify(notificationList));
        updateNotificationBadge();
        
        // Show toast
        showToast(orderData);
        
        // Play sound
        if (soundEnabled) {
            playNotificationSound();
        }
        
        // Highlight new order
        highlightNewOrder(orderData.id);
    }
    
    // Show toast notification
    function showToast(orderData) {
        const toastContainer = document.createElement('div');
        toastContainer.className = 'toast-notification';
        toastContainer.innerHTML = `
            <div class="toast show" role="alert">
                <div class="toast-header" style="background: #28a745; color: white;">
                    <i class="fas fa-utensils me-2"></i>
                    <strong class="me-auto">Pesanan Dine In Baru!</strong>
                    <small>Baru saja</small>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    <strong>${orderData.customerName}</strong> - Meja ${orderData.tableNumber}
                    <br>
                    <small>No. Order: ${orderData.orderNumber}</small>
                    <hr class="my-1">
                    <button class="btn btn-sm btn-primary" onclick="scrollToOrder(${orderData.id})">
                        <i class="fas fa-eye"></i> Lihat Pesanan
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(toastContainer);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toastContainer.parentNode) {
                toastContainer.remove();
            }
        }, 5000);
        
        // Close button functionality
        const closeBtn = toastContainer.querySelector('.btn-close');
        if (closeBtn) {
            closeBtn.onclick = () => toastContainer.remove();
        }
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
        dropdown.style.top = (bell.offsetTop + 35) + 'px';
        dropdown.style.right = '0';
        
        if (notificationList.length === 0) {
            dropdown.innerHTML = '<div class="text-center p-3">Tidak ada notifikasi</div>';
        } else {
            let html = '<div class="dropdown-header bg-light"><strong>Notifikasi Pesanan Dine In</strong></div>';
            notificationList.forEach(notif => {
                const time = new Date(notif.time).toLocaleTimeString('id-ID');
                html += `
                    <div class="notification-item ${!notif.read ? 'unread' : ''}" onclick="markAsRead(${notif.id})">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>${notif.customerName}</strong>
                                <div class="small">Meja ${notif.tableNumber} - ${notif.orderNumber}</div>
                                <div class="notification-time">${time}</div>
                            </div>
                            ${!notif.read ? '<span class="badge bg-warning">Baru</span>' : ''}
                        </div>
                    </div>
                `;
            });
            html += '<div class="dropdown-footer text-center p-2"><button class="btn btn-sm btn-link" onclick="clearAllNotifications()">Hapus Semua</button></div>';
            dropdown.innerHTML = html;
        }
        
        bell.parentNode.style.position = 'relative';
        bell.parentNode.appendChild(dropdown);
        
        // Close dropdown when clicking outside
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
            localStorage.setItem('dineinNotifications', JSON.stringify(notificationList));
            
            // Scroll to order if orderId exists
            if (notification.orderId) {
                scrollToOrder(notification.orderId);
            }
        }
        
        // Close dropdown
        const dropdown = document.getElementById('notificationsDropdown');
        if (dropdown) dropdown.remove();
    }
    
    // Mark order as viewed when detail opened
    function markOrderAsViewed(orderId) {
        // Mark any notifications for this order as read
        notificationList.forEach(notif => {
            if (notif.orderId == orderId && !notif.read) {
                notif.read = true;
                unreadCount--;
            }
        });
        updateNotificationBadge();
        localStorage.setItem('dineinNotifications', JSON.stringify(notificationList));
        
        // Send to server
        fetch('{{ route("admin.dinein.mark-viewed") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ order_id: orderId })
        }).catch(e => console.log('Error marking as viewed:', e));
    }
    
    // Clear all notifications
    function clearAllNotifications() {
        notificationList = [];
        unreadCount = 0;
        localStorage.setItem('dineinNotifications', JSON.stringify(notificationList));
        updateNotificationBadge();
        const dropdown = document.getElementById('notificationsDropdown');
        if (dropdown) dropdown.remove();
    }
    
    // Toggle sound
    function toggleSound() {
        soundEnabled = !soundEnabled;
        localStorage.setItem('dineinSoundEnabled', soundEnabled);
        const icon = document.getElementById('soundIcon');
        icon.className = soundEnabled ? 'fas fa-volume-up' : 'fas fa-volume-mute';
        
        // Show feedback
        const toastContainer = document.createElement('div');
        toastContainer.className = 'toast-notification';
        toastContainer.innerHTML = `
            <div class="toast show" role="alert">
                <div class="toast-header">
                    <i class="fas fa-bell me-2"></i>
                    <strong class="me-auto">Notifikasi Suara</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    Suara notifikasi ${soundEnabled ? 'diaktifkan' : 'dimatikan'}
                </div>
            </div>
        `;
        document.body.appendChild(toastContainer);
        setTimeout(() => toastContainer.remove(), 2000);
    }
    
    // Polling for new orders
    function startPolling() {
        setInterval(checkNewOrders, 10000); // Check every 10 seconds
    }
    
    function checkNewOrders() {
        fetch('{{ route("admin.dinein.check-new") }}')
            .then(response => response.json())
            .then(data => {
                if (data.newOrders && data.newOrders.length > 0) {
                    data.newOrders.forEach(order => {
                        addNotification({
                            id: order.id,
                            orderNumber: order.order_number,
                            customerName: order.customer_name,
                            tableNumber: order.table_number
                        });
                    });
                    // Refresh to show new orders
                    location.reload();
                }
            })
            .catch(error => console.log('Polling error:', error));
    }
    
    // Print struk function
    function printStruk(orderId) {
        const printContent = document.getElementById('strukArea' + orderId).innerHTML;
        
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Struk Pesanan - Kopi Ancol</title>
                <style>
                    body {
                        font-family: monospace;
                        margin: 0;
                        padding: 20px;
                        background: white;
                    }
                    .struk-container {
                        max-width: 350px;
                        margin: 0 auto;
                        background: white;
                        padding: 20px;
                        font-family: monospace;
                        font-size: 12px;
                    }
                    .struk-header {
                        text-align: center;
                        margin-bottom: 15px;
                        border-bottom: 1px dashed #000;
                        padding-bottom: 10px;
                    }
                    .struk-header h3 {
                        margin: 0;
                        font-size: 16px;
                        font-weight: bold;
                    }
                    .struk-header p {
                        margin: 5px 0;
                        font-size: 11px;
                    }
                    .struk-divider {
                        border-top: 1px dashed #000;
                        margin: 10px 0;
                    }
                    .struk-row {
                        display: flex;
                        justify-content: space-between;
                        margin-bottom: 5px;
                    }
                    .struk-total {
                        border-top: 1px solid #000;
                        margin-top: 10px;
                        padding-top: 10px;
                        font-weight: bold;
                    }
                    .struk-footer {
                        text-align: center;
                        margin-top: 15px;
                        border-top: 1px dashed #000;
                        padding-top: 10px;
                        font-size: 10px;
                    }
                    @media print {
                        body {
                            margin: 0;
                            padding: 0;
                        }
                    }
                </style>
            </head>
            <body>
                ${printContent}
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(function() {
                            window.close();
                        }, 500);
                    };
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
    
    // Request notification permission
    if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
        Notification.requestPermission();
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        init();
        
        // Mark as viewed when modal opens
        document.querySelectorAll('[data-bs-target^="#orderModal"]').forEach(button => {
            button.addEventListener('click', function() {
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