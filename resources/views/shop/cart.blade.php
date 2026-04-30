@extends('layouts.app')

@section('content')
<style>
    /* Modern Cart Styles */
    .cart-container {
        background: rgb(250, 249, 249);
        min-height: 100vh;
        padding: 40px 0;
    }
    
    .cart-header {
        margin-bottom: 30px;
    }
    
    .cart-header h2 {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #2C1810, #4A2C1A);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Cart Table Modern */
    .cart-table-wrapper {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    
    .cart-table thead th {
        padding: 16px 20px;
        font-weight: 600;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    
    .cart-table tbody td {
        padding: 20px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .product-image {
        width: 60px;
        height: 60px;
        background: #f8f9fa;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .product-name {
        font-weight: 600;
        color: #2C1810;
        margin-bottom: 4px;
    }
    
    .product-category {
        font-size: 0.7rem;
        color: #C49A6C;
    }
    
    /* Quantity Input Modern */
    .quantity-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .quantity-input {
        width: 65px;
        text-align: center;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 8px 0;
        font-weight: 600;
    }
    
    /* Summary Card Modern */
    .summary-card {
        background: white;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        position: sticky;
        top: 100px;
    }
    
    .summary-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2C1810;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
        margin-bottom: 20px;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 0.9rem;
    }
    
    .summary-total {
        border-top: 2px solid #f0f0f0;
        padding-top: 15px;
        margin-top: 10px;
    }
    
    /* Discount Card Modern */
    .discount-card {
        background: white;
        border-radius: 24px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
    
    .discount-input-group {
        display: flex;
        gap: 10px;
    }
    
    .discount-input {
        flex: 1;
        border: 1px solid #e0e0e0;
        border-radius: 16px;
        padding: 12px 16px;
        transition: all 0.2s;
    }
    
    .discount-input:focus {
        border-color: #C49A6C;
        outline: none;
        box-shadow: 0 0 0 3px rgba(196, 154, 108, 0.1);
    }
    
    .btn-apply {
        background: linear-gradient(135deg, #C49A6C, #A67C52);
        color: white;
        border: none;
        padding: 0 20px;
        border-radius: 16px;
        font-weight: 600;
        transition: all 0.2s;
    }
    
    .btn-apply:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(196, 154, 108, 0.3);
    }
    
    /* Button Checkout */
    .btn-checkout-modern {
        background: linear-gradient(135deg, #FF6B35, #FF8C42);
        color: white;
        border: none;
        padding: 14px;
        border-radius: 30px;
        font-weight: 700;
        width: 100%;
        display: block;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .btn-checkout-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 107, 53, 0.3);
        color: white;
    }
    
    .btn-continue {
        background: white;
        border: 1px solid #C49A6C;
        color: #C49A6C;
        padding: 10px 20px;
        border-radius: 30px;
        transition: all 0.2s;
    }
    
    .btn-continue:hover {
        background: #C49A6C;
        color: white;
    }
    
    /* Empty Cart */
    .empty-cart {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 24px;
    }
    
    .empty-cart-icon {
        font-size: 80px;
        color: #C49A6C;
        margin-bottom: 20px;
        opacity: 0.5;
    }
    
    .discount-badge {
        background: #e8f5e9;
        color: #2C1810;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .discount-badge:hover {
        background: #c8e6c9;
        transform: translateY(-2px);
    }
    
    .progress-shipping {
        height: 6px;
        border-radius: 10px;
        background-color: #e0e0e0;
    }
    
    .progress-shipping-bar {
        height: 100%;
        border-radius: 10px;
        background: linear-gradient(90deg, #28a745, #20c997);
        transition: width 0.3s ease;
    }
    
    .alert-custom {
        border-radius: 16px;
        border: none;
        font-size: 0.8rem;
    }
    
    @media (max-width: 768px) {
        .cart-container {
            padding: 20px 0;
        }
        .summary-card {
            margin-top: 20px;
        }
        .discount-input-group {
            flex-direction: column;
        }
        .btn-apply {
            padding: 10px;
        }
    }
</style>

<div class="cart-container">
    <div class="container">
        <div class="cart-header">
            <h2><i class="fas fa-shopping-cart" style="color: #FF6B35;"></i> Shopping Cart</h2>
            <p class="text-muted">Review your items before checkout</p>
        </div>

        @php
            $cart = session()->get('cart', []);
            $total = 0;
            $discount = session()->get('discount');
            $discountAmount = $discount['amount'] ?? 0;
        @endphp

        @if(count($cart) > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="cart-table-wrapper">
                    <table class="table cart-table mb-0">
                        <thead style="background: rgb(241, 73, 6); color: white;">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $id => $details)
                                @php
                                    $subtotal = $details['price'] * $details['quantity'];
                                    $total += $subtotal;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="product-image">
                                                @if(isset($details['image']) && $details['image'] && file_exists(public_path($details['image'])))
                                                    <img src="{{ asset($details['image']) }}" alt="{{ $details['name'] }}">
                                                @else
                                                    <i class="fas fa-mug-hot fa-2x" style="color: #C49A6C;"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="product-name">{{ $details['name'] }}</div>
                                                <div class="product-category">Premium Coffee</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                                    <td>
                                        <div class="quantity-wrapper">
                                            <form action="{{ route('cart.update') }}" method="POST" class="d-flex align-items-center gap-2">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="id" value="{{ $id }}">
                                                <input type="number" name="quantity" value="{{ $details['quantity'] }}" 
                                                       min="1" class="quantity-input" 
                                                       onchange="this.form.submit()">
                                            </form>
                                        </div>
                                    </td>
                                    <td class="fw-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    <td>
                                        <form action="{{ route('cart.remove') }}" method="POST" onsubmit="return confirm('Remove this item from cart?')">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <button type="submit" class="btn btn-sm btn-danger rounded-circle" style="width: 32px; height: 32px;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    <a href="{{ route('shop.products') }}" class="btn btn-continue">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Discount Card -->
                <div class="discount-card">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="fas fa-tag text-primary"></i>
                        <h5 class="mb-0 fw-bold">Kode Diskon / Voucher</h5>
                    </div>
                    <div class="discount-input-group">
                        <input type="text" 
                               id="discountCode" 
                               class="discount-input" 
                               placeholder="Masukkan kode diskon"
                               autocomplete="off">
                        <button onclick="applyDiscount()" class="btn-apply">
                            <i class="fas fa-check-circle"></i> Apply
                        </button>
                    </div>
                    
                    <div id="appliedDiscountInfo" style="display: {{ $discountAmount > 0 ? 'block' : 'none' }};" class="mt-3">
                        <div class="alert alert-success alert-custom d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-gift"></i>
                                <strong id="discountName">{{ $discount['name'] ?? '' }} ({{ $discount['code'] ?? '' }})</strong>
                                <br>
                                <small id="discountAmountText">Potongan: Rp {{ number_format($discountAmount, 0, ',', '.') }}</small>
                            </div>
                            <button onclick="removeDiscount()" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted">Promo aktif:</small>
                        <div id="activeDiscountsList" class="mt-2 d-flex flex-wrap gap-2"></div>
                    </div>
                </div>
                
                <!-- Summary Card -->
                <div class="summary-card">
                    <h5 class="summary-title">Ringkasan Pesanan</h5>
                    
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span id="cartSubtotal" class="fw-semibold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    
                    <div id="discountRow" class="summary-row text-success" style="display: {{ $discountAmount > 0 ? 'flex' : 'none' }};">
                        <span><i class="fas fa-tag"></i> Diskon</span>
                        <span id="discountValue">- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                    </div>
                    
                    
                    <!-- Free Shipping Progress -->
                    @php
                        $subtotal = $total;
                        $isFreeShipping = $subtotal >= 100000;
                        $remainingForFreeShip = max(0, 100000 - $subtotal);
                        $progressPercent = $subtotal > 0 ? min(($subtotal / 100000) * 100, 100) : 0;
                    @endphp
                    
                    @if(!$isFreeShipping && $subtotal > 0)
                    <div class="alert alert-warning alert-custom py-2 mb-2">
                        <i class="fas fa-info-circle"></i> 
                        Belanja minimal <strong>Rp {{ number_format($remainingForFreeShip, 0, ',', '.') }}</strong> lagi untuk <strong>GRATIS ONGKIR</strong>
                        <div class="progress-shipping mt-2">
                            <div class="progress-shipping-bar" style="width: {{ $progressPercent }}%"></div>
                        </div>
                    </div>
                    @elseif($isFreeShipping)
                    <div class="alert alert-success alert-custom py-2 mb-2">
                        <i class="fas fa-truck"></i> 
                        <strong>🎉 Selamat! Anda mendapatkan GRATIS ONGKIR!</strong>
                    </div>
                    @endif
                    
                    <div class="summary-total">
                        <div class="summary-row fw-bold fs-5">
                            <span>Total</span>
                            <span id="cartTotal" style="color: #FF6B35;">
                                Rp {{ number_format(($total - $discountAmount) + (($total - $discountAmount) * 0), 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    
                    @auth
                        <a href="{{ route('checkout') }}" class="btn-checkout-modern mt-3">
                            <i class="fas fa-credit-card"></i> Proceed to Checkout
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-checkout-modern mt-3">
                            <i class="fas fa-sign-in-alt"></i> Login to Checkout
                        </a>
                        <div class="text-center mt-2">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> Silakan login terlebih dahulu untuk checkout
                            </small>
                        </div>
                    @endauth
                    
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="fas fa-lock"></i> Secure payment guaranteed
                        </small>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="empty-cart">
            <div class="empty-cart-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h4>Your cart is empty</h4>
            <p class="text-muted">Looks like you haven't added any items yet</p>
            <a href="{{ route('shop.products') }}" class="btn-checkout-modern" style="width: auto; padding: 12px 30px;">
                <i class="fas fa-coffee"></i> Start Shopping
            </a>
        </div>
        @endif
    </div>
</div>

<script>
// Apply diskon
function applyDiscount() {
    const code = document.getElementById('discountCode').value.trim();
    if (!code) {
        showToast('warning', 'Masukkan kode diskon terlebih dahulu');
        return;
    }
    
    fetch('{{ route("discount.apply") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ discount_code: code })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            updateCartTotal();
            showAppliedDiscount(data.discount);
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Terjadi kesalahan, silakan coba lagi');
    });
}

// Hapus diskon
function removeDiscount() {
    fetch('{{ route("discount.remove") }}', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            updateCartTotal();
            document.getElementById('appliedDiscountInfo').style.display = 'none';
            document.getElementById('discountCode').value = '';
        }
    });
}

// Tampilkan diskon yang sudah diapply
function showAppliedDiscount(discount) {
    document.getElementById('discountName').innerHTML = `<i class="fas fa-tag"></i> ${discount.name} (${discount.code})`;
    document.getElementById('discountAmountText').innerHTML = `Potongan: ${discount.amount_formatted}`;
    document.getElementById('appliedDiscountInfo').style.display = 'block';
}

// Update total di cart
function updateCartTotal() {
    fetch('{{ route("cart.get-with-discount") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('cartSubtotal').innerHTML = data.subtotal_formatted;
            if (data.discount_amount > 0) {
                document.getElementById('discountRow').style.display = 'flex';
                document.getElementById('discountValue').innerHTML = `- ${data.discount_amount_formatted}`;
            } else {
                document.getElementById('discountRow').style.display = 'none';
            }
            document.getElementById('cartTotal').innerHTML = data.total_formatted;
        });
}

// Load daftar diskon aktif
function loadActiveDiscounts() {
    fetch('{{ route("discounts.active") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.discounts.length > 0) {
                let html = '';
                data.discounts.forEach(discount => {
                    let discountText = discount.type === 'percentage' ? discount.value + '%' : 'Rp ' + discount.value.toLocaleString('id-ID');
                    html += `
                        <div class="discount-badge" onclick="document.getElementById('discountCode').value='${discount.code}'; applyDiscount();">
                            <i class="fas fa-ticket-alt"></i> ${discount.code}
                            <small>${discountText}</small>
                        </div>
                    `;
                });
                document.getElementById('activeDiscountsList').innerHTML = html;
            } else {
                document.getElementById('activeDiscountsList').innerHTML = '<small class="text-muted">Belum ada promo aktif</small>';
            }
        });
}

// Toast notification
function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    const bgColor = type === 'success' ? '#28a745' : (type === 'error' ? '#dc3545' : '#ffc107');
    toast.innerHTML = `
        <div class="toast show" role="alert">
            <div class="toast-header" style="background: ${bgColor}; color: white;">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
                <strong class="me-auto">${type === 'success' ? 'Berhasil!' : (type === 'error' ? 'Gagal!' : 'Peringatan')}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Load saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    loadActiveDiscounts();
    
    @if(session()->has('discount'))
        showAppliedDiscount({
            name: '{{ session("discount.name") }}',
            code: '{{ session("discount.code") }}',
            amount_formatted: 'Rp {{ number_format(session("discount.amount"), 0, ",", ".") }}'
        });
    @endif
});
</script>

<style>
.toast-notification {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    animation: slideInRight 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
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

.toast-notification .toast-header {
    padding: 12px 15px;
}

.toast-notification .toast-body {
    padding: 12px 15px;
}
</style>
@endsection