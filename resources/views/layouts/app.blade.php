<!DOCTYPE html>
<html lang="id">
<head>
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#FF6B35">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Kopi Ancol">
    <link rel="apple-touch-icon" href="/images/icons/icon-192x192.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kopi Ancol - Premium Coffee Shop | Full Screen Experience</title>
    
    <!-- Bootstrap & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #f1e1e1 0%, #c3cfe2 100%);
            min-height: 100vh;
            width: 100%;
            overflow-x: hidden;
        }
        
        /* FULL LEBAR - semua container utama menggunakan max-width: 100% */
        .container, .container-fluid {
            width: 100%;
            max-width: 100%;
            padding-left: 2rem;
            padding-right: 2rem;
        }
        
        @media (max-width: 768px) {
            .container, .container-fluid {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
        
        /* Navbar full width tanpa batasan */
        .navbar {
            background: linear-gradient(135deg, #000000 0%, #000000 100%) !important;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
            width: 100%;
        }
        
        .navbar .container {
            max-width: 100%;
            padding: 0;
        }
        
        @media (max-width: 992px) {
            .navbar {
                padding: 0.8rem 1rem;
            }
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.8rem;
            background: linear-gradient(135deg, #FFD89B 0%, #C7A252 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            color: #D7CCC8 !important;
        }
        
        .nav-link:hover {
            color: #FFD89B !important;
            transform: translateY(-2px);
        }
        
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #FF6B35;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 11px;
            font-weight: bold;
            animation: pulse 1.5s infinite;
        }
        
        /* Modern Cart Notification Styles */
        .cart-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 350px;
            max-width: 450px;
            animation: slideInRight 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            cursor: pointer;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%) scale(0.8);
                opacity: 0;
            }
            to {
                transform: translateX(0) scale(1);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0) scale(1);
                opacity: 1;
            }
            to {
                transform: translateX(100%) scale(0.8);
                opacity: 0;
            }
        }
        
        .cart-notification.hide {
            animation: slideOutRight 0.3s ease forwards;
        }
        
        .notification-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(255, 255, 255, 0.1);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }
        
        .notification-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        
        .notification-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .notification-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .notification-header {
            padding: 15px 20px;
            background: rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .notification-body {
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .notification-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            animation: bounce 0.5s ease;
        }
        
        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
        
        .notification-content {
            flex: 1;
            color: white;
        }
        
        .notification-title {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }
        
        .notification-message {
            font-size: 14px;
            margin: 0;
            opacity: 0.95;
        }
        
        .notification-price {
            font-size: 16px;
            font-weight: bold;
            color: #ffd700;
            margin: 5px 0 0 0;
        }
        
        .notification-progress {
            height: 3px;
            background: rgba(255, 255, 255, 0.3);
            width: 100%;
            position: relative;
        }
        
        .notification-progress-bar {
            height: 100%;
            background: white;
            width: 100%;
            animation: progress 3s linear forwards;
        }
        
        @keyframes progress {
            from { width: 100%; }
            to { width: 0%; }
        }
        
        /* Floating Cart Animation */
        .floating-cart {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            animation: float 2s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        /* Confetti Canvas */
        #confetti-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9998;
        }
        
        /* Mini Cart Sidebar - full height */
        .mini-cart-sidebar {
            position: fixed;
            top: 0;
            right: -450px;
            width: 450px;
            height: 100vh;
            background: white;
            box-shadow: -5px 0 30px rgba(0, 0, 0, 0.2);
            z-index: 10000;
            transition: right 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            overflow-y: auto;
        }
        
        @media (max-width: 576px) {
            .mini-cart-sidebar {
                width: 100%;
                right: -100%;
            }
        }
        
        .mini-cart-sidebar.open {
            right: 0;
        }
        
        .mini-cart-header {
            background: linear-gradient(135deg, #2C1810 0%, #4A2C1A 100%);
            color: white;
            padding: 20px;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        
        .mini-cart-items {
            padding: 20px;
        }
        
        .mini-cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            display: none;
            backdrop-filter: blur(3px);
        }
        
        .overlay.show {
            display: block;
        }
        
        /* Ripple Effect */
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        }
        
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        /* Button Add to Cart Style */
        .btn-add-cart {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .btn-add-cart:active {
            transform: scale(0.95);
        }
        
        .btn-add-cart.added {
            background: #28a745 !important;
            border-color: #28a745 !important;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        /* Dropdown Menu */
        .dropdown-menu {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            margin-top: 10px;
        }
        
        .dropdown-item {
            padding: 8px 20px;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .dropdown-item:hover {
            background: #FFF8F0;
            color: #FF6B35;
            padding-left: 25px;
        }
        
        /* Main Content - FULL WIDTH dengan padding yang nyaman */
        main {
            min-height: calc(100vh - 200px);
            width: 100%;
        }
        
        /* Konten utama menggunakan lebar penuh */
        .content-full {
            width: 100%;
            padding: 2rem;
        }
        
        @media (max-width: 768px) {
            .content-full {
                padding: 1rem;
            }
        }
        
        /* Product Grid - Full width responsif */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            width: 100%;
        }
        
        /* Hero banner full width */
        .hero-banner {
            width: 100%;
            background: linear-gradient(135deg, #2C1810 0%, #6b3e1f 100%);
            padding: 4rem 2rem;
            margin-bottom: 2rem;
            border-radius: 0;
            color: white;
        }
        
        /* Footer full width */
        .footer {
            background: linear-gradient(135deg, #2C1810 0%, #4A2C1A 100%);
            color: white;
            padding: 50px 2rem 20px;
            margin-top: 60px;
            width: 100%;
        }
        
        .footer .container {
            max-width: 100%;
            padding: 0;
        }
        
        .footer a {
            color: #FFD89B;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer a:hover {
            color: white;
            padding-left: 5px;
        }
        
        .social-icons a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            margin: 0 5px;
            transition: all 0.3s ease;
        }
        
        .social-icons a:hover {
            background: #FF6B35;
            transform: translateY(-3px);
        }
        
        /* Alert Custom */
        .alert-custom {
            border-radius: 10px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        /* Card product styling */
        .product-card {
            background: white;
            border-radius: 1.5rem;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            height: 100%;
        }
        
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 35px rgba(0,0,0,0.1);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-banner {
                padding: 2rem 1rem;
            }
            
            .products-grid {
                gap: 1rem;
            }
            
            .cart-notification {
                min-width: 280px;
                max-width: 320px;
            }
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #FF6B35;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #e55a2b;
        }

        /* Update Notification Toast */
        .update-toast {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 16px;
            padding: 15px;
            color: white;
            z-index: 10002;
            transform: translateY(100px);
            transition: transform 0.3s ease;
            max-width: 400px;
            margin: 0 auto;
        }

        .update-toast.show {
            transform: translateY(0);
        }

        .update-toast-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .update-toast-content i {
            font-size: 24px;
        }

        .update-toast-content div {
            flex: 1;
        }

        .update-toast-content strong {
            display: block;
            font-size: 14px;
        }

        .update-toast-content small {
            font-size: 11px;
            opacity: 0.8;
        }

        .update-btn {
            background: white;
            border: none;
            padding: 6px 15px;
            border-radius: 20px;
            color: #667eea;
            font-weight: 600;
            cursor: pointer;
        }

        /* Network Status Toast */
        .network-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            border-radius: 12px;
            padding: 12px 20px;
            z-index: 10002;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            min-width: 250px;
        }

        .network-toast.show {
            transform: translateX(0);
        }

        .network-online {
            background: #28a745;
            color: white;
        }

        .network-offline {
            background: #dc3545;
            color: white;
        }

        .network-toast-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .network-toast-content i {
            font-size: 18px;
        }

        /* PWA Install Banner */
        .pwa-install-banner {
            position: fixed;
            bottom: 100px;
            left: 20px;
            right: 20px;
            background: linear-gradient(135deg, #2C1810 0%, #4A2C1A 100%);
            border-radius: 20px;
            padding: 15px;
            color: white;
            z-index: 10001;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            display: none;
            animation: slideUp 0.5s ease;
            max-width: 400px;
            margin: 0 auto;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .pwa-install-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .pwa-install-icon {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.1);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .pwa-install-text {
            flex: 1;
        }

        .pwa-install-text h6 {
            margin: 0 0 5px 0;
            font-weight: bold;
        }

        .pwa-install-text p {
            margin: 0;
            font-size: 12px;
            opacity: 0.9;
        }

        .pwa-install-buttons {
            display: flex;
            gap: 10px;
        }

        .pwa-install-btn {
            background: #FF6B35;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .pwa-install-btn:hover {
            background: #e55a2b;
            transform: scale(1.05);
        }

        .pwa-close-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .pwa-close-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        @media (max-width: 576px) {
            .pwa-install-banner {
                bottom: 90px;
                left: 10px;
                right: 10px;
            }
            .pwa-install-content {
                flex-wrap: wrap;
            }
            .pwa-install-buttons {
                width: 100%;
                justify-content: flex-end;
            }
        }
    </style>
</head>
<body>

<!-- PWA Install Banner -->
<div class="pwa-install-banner" id="pwa-install-banner">
    <div class="pwa-install-content">
        <div class="pwa-install-icon">
            <i class="fas fa-mug-hot"></i>
        </div>
        <div class="pwa-install-text">
            <h6>Instal Kopi Ancol App</h6>
            <p>Pasang aplikasi untuk pengalaman lebih nyaman dan akses cepat</p>
        </div>
        <div class="pwa-install-buttons">
            <button class="pwa-close-btn" id="pwa-close-btn">
                <i class="fas fa-times"></i>
            </button>
            <button class="pwa-install-btn" id="pwa-install-btn">
                <i class="fas fa-download"></i> Install
            </button>
        </div>
    </div>
</div>

<!-- Floating Cart Button -->
<div class="floating-cart no-print">
    <button class="btn btn-lg btn-primary rounded-circle shadow-lg" onclick="toggleMiniCart()" style="width: 60px; height: 60px; background: #FF6B35; border: none; box-shadow: 0 5px 20px rgba(0,0,0,0.2);">
        <i class="fas fa-shopping-cart fa-lg"></i>
        <span class="cart-count" id="floatingCartCount" style="position: absolute; top: -8px; right: -12px; background: #ff4757; color: white; border-radius: 50%; padding: 2px 6px; font-size: 10px; font-weight: bold;">0</span>
    </button>
</div>

<!-- Mini Cart Sidebar -->
<div class="overlay" id="overlay" onclick="toggleMiniCart()"></div>
<div class="mini-cart-sidebar" id="miniCartSidebar">
    <div class="mini-cart-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-shopping-cart"></i> Keranjang Belanja
            </h5>
            <button class="btn btn-sm btn-light" onclick="toggleMiniCart()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <div class="mini-cart-items" id="miniCartItems">
        @php
            // Sinkronisasi dengan cart.blade.php - menggunakan 'cart' tanpa titik
            $cart = session()->get('cart', []);
            $total = 0;
            $discount = session()->get('discount');
            $discountAmount = $discount['amount'] ?? 0;
        @endphp
        @if(count($cart) > 0)
            @foreach($cart as $id => $item)
                @php
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                @endphp
                <div class="mini-cart-item">
                    <div>
                        <strong>{{ $item['name'] ?? 'Produk' }}</strong>
                        <div class="text-muted small">{{ $item['quantity'] }}x @ Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div>
                        <span class="text-primary">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        <button class="btn btn-sm btn-link text-danger" onclick="removeFromCart({{ $id }})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center text-muted py-5">
                <i class="fas fa-shopping-cart fa-3x mb-3"></i><br>
                Keranjang kosong
            </div>
        @endif
    </div>
    <div class="p-3 border-top">
        <div class="d-flex justify-content-between mb-2">
            <span>Subtotal:</span>
            <strong id="miniCartSubtotal">Rp {{ number_format($total, 0, ',', '.') }}</strong>
        </div>
        @if($discountAmount > 0)
        <div class="d-flex justify-content-between mb-2 text-success">
            <span><i class="fas fa-tag"></i> Diskon:</span>
            <strong id="miniCartDiscount">- Rp {{ number_format($discountAmount, 0, ',', '.') }}</strong>
        </div>
        @endif
        <hr>
        <div class="d-flex justify-content-between mb-3">
            <strong>Total:</strong>
            <strong id="miniCartTotal" style="color: #FF6B35; font-size: 1.1rem;">
                Rp {{ number_format($total - $discountAmount, 0, ',', '.') }}
            </strong>
        </div>
        <a href="{{ route('cart.index') }}" class="btn btn-primary w-100" style="background: #FF6B35; border: none; border-radius: 30px;">
            <i class="fas fa-arrow-right"></i> Lihat Keranjang
        </a>
    </div>
</div>
<canvas id="confetti-canvas"></canvas>

<style>
/* Mini Cart Sidebar Styles */
.mini-cart-sidebar {
    position: fixed;
    top: 0;
    right: -420px;
    width: 400px;
    height: 100vh;
    background: white;
    box-shadow: -5px 0 30px rgba(0,0,0,0.15);
    z-index: 10000;
    transition: right 0.3s ease-in-out;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
}

.mini-cart-sidebar.open {
    right: 0;
}

.mini-cart-header {
    background: linear-gradient(135deg, #2C1810 0%, #4A2C1A 100%);
    color: white;
    padding: 20px;
    position: sticky;
    top: 0;
    z-index: 1;
}

.mini-cart-items {
    flex: 1;
    padding: 16px;
    overflow-y: auto;
    max-height: calc(100vh - 200px);
}

.mini-cart-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
    gap: 10px;
}

.mini-cart-item:last-child {
    border-bottom: none;
}

.mini-cart-product-info {
    flex: 2;
}

.mini-cart-product-name {
    font-size: 0.9rem;
    font-weight: 600;
    color: #2C1810;
    margin-bottom: 4px;
    line-height: 1.3;
}

.mini-cart-product-actions {
    text-align: right;
    display: flex;
    align-items: center;
    gap: 8px;
}

.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 9999;
    display: none;
    backdrop-filter: blur(3px);
}

.overlay.show {
    display: block;
}

.alert-sm {
    font-size: 11px;
    padding: 6px 10px;
    border-radius: 8px;
}

@media (max-width: 576px) {
    .mini-cart-sidebar {
        width: 100%;
        right: -100%;
    }
}

/* Scrollbar */
.mini-cart-items::-webkit-scrollbar {
    width: 4px;
}

.mini-cart-items::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.mini-cart-items::-webkit-scrollbar-thumb {
    background: #C49A6C;
    border-radius: 4px;
}

/* Floating Cart Button */
.floating-cart {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1000;
}

.cart-count {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #ff4757;
    color: white;
    border-radius: 50%;
    min-width: 20px;
    height: 20px;
    font-size: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
}
</style>

<script>
    let lastCartCount = {{ count(session()->get('cart', [])) }};
    
    // Toggle mini cart sidebar
    function toggleMiniCart() {
        const sidebar = document.getElementById('miniCartSidebar');
        const overlay = document.getElementById('overlay');
        
        if (sidebar) {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        
            if (sidebar.classList.contains('open')) {
                loadMiniCart();
            }
        }
    }
    
    // Close mini cart when clicking overlay
    document.getElementById('overlay')?.addEventListener('click', function() {
        toggleMiniCart();
    });
    
    // Load mini cart contents via AJAX
    function loadMiniCart() {
        fetch('{{ route("cart.mini") }}')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('miniCartItems');
                const subtotalElement = document.getElementById('miniCartSubtotal');
                const totalElement = document.getElementById('miniCartTotal');
                const discountElement = document.getElementById('miniCartDiscount');
                
                if (data.items && data.items.length > 0) {
                    let html = '';
                    data.items.forEach(item => {
                        html += `
                            <div class="mini-cart-item">
                                <div class="mini-cart-product-info">
                                    <div class="mini-cart-product-name">
                                        <strong>${escapeHtml(item.name)}</strong>
                                    </div>
                                    <div class="text-muted small">
                                        ${item.quantity}x @ Rp ${item.price.toLocaleString('id-ID')}
                                    </div>
                                </div>
                                <div class="mini-cart-product-actions">
                                    <span class="text-primary fw-semibold">Rp ${item.subtotal.toLocaleString('id-ID')}</span>
                                    <button class="btn btn-sm btn-link text-danger" onclick="removeFromCart(${item.id})" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                    
                    if (subtotalElement) {
                        subtotalElement.innerHTML = 'Rp ' + data.subtotal.toLocaleString('id-ID');
                    }
                    
                    if (data.discount_amount > 0) {
                        if (discountElement) {
                            discountElement.style.display = 'flex';
                            discountElement.innerHTML = `<span><i class="fas fa-tag"></i> Diskon:</span><strong>- Rp ${data.discount_amount.toLocaleString('id-ID')}</strong>`;
                        }
                        if (totalElement) {
                            totalElement.innerHTML = 'Rp ' + (data.subtotal - data.discount_amount).toLocaleString('id-ID');
                        }
                    } else {
                        if (discountElement) {
                            discountElement.style.display = 'none';
                        }
                        if (totalElement) {
                            totalElement.innerHTML = 'Rp ' + data.subtotal.toLocaleString('id-ID');
                        }
                    }
                } else {
                    container.innerHTML = '<div class="text-center text-muted py-5"><i class="fas fa-shopping-cart fa-3x mb-3"></i><br>Keranjang kosong</div>';
                    if (subtotalElement) subtotalElement.innerHTML = 'Rp 0';
                    if (totalElement) totalElement.innerHTML = 'Rp 0';
                    if (discountElement) discountElement.style.display = 'none';
                }
            })
            .catch(error => console.log('Error loading cart:', error));
    }
    
    // Remove from cart function
    function removeFromCart(cartItemId) {
        fetch('{{ route("cart.remove") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: cartItemId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartCount(data.cart_count);
                loadMiniCart();
                if (window.location.pathname.includes('/cart')) {
                    location.reload();
                }
            }
        })
        .catch(error => console.log('Error removing from cart:', error));
    }
    
    function updateCartCount(count) {
        const floatingCartCount = document.getElementById('floatingCartCount');
        if (floatingCartCount) {
            floatingCartCount.textContent = count;
            floatingCartCount.style.display = count > 0 ? 'flex' : 'none';
        }
        const navbarCartBadge = document.querySelector('.cart-badge');
        if (navbarCartBadge) {
            navbarCartBadge.style.display = count > 0 ? 'inline-block' : 'none';
            if (count > 0) navbarCartBadge.textContent = count;
        }
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>

<!-- Navbar Full Width -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid" style="padding: 0 2rem;">
        <a class="navbar-brand" href="{{ route('shop.index') }}">
            <i class="fas fa-mug-hot"></i> Kopi Ancol
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('shop.index') }}">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('shop.products') }}">
                        <i class="fas fa-coffee"></i> Produk
                    </a>
                </li>
                <li class="nav-item position-relative">
                    <a class="nav-link" href="{{ route('cart.index') }}">
                        <i class="fas fa-shopping-cart"></i> Cart
                        @php
                            $cartCount = count(session()->get('cart', []));
                        @endphp
                        @if($cartCount > 0)
                            <span class="cart-badge">{{ $cartCount }}</span>
                        @endif
                    </a>
                </li>
                
                <!-- Menu Auth -->
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('customer.dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('customer.orders') }}">
                                    <i class="fas fa-shopping-cart"></i> Pesanan Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('customer.profile') }}">
                                    <i class="fas fa-user-edit"></i> Profil Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('customer.reviews') }}">
                                    <i class="fas fa-star"></i> Review Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('customer.chat.index') }}">
                                    <i class="fas fa-comments"></i> Chat Admin
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="fas fa-user-plus"></i> Daftar
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content FULL WIDTH -->
<main class="py-4">
    <div class="container-fluid" style="padding-left: 2rem; padding-right: 2rem;">
        @if(session('success'))
            <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Ini adalah tempat konten utama (akan diisi oleh blade child) -->
        @yield('content')
        
    </div>
</main>

<!-- Footer Full Width -->
<footer class="footer">
    <div class="container-fluid" style="padding: 0 2rem;">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5><i class="fas fa-mug-hot"></i> Kopi Ancol</h5>
                <p>Kedai kopi premium yang menyajikan biji kopi terbaik dari Colol, Manggarai timur. Rasakan aroma dan cita rasa kopi otentik yang kaya</p>
                <div class="social-icons">
                    <a href="https://www.facebook.com/Iron" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://www.instagram.com/@Iron-vlts" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://www.twitter.com/kopiancol" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://wa.me/6281246135710" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('shop.index') }}">Home</a></li>
                    <li><a href="{{ route('shop.products') }}">Products</a></li>
                    <li><a href="{{ route(name: 'about.index')}}">About Us</a></li>
                    <li><a href="{{route(name: 'locations.index')}}">Locations</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Contact Info</h5>
                <ul class="list-unstyled">
                    <li><i class="fas fa-map-marker-alt"></i> Jl. Ruteng-Elar, Colol, Manggarai timur</li>
                    <li><i class="fas fa-phone"></i> +62 812-4613-5710</li>
                    <li><i class="fas fa-envelope"></i> iron@kopiancol.com</li>
                    <li><i class="far fa-clock"></i> Mon-Sun: 08:00 - 23:00</li>
                </ul>
            </div>
        </div>
        <hr class="bg-white">
        <div class="text-center">
            <p class="mb-0">&copy; 2026 Kopi Ancol. All rights reserved. <br> <small>Crafted with <i class="fas fa-heart text-danger"></i> for coffee lovers</small></p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Confetti Effect
    function startConfetti() {
        const canvas = document.getElementById('confetti-canvas');
        if (!canvas) return;
        
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        
        const particles = [];
        const colors = ['#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff', '#ffa500', '#ff69b4'];
        
        for (let i = 0; i < 150; i++) {
            particles.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height - canvas.height,
                size: Math.random() * 8 + 4,
                color: colors[Math.floor(Math.random() * colors.length)],
                speed: Math.random() * 5 + 3,
                rotation: Math.random() * 360,
                rotationSpeed: Math.random() * 10 - 5
            });
        }
        
        function animateConfetti() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            let stillActive = false;
            for (let i = 0; i < particles.length; i++) {
                const p = particles[i];
                p.y += p.speed;
                p.rotation += p.rotationSpeed;
                
                if (p.y < canvas.height + 50) {
                    stillActive = true;
                    ctx.save();
                    ctx.translate(p.x, p.y);
                    ctx.rotate(p.rotation * Math.PI / 180);
                    ctx.fillStyle = p.color;
                    ctx.fillRect(-p.size/2, -p.size/2, p.size, p.size);
                    ctx.restore();
                }
            }
            
            if (stillActive) {
                requestAnimationFrame(animateConfetti);
            } else {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            }
        }
        
        animateConfetti();
        
        setTimeout(() => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }, 3000);
    }
    
    // Enhanced Notification - Updated to correctly capture product name and price
    function showEnhancedNotification(productName, productPrice) {
        const existingNotifs = document.querySelectorAll('.cart-notification');
        existingNotifs.forEach(notif => notif.remove());
        
        const notification = document.createElement('div');
        notification.className = 'cart-notification';
        notification.onclick = () => toggleMiniCart();
        
        const messages = [
            'ditambahkan ke keranjang! 🛒',
            'siap untuk dinikmati! ☕',
            'masuk ke keranjang belanja! 🎉',
            'tunggu apa lagi? Checkout sekarang! 💫',
            'pilihan yang tepat! 🌟',
            'selamat menikmati kopi spesial! ✨',
            'berhasil ditambahkan! 🎯',
            'siap menemani harimu! ☀️'
        ];
        const randomMessage = messages[Math.floor(Math.random() * messages.length)];
        
        // Format price dengan Rupiah
        const formattedPrice = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(productPrice);
        
        notification.innerHTML = `
            <div class="notification-card notification-success">
                <div class="notification-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <small><i class="fas fa-check-circle"></i> Berhasil!</small>
                        <small>Baru saja</small>
                    </div>
                </div>
                <div class="notification-body">
                    <div class="notification-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">
                            <strong>${escapeHtml(productName)}</strong>
                        </div>
                        <div class="notification-message">
                            ${randomMessage}
                        </div>
                        <div class="notification-price">
                            ${formattedPrice}
                        </div>
                    </div>
                </div>
                <div class="notification-progress">
                    <div class="notification-progress-bar"></div>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (notification) {
                notification.classList.add('hide');
                setTimeout(() => {
                    if (notification && notification.remove) {
                        notification.remove();
                    }
                }, 300);
            }
        }, 3000);
    }
    
    // Helper function untuk escape HTML
    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        }).replace(/[\uD800-\uDBFF][\uDC00-\uDFFF]/g, function(c) {
            return c;
        });
    }
    
    // Improved function to get product details from card
    function getProductDetailsFromCard(card) {
        if (!card) return { name: 'Produk', price: 0 };
        
        let productName = '';
        let productPrice = 0;
        
        // Try to find product name from various possible selectors
        const titleElement = card.querySelector('.product-title, h4, h5, .card-title, [class*="title"]');
        if (titleElement) {
            productName = titleElement.innerText.trim();
        }
        
        // If still empty, try to find from product-body
        if (!productName) {
            const bodyElement = card.querySelector('.product-body');
            if (bodyElement) {
                const firstStrong = bodyElement.querySelector('strong, h4, h5');
                if (firstStrong) {
                    productName = firstStrong.innerText.trim();
                }
            }
        }
        
        // Fallback name
        if (!productName) {
            productName = 'Kopi Spesial';
        }
        
        // Try to find product price from various possible selectors
        const priceElement = card.querySelector('.product-price, .price, [class*="price"]');
        if (priceElement) {
            let priceText = priceElement.innerText.trim();
            // Extract numbers from price text (remove Rp, dots, etc)
            const priceMatch = priceText.match(/(\d+[.,]?\d*)/);
            if (priceMatch) {
                productPrice = parseInt(priceMatch[0].replace(/[.,]/g, ''));
            }
        }
        
        // If price still 0, try to find from any element with price format
        if (productPrice === 0) {
            const allText = card.innerText;
            const pricePattern = /Rp\s*([\d.,]+)/i;
            const match = allText.match(pricePattern);
            if (match) {
                productPrice = parseInt(match[1].replace(/[.,]/g, ''));
            }
        }
        
        // Validate price
        if (isNaN(productPrice) || productPrice === 0) {
            productPrice = 25000;
        }
        
        return { name: productName, price: productPrice };
    }
    
    // Play sound effect
    function playAddToCartSound() {
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.value = 880;
            gainNode.gain.value = 0.3;
            
            oscillator.start();
            gainNode.gain.exponentialRampToValueAtTime(0.00001, audioContext.currentTime + 0.5);
            oscillator.stop(audioContext.currentTime + 0.5);
        } catch(e) {
            console.log('Web Audio not supported');
        }
    }
    
    // Update cart count - FIXED: Memastikan notifikasi terupdate
    function updateCartCount(count) {
        const floatingCartCount = document.getElementById('floatingCartCount');
        const navbarCartBadge = document.querySelector('.cart-badge');
        
        console.log('Updating cart count to:', count); // Debug log
        
        if (floatingCartCount) {
            floatingCartCount.textContent = count;
            floatingCartCount.style.animation = 'none';
            setTimeout(() => {
                floatingCartCount.style.animation = 'pulse 0.5s ease';
            }, 10);
        }
        
        if (navbarCartBadge) {
            if (count > 0) {
                navbarCartBadge.textContent = count;
                navbarCartBadge.style.display = 'inline-block';
            } else {
                navbarCartBadge.style.display = 'none';
            }
        }
        
        // Also update mini cart count if sidebar is open
        if (count > 0) {
            // Optional: Refresh mini cart content
            loadMiniCart();
        }
    }
    
    // Toggle mini cart sidebar
    function toggleMiniCart() {
        const sidebar = document.getElementById('miniCartSidebar');
        const overlay = document.getElementById('overlay');
        
        if (sidebar) {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
            
            if (sidebar.classList.contains('open')) {
                loadMiniCart();
            }
        }
    }
    
    // Load mini cart contents - FIXED: Mengambil data terbaru dari server
    function loadMiniCart() {
        fetch('{{ route("cart.mini") }}', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Mini cart data:', data); // Debug log
            const container = document.getElementById('miniCartItems');
            if (container) {
                if (data.items && data.items.length > 0) {
                    let html = '';
                    data.items.forEach(item => {
                        html += `
                            <div class="mini-cart-item">
                                <div>
                                    <strong>${escapeHtml(item.name)}</strong>
                                    <div class="text-muted small">${item.quantity}x @ Rp ${item.price.toLocaleString('id-ID')}</div>
                                </div>
                                <div>
                                    <span class="text-primary">Rp ${item.subtotal.toLocaleString('id-ID')}</span>
                                    <button class="btn btn-sm btn-link text-danger" onclick="removeFromCart(${item.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                    document.getElementById('miniCartTotal').innerHTML = `Rp ${data.total.toLocaleString('id-ID')}`;
                } else {
                    container.innerHTML = '<div class="text-center text-muted py-5"><i class="fas fa-shopping-cart fa-3x mb-3"></i><br>Keranjang kosong</div>';
                    document.getElementById('miniCartTotal').innerHTML = 'Rp 0';
                }
            }
        })
        .catch(error => console.log('Error loading cart:', error));
    }
    
    // Remove from cart - FIXED: Memastikan ID dikirim dengan benar
    function removeFromCart(cartItemId) {
        console.log('Removing item with ID:', cartItemId);
        
        fetch('{{ route("cart.remove") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ cart_item_id: cartItemId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Item removed successfully, new count:', data.cart_count);
                updateCartCount(data.cart_count);
                loadMiniCart();
                showRemovalNotification();
            } else {
                console.error('Failed to remove item:', data.message);
            }
        })
        .catch(error => console.log('Error removing from cart:', error));
    }
    
    // Show notification when item removed from cart
    function showRemovalNotification() {
        const notification = document.createElement('div');
        notification.className = 'cart-notification';
        
        notification.innerHTML = `
            <div class="notification-card notification-warning">
                <div class="notification-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <small><i class="fas fa-trash-alt"></i> Dihapus</small>
                        <small>Baru saja</small>
                    </div>
                </div>
                <div class="notification-body">
                    <div class="notification-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">
                            <strong>Item dihapus</strong>
                        </div>
                        <div class="notification-message">
                            Produk telah dihapus dari keranjang 🗑️
                        </div>
                    </div>
                </div>
                <div class="notification-progress">
                    <div class="notification-progress-bar"></div>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('hide');
            setTimeout(() => notification.remove(), 300);
        }, 2000);
    }
    
    // Function to refresh cart after form submission
    function refreshCartAfterAdd() {
        // Fetch latest cart count
        fetch('{{ route("cart.count") }}', {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Refreshed cart count:', data.count);
            updateCartCount(data.count);
            loadMiniCart();
        })
        .catch(error => console.log('Error refreshing cart:', error));
    }
    
    // Initialize all event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Initial cart count
        refreshCartAfterAdd();
        
        // Handle all Add to Cart buttons (including dynamically added ones)
        function handleAddToCartButtons() {
            // For button with class btn-add-cart
            document.querySelectorAll('.btn-add-cart').forEach(btn => {
                // Remove existing listener to avoid duplicates
                btn.removeEventListener('click', handleCartClick);
                btn.addEventListener('click', handleCartClick);
            });
            
            // For form submissions
            document.querySelectorAll('form[action*="cart.add"]').forEach(form => {
                form.removeEventListener('submit', handleFormSubmit);
                form.addEventListener('submit', handleFormSubmit);
            });
        }
        
        function handleCartClick(e) {
            // Ripple effect
            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            const rect = e.currentTarget.getBoundingClientRect();
            ripple.style.left = (e.clientX - rect.left) + 'px';
            ripple.style.top = (e.clientY - rect.top) + 'px';
            e.currentTarget.style.position = 'relative';
            e.currentTarget.style.overflow = 'hidden';
            e.currentTarget.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
            
            // Find product card
            let productCard = e.currentTarget.closest('.product-card');
            if (!productCard) {
                productCard = e.currentTarget.closest('.card');
            }
            
            // Get product details and show notification
            const productDetails = getProductDetailsFromCard(productCard);
            showEnhancedNotification(productDetails.name, productDetails.price);
            
            // Play sound
            try {
                playAddToCartSound();
            } catch(e) {}
            
            // Start confetti
            startConfetti();
            
            // Add animation to button
            e.currentTarget.classList.add('added');
            setTimeout(() => {
                e.currentTarget.classList.remove('added');
            }, 500);
            
            // Refresh cart after a short delay to allow server processing
            setTimeout(() => {
                refreshCartAfterAdd();
            }, 500);
        }
        
        function handleFormSubmit(e) {
            // Get product card
            let productCard = e.currentTarget.closest('.product-card');
            if (!productCard) {
                productCard = e.currentTarget.closest('.card');
            }
            
            const productDetails = getProductDetailsFromCard(productCard);
            
            // Show notification
            showEnhancedNotification(productDetails.name, productDetails.price);
            startConfetti();
            
            // Play sound
            try {
                playAddToCartSound();
            } catch(e) {}
            
            // Refresh cart after form submission
            setTimeout(() => {
                refreshCartAfterAdd();
            }, 1000);
        }
        
        // Initial binding
        handleAddToCartButtons();
        
        // Use MutationObserver to handle dynamically added elements
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length > 0) {
                    handleAddToCartButtons();
                }
            });
        });
        
        observer.observe(document.body, { childList: true, subtree: true });
    });

    // ========== PWA Installation ==========
    let deferredPrompt;
    let pwaInstallBanner = document.getElementById('pwa-install-banner');
    let pwaInstallBtn = document.getElementById('pwa-install-btn');
    let pwaCloseBtn = document.getElementById('pwa-close-btn');

    // Register Service Worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then((registration) => {
                    console.log('Service Worker registered with scope:', registration.scope);
                    
                    // Check for updates
                    registration.addEventListener('updatefound', () => {
                        const newWorker = registration.installing;
                        console.log('New Service Worker installing...');
                        
                        newWorker.addEventListener('statechange', () => {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                console.log('New update available!');
                                showUpdateNotification();
                            }
                        });
                    });
                })
                .catch((error) => {
                    console.log('Service Worker registration failed:', error);
                });
        });
    }

    // Handle beforeinstallprompt event
    window.addEventListener('beforeinstallprompt', (e) => {
        console.log('beforeinstallprompt event fired');
        e.preventDefault();
        deferredPrompt = e;
        
        // Show install banner (if not dismissed before)
        const pwaDismissed = localStorage.getItem('pwa-banner-dismissed');
        if (!pwaDismissed) {
            showPWAInstallBanner();
        }
    });

    // Show PWA install banner
    function showPWAInstallBanner() {
        if (pwaInstallBanner) {
            pwaInstallBanner.style.display = 'block';
        }
    }

    // Hide PWA install banner
    function hidePWAInstallBanner() {
        if (pwaInstallBanner) {
            pwaInstallBanner.style.display = 'none';
        }
    }

    // Install PWA
    if (pwaInstallBtn) {
        pwaInstallBtn.addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                console.log(`User response to the install prompt: ${outcome}`);
                deferredPrompt = null;
                hidePWAInstallBanner();
                localStorage.setItem('pwa-banner-dismissed', 'true');
            }
        });
    }

    // Close install banner
    if (pwaCloseBtn) {
        pwaCloseBtn.addEventListener('click', () => {
            hidePWAInstallBanner();
            localStorage.setItem('pwa-banner-dismissed', 'true');
        });
    }

    // Show update notification
    function showUpdateNotification() {
        const updateToast = document.createElement('div');
        updateToast.className = 'update-toast';
        updateToast.innerHTML = `
            <div class="update-toast-content">
                <i class="fas fa-sync-alt"></i>
                <div>
                    <strong>Update Tersedia!</strong>
                    <small>Klik untuk refresh aplikasi</small>
                </div>
                <button onclick="location.reload()" class="update-btn">Update</button>
            </div>
        `;
        document.body.appendChild(updateToast);
        
        setTimeout(() => {
            updateToast.classList.add('show');
        }, 100);
        
        setTimeout(() => {
            updateToast.remove();
        }, 10000);
    }

    // Check if app is installed
    function isPWAInstalled() {
        if (window.matchMedia('(display-mode: standalone)').matches) {
            console.log('App is running in standalone mode (installed)');
            return true;
        }
        return false;
    }

    // Set user online/offline status
    window.addEventListener('online', () => {
        console.log('Network connection restored');
        showNetworkStatus('online', 'Koneksi kembali online');
    });

    window.addEventListener('offline', () => {
        console.log('Network connection lost');
        showNetworkStatus('offline', 'Koneksi terputus! Beberapa fitur mungkin tidak tersedia');
    });

    function showNetworkStatus(status, message) {
        const toast = document.createElement('div');
        toast.className = `network-toast network-${status}`;
        toast.innerHTML = `
            <div class="network-toast-content">
                <i class="fas ${status === 'online' ? 'fa-wifi' : 'fa-plug'}"></i>
                <span>${message}</span>
            </div>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('show');
        }, 100);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
</script>

@stack('scripts')
</body>
</html>