@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-orange: #FF6B35;
        --primary-orange-dark: #e55a2b;
        --primary-brown: #2C1810;
        --secondary-brown: #5C3D2E;
        --light-bg: #FDF9F6;
        --card-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        --card-shadow-hover: 0 15px 50px rgba(0, 0, 0, 0.12);
        --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Layout & Container */
    .checkout-header {
        margin-bottom: 32px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0e6e0;
        position: relative;
    }
    
    .checkout-header h2 {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary-brown) 0%, var(--primary-orange) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 8px;
    }
    
    .checkout-header p {
        color: #7f8c8d;
        font-size: 0.95rem;
    }
    
    .checkout-header::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-orange) 0%, #FF8C42 100%);
        border-radius: 3px;
    }

    /* Form & Card Styles */
    .checkout-form, .cart-summary {
        background: white;
        border-radius: 24px;
        padding: 28px;
        box-shadow: var(--card-shadow);
        transition: var(--transition-smooth);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .checkout-form:hover, .cart-summary:hover {
        box-shadow: var(--card-shadow-hover);
    }
    
    .cart-summary {
        position: sticky;
        top: 20px;
    }
    
    .section-title {
        font-weight: 700;
        margin-bottom: 24px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f0e6e0;
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--primary-brown);
    }
    
    .section-title i {
        color: var(--primary-orange);
        font-size: 1.25rem;
    }

    /* Form Inputs */
    .form-label {
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--primary-brown);
        font-size: 0.9rem;
    }
    
    .form-control, .form-select {
        border: 1.5px solid #e8e0d8;
        border-radius: 12px;
        padding: 10px 14px;
        transition: var(--transition-smooth);
        font-size: 0.95rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
        outline: none;
    }
    
    textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }
    
    .location-select {
        margin-bottom: 12px;
    }

    /* Payment Method Cards */
    .payment-method-card {
        transition: var(--transition-smooth);
        cursor: pointer;
        border-radius: 16px;
        border: 2px solid #e8e0d8 !important;
        background: white;
        margin-bottom: 12px;
    }
    
    .payment-method-card:hover {
        border-color: var(--primary-orange) !important;
        background: linear-gradient(135deg, #FFF8F0 0%, #FFF5EE 100%);
        transform: translateX(4px);
    }
    
    .payment-method-card.selected {
        border-color: var(--primary-orange) !important;
        background: linear-gradient(135deg, #FFF8F0 0%, #FFF5EE 100%);
        border-width: 2px !important;
        box-shadow: 0 4px 12px rgba(255, 107, 53, 0.15);
    }
    
    .payment-method-card .form-check-input:checked {
        background-color: var(--primary-orange);
        border-color: var(--primary-orange);
    }
    
    .payment-method-card .form-check-label strong {
        color: var(--primary-brown);
    }

    /* Buttons */
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-orange) 0%, #FF8C42 100%);
        border: none;
        padding: 12px 24px;
        border-radius: 40px;
        font-weight: 600;
        color: white;
        transition: var(--transition-smooth);
        font-size: 1rem;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 107, 53, 0.35);
        color: white;
    }
    
    .btn-primary-custom:active {
        transform: translateY(0);
    }
    
    .btn-primary-custom:disabled {
        opacity: 0.6;
        transform: none;
    }
    
    .btn-back-custom {
        background: #6c757d;
        border: none;
        padding: 12px 24px;
        border-radius: 40px;
        font-weight: 600;
        color: white;
        transition: var(--transition-smooth);
    }
    
    .btn-back-custom:hover {
        background: #5a6268;
        transform: translateY(-2px);
        color: white;
    }
    
    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 25px;
    }
    
    .action-buttons .btn {
        flex: 1;
        font-size: 0.95rem;
    }

    /* Shipping Section */
    .shipping-section {
        margin-top: 20px;
        padding: 20px;
        background: linear-gradient(135deg, #F8F9FA 0%, #FDF9F6 100%);
        border-radius: 16px;
        border: 1px solid #e8e0d8;
    }
    
    .shipping-title {
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--primary-brown);
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .shipping-title i {
        color: var(--primary-orange);
    }
    
    .shipping-option {
        border: 2px solid #e8e0d8;
        border-radius: 14px;
        padding: 14px 16px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: var(--transition-smooth);
        background: white;
    }
    
    .shipping-option:hover {
        border-color: var(--primary-orange);
        background: #FFF8F0;
        transform: translateX(4px);
    }
    
    .shipping-option.selected {
        border-color: var(--primary-orange);
        background: linear-gradient(135deg, #FFF8F0 0%, #FFF5EE 100%);
        box-shadow: 0 2px 8px rgba(255, 107, 53, 0.1);
    }
    
    .free-shipping-badge {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 5px 14px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }

    /* Alert Styles */
    .alert-custom {
        border-radius: 14px;
        border: none;
        padding: 14px 16px;
    }
    
    .alert-success-custom {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border-left: 4px solid #28a745;
    }
    
    .alert-warning-custom {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        color: #856404;
        border-left: 4px solid #ffc107;
    }
    
    .alert-info-custom {
        background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
        color: #0c5460;
        border-left: 4px solid #17a2b8;
    }

    /* Address Preview */
    .address-preview {
        background: linear-gradient(135deg, #e8f4f8 0%, #d4eaf0 100%);
        border-radius: 12px;
        padding: 12px 15px;
        margin-top: 10px;
        border-left: 4px solid var(--primary-orange);
        font-size: 0.85rem;
    }
    
    .address-preview i {
        color: var(--primary-orange);
        margin-right: 8px;
    }

    /* Virtual Account & E-Wallet Cards */
    .virtual-account-card, .ewallet-card {
        background: white;
        border-radius: 14px;
        padding: 16px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: var(--transition-smooth);
        border: 2px solid #e8e0d8;
    }
    
    .virtual-account-card:hover, .ewallet-card:hover {
        background: #FFF8F0;
        transform: translateX(6px);
        border-color: var(--primary-orange);
    }
    
    .bank-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--primary-brown) 0%, var(--secondary-brown) 100%);
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 1rem;
    }

    /* Cart Summary */
    .cart-items {
        max-height: 300px;
        overflow-y: auto;
        margin-bottom: 16px;
        padding-right: 8px;
    }
    
    .cart-items::-webkit-scrollbar {
        width: 5px;
    }
    
    .cart-items::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .cart-items::-webkit-scrollbar-thumb {
        background: var(--primary-orange);
        border-radius: 10px;
    }
    
    .summary-card {
        background: linear-gradient(135deg, #FDF9F6 0%, #FFF8F0 100%);
        border-radius: 20px;
        padding: 20px;
        margin-top: 16px;
    }
    
    .summary-divider {
        border-top: 2px dashed #e0d6ce;
        margin: 16px 0;
    }
    
    .total-amount {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--primary-orange);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 40px;
        background: linear-gradient(135deg, #FDF9F6 0%, #FFF8F0 100%);
        border-radius: 24px;
    }
    
    .empty-state i {
        font-size: 64px;
        color: #C49A6C;
        opacity: 0.5;
        margin-bottom: 20px;
    }
    
    .empty-state h4 {
        color: var(--primary-brown);
        margin-bottom: 16px;
    }

    /* Loading Overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(44, 24, 16, 0.9);
        z-index: 9999;
        display: none;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        backdrop-filter: blur(5px);
    }
    
    .loading-spinner {
        width: 60px;
        height: 60px;
        border: 4px solid rgba(255, 255, 255, 0.2);
        border-top: 4px solid var(--primary-orange);
        border-right: 4px solid #FF8C42;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .loading-text {
        color: white;
        margin-top: 20px;
        font-weight: 500;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 24px;
        border: none;
        overflow: hidden;
    }
    
    .modal-header-custom {
        background: linear-gradient(135deg, var(--primary-brown) 0%, var(--secondary-brown) 100%);
        color: white;
        padding: 18px 24px;
        border: none;
    }
    
    .modal-header-custom .modal-title {
        font-weight: 700;
    }
    
    .modal-body-custom {
        padding: 24px;
    }
    
    .qris-box {
        background: white;
        padding: 20px;
        border-radius: 20px;
        border: 2px solid var(--primary-orange);
        display: inline-block;
        margin: 10px auto;
    }
    
    /* Progress Bar */
    .progress-custom {
        height: 8px;
        border-radius: 10px;
        background-color: #e8e0d8;
        overflow: hidden;
        margin-top: 8px;
    }
    
    .progress-bar-custom {
        background: linear-gradient(90deg, var(--primary-orange) 0%, #FF8C42 100%);
        border-radius: 10px;
        transition: width 0.4s ease;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .checkout-form, .cart-summary {
            padding: 20px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .section-title {
            font-size: 1.2rem;
        }
    }
    
    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fadeInUp 0.5s ease-out;
    }
</style>

<div class="animate-fade-in">
    <div class="checkout-header">
        <h2>
            <i class="fas fa-clipboard-list" style="background: linear-gradient(135deg, var(--primary-brown) 0%, var(--primary-orange) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i> 
            Checkout
        </h2>
        <p class="text-muted">Lengkapi informasi pemesanan Anda untuk proses yang lebih cepat</p>
    </div>

    @php
        $cart = session()->get('cart', []);
        $total = 0;
        foreach($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }
        $grandTotal = $total + ($total * 0.1);
        $discount = session()->get('discount');
        $discountAmount = $discount['amount'] ?? 0;
        $finalTotal = $grandTotal - $discountAmount;
        
        $isFreeShippingEligible = ($total - $discountAmount) >= 100000;
    @endphp

    @if(count($cart) > 0)
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="checkout-form">
                <div class="section-title">
                    <i class="fas fa-user-circle fa-lg"></i>
                    <span>Informasi Customer</span>
                </div>
                <form id="checkoutForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="customer_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div class="col-md-6">
                            <label for="customer_email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="customer_email" name="customer_email" placeholder="contoh@domain.com" required>
                        </div>
                        <div class="col-md-6">
                            <label for="customer_phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="customer_phone" name="customer_phone" placeholder="08xxxxxxxxxx" required>
                        </div>
                    </div>

                    <!-- ALAMAT PENGIRIMAN LENGKAP -->
                    <div class="mt-4">
                        <label class="form-label">Alamat Pengiriman <span class="text-danger">*</span></label>
                        
                        <div class="location-select">
                            <select id="province_id" class="form-select" required>
                                <option value="">Pilih Provinsi</option>
                            </select>
                        </div>
                        
                        <div class="location-select">
                            <select id="regency_id" class="form-select" disabled required>
                                <option value="">Pilih Kabupaten/Kota</option>
                            </select>
                        </div>
                        
                        <div class="location-select">
                            <select id="district_id" class="form-select" disabled required>
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>
                        
                        <div class="location-select">
                            <select id="village_id" class="form-select" disabled required>
                                <option value="">Pilih Desa/Kelurahan</option>
                            </select>
                        </div>
                        
                        <textarea class="form-control mt-2" id="customer_address_detail" rows="2" placeholder="Detail alamat (Jalan, No. Rumah, RT/RW, Kode Pos)"></textarea>
                        
                        <!-- Full Address Preview -->
                        <div class="address-preview" id="addressPreview" style="display: none;">
                            <i class="fas fa-map-marker-alt"></i>
                            <strong>Alamat Lengkap:</strong>
                            <span id="addressPreviewText"></span>
                        </div>
                    </div>
                    
                    <!-- EKSPEDISI DAN ONGKIR -->
                    <div class="shipping-section">
                        <div class="shipping-title">
                            <i class="fas fa-truck fa-lg"></i>
                            <span>Ekspedisi & Ongkos Kirim <span class="text-danger">*</span></span>
                        </div>
                        
                        @if($isFreeShippingEligible)
                        <div class="alert alert-success-custom alert-custom mb-3">
                            <i class="fas fa-trophy"></i> 
                            <strong>🎉 Selamat! Anda mendapatkan GRATIS ONGKIR!</strong><br>
                            <small>Karena total belanja Anda di atas Rp 100.000</small>
                        </div>
                        @else
                        <div class="alert alert-warning-custom alert-custom mb-3">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Belanja minimal Rp 100.000 untuk GRATIS ONGKIR!</strong><br>
                            <small>Kekurangan: Rp {{ number_format(100000 - ($total - $discountAmount), 0, ',', '.') }}</small>
                            <div class="progress-custom mt-2">
                                <div class="progress-bar-custom" style="width: {{ min((($total - $discountAmount) / 100000) * 100, 100) }}%"></div>
                            </div>
                        </div>
                        @endif
                        
                        <div id="shippingOptionsContainer">
                            @if($isFreeShippingEligible)
                                <div class="alert alert-success-custom alert-custom text-center mb-0">
                                    <i class="fas fa-check-circle fa-lg"></i>
                                    <div class="mt-1">Ongkos Kirim GRATIS!</div>
                                    <input type="hidden" id="selected_shipping" name="shipping_cost" value="0">
                                    <input type="hidden" id="selected_courier" name="courier" value="free">
                                    <input type="hidden" id="selected_service" name="shipping_service" value="Gratis Ongkir">
                                </div>
                            @else
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-map-marker-alt fa-2x mb-2 opacity-50"></i>
                                    <p class="mb-0">Silakan pilih desa/kelurahan terlebih dahulu<br>untuk melihat ongkos kirim</p>
                                </div>
                            @endif
                        </div>
                        <input type="hidden" id="selected_shipping" name="shipping_cost" value="0">
                        <input type="hidden" id="selected_courier" name="courier" value="">
                        <input type="hidden" id="selected_service" name="shipping_service" value="">
                        <input type="hidden" id="destination_id" name="destination_id" value="">
                    </div>
                    
                    <div class="mt-4">
                        <label for="notes" class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Contoh: Tolong dibungkus rapi, Pisahkan pesanan, dll"></textarea>
                    </div>

                    <!-- Payment Methods -->
                    <div class="mt-4">
                        <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                        
                        <div class="payment-method-card p-3 border rounded" onclick="selectPayment('qris', this)">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_qris" value="qris">
                                <label class="form-check-label" for="payment_qris">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="fas fa-qrcode fa-2x" style="color: #FF6B35;"></i>
                                        <div>
                                            <strong>QRIS</strong>
                                            <br><small class="text-muted">Scan GoPay, OVO, DANA, LinkAja</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <div class="payment-method-card p-3 border rounded" onclick="selectPayment('virtual_account', this)">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_va" value="virtual_account">
                                <label class="form-check-label" for="payment_va">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="fas fa-university fa-2x" style="color: #6f42c1;"></i>
                                        <div>
                                            <strong>Virtual Account</strong>
                                            <br><small class="text-muted">BCA, Mandiri, BRI, BNI</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <div class="payment-method-card p-3 border rounded" onclick="selectPayment('ewallet', this)">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_ewallet" value="ewallet">
                                <label class="form-check-label" for="payment_ewallet">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="fas fa-wallet fa-2x" style="color: #fd7e14;"></i>
                                        <div>
                                            <strong>E-Wallet</strong>
                                            <br><small class="text-muted">GoPay, OVO, DANA, ShopeePay</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <div class="payment-method-card p-3 border rounded" onclick="selectPayment('cod', this)">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="cod">
                                <label class="form-check-label" for="payment_cod">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="fas fa-money-bill-wave fa-2x" style="color: #20c997;"></i>
                                        <div>
                                            <strong>Cash on Delivery (COD)</strong>
                                            <br><small class="text-muted">Bayar saat barang diterima</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" id="selected_payment" name="selected_payment" value="">
                    <input type="hidden" id="selected_bank" name="selected_bank" value="">
                    <input type="hidden" id="selected_ewallet" name="selected_ewallet" value="">
                    
                    <div class="action-buttons">
                        <a href="{{ route('cart.index') }}" class="btn btn-back-custom">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Keranjang
                        </a>
                        <button type="button" class="btn btn-primary-custom" id="payNowBtn" onclick="processOrder()">
                            <i class="fas fa-credit-card me-2"></i>Proses Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-lg-5">
            <div class="cart-summary">
                <div class="section-title">
                    <i class="fas fa-receipt"></i>
                    <span>Ringkasan Pesanan</span>
                </div>
                
                <div class="cart-items">
                    @foreach($cart as $details)
                    <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                        <div>
                            <span class="fw-semibold">{{ $details['name'] }}</span>
                            <br>
                            <small class="text-muted">x{{ $details['quantity'] }}</small>
                        </div>
                        <span class="fw-semibold">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                
                <div class="summary-card">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span id="cartSubtotal">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Pajak (5%)</span>
                        <span id="taxAmount">Rp {{ number_format($total * 0.1, 0, ',', '.') }}</span>
                    </div>
                    
                    <div id="discountRow" class="d-flex justify-content-between mb-2 text-success" style="display: {{ $discountAmount > 0 ? 'flex' : 'none' }};">
                        <span><i class="fas fa-tag me-1"></i> Diskon</span>
                        <span id="discountValue">- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                    </div>
                    
                    <div id="shippingRow" class="d-flex justify-content-between mb-2" style="display: {{ $isFreeShippingEligible ? 'flex' : 'none' }};">
                        <span><i class="fas fa-truck me-1"></i> Ongkos Kirim</span>
                        <span id="shippingAmount" class="text-success">
                            @if($isFreeShippingEligible)
                                <i class="fas fa-check-circle"></i> FREE
                            @else
                                Rp 0
                            @endif
                        </span>
                    </div>
                    
                    <div class="summary-divider"></div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <strong class="fs-5">Total</strong>
                        <strong class="total-amount" id="cartTotal">
                            Rp {{ number_format($finalTotal, 0, ',', '.') }}
                        </strong>
                    </div>
                </div>
                
                <div class="alert alert-info-custom alert-custom mt-3 small">
                    <i class="fas fa-check-circle me-1"></i> 
                    Setelah pesanan diproses, order akan langsung masuk ke sistem kami.
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-shopping-cart"></i>
        <h4>Keranjang Belanja Kosong</h4>
        <p class="text-muted mb-4">Anda belum menambahkan produk apapun ke keranjang</p>
        <a href="{{ route('shop.index') }}" class="btn btn-primary-custom">
            <i class="fas fa-coffee me-2"></i>Mulai Belanja
        </a>
    </div>
    @endif
</div>

<!-- QRIS Modal -->
<div class="modal fade" id="qrisModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header-custom">
                <h5 class="modal-title"><i class="fas fa-qrcode me-2"></i> Scan QRIS</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body-custom text-center">
                <div class="d-flex justify-content-center gap-3 mb-3">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/GoPay_logo.svg/60px-GoPay_logo.svg.png" height="35" alt="GoPay">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/OVO_logo.svg/60px-OVO_logo.svg.png" height="35" alt="OVO">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/97/DANA_logo.svg/60px-DANA_logo.svg.png" height="35" alt="DANA">
                </div>
                <div class="qris-box">
                    <img id="qrisImage" src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=QRIS-KOPI-ANCOL-{{ time() }}" alt="QRIS" style="width: 180px; height: 180px;">
                </div>
                <div class="alert alert-info mt-3 mb-0">
                    <strong>Total Pembayaran</strong>
                    <h4 class="text-success mt-1 mb-0" id="qrisTotal">Rp {{ number_format($finalTotal, 0, ',', '.') }}</h4>
                </div>
                <div class="alert alert-warning small mt-3 mb-0">
                    <i class="fas fa-clock me-1"></i> Batas waktu pembayaran: 60 menit
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" onclick="confirmQRIS()">
                    <i class="fas fa-check-circle me-1"></i>Saya Sudah Bayar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Virtual Account Modal -->
<div class="modal fade" id="vaModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header-custom">
                <h5 class="modal-title"><i class="fas fa-university me-2"></i> Pilih Bank</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body-custom">
                <div class="virtual-account-card" onclick="selectBank('bca')">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bank-icon">BCA</div>
                        <div>
                            <strong>Bank BCA</strong>
                            <br><small class="text-muted">Virtual Account BCA</small>
                        </div>
                    </div>
                </div>
                <div class="virtual-account-card" onclick="selectBank('mandiri')">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bank-icon">MDR</div>
                        <div>
                            <strong>Bank Mandiri</strong>
                            <br><small class="text-muted">Virtual Account Mandiri</small>
                        </div>
                    </div>
                </div>
                <div class="virtual-account-card" onclick="selectBank('bri')">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bank-icon">BRI</div>
                        <div>
                            <strong>Bank BRI</strong>
                            <br><small class="text-muted">Virtual Account BRI</small>
                        </div>
                    </div>
                </div>
                <div class="virtual-account-card" onclick="selectBank('bni')">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bank-icon">BNI</div>
                        <div>
                            <strong>Bank BNI</strong>
                            <br><small class="text-muted">Virtual Account BNI</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- E-Wallet Modal -->
<div class="modal fade" id="ewalletModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header-custom">
                <h5 class="modal-title"><i class="fas fa-wallet me-2"></i> Pilih E-Wallet</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body-custom">
                <div class="virtual-account-card" onclick="selectEWallet('gopay')">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fab fa-google-pay fa-2x" style="color: #00AA6C;"></i>
                        <div>
                            <strong>GoPay</strong>
                            <br><small class="text-muted">Bayar dengan GoPay</small>
                        </div>
                    </div>
                </div>
                <div class="virtual-account-card" onclick="selectEWallet('ovo')">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-wallet fa-2x" style="color: #5C2D91;"></i>
                        <div>
                            <strong>OVO</strong>
                            <br><small class="text-muted">Bayar dengan OVO</small>
                        </div>
                    </div>
                </div>
                <div class="virtual-account-card" onclick="selectEWallet('dana')">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-mobile-alt fa-2x" style="color: #007AFF;"></i>
                        <div>
                            <strong>DANA</strong>
                            <br><small class="text-muted">Bayar dengan DANA</small>
                        </div>
                    </div>
                </div>
                <div class="virtual-account-card" onclick="selectEWallet('shopeepay')">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-store fa-2x" style="color: #EE4D2D;"></i>
                        <div>
                            <strong>ShopeePay</strong>
                            <br><small class="text-muted">Bayar dengan ShopeePay</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
    <div class="loading-text">Memproses pesanan...</div>
</div>

<script>
    // ========== VARIABLES ==========
    let selectedPayment = '';
    let selectedBank = '';
    let selectedEWallet = '';
    let totalAmount = {{ $finalTotal }};
    let subtotal = {{ $total }};
    let discountAmount = {{ $discountAmount }};
    let tax = subtotal * 0.1;
    let shippingCost = 0;
    let isFreeShipping = {{ $isFreeShippingEligible ? 'true' : 'false' }};
    
    // ========== PAYMENT FUNCTIONS ==========
    function selectPayment(method, element) {
        selectedPayment = method;
        document.getElementById('selected_payment').value = method;
        
        document.querySelectorAll('.payment-method-card').forEach(card => {
            card.classList.remove('selected');
        });
        if (element) {
            element.classList.add('selected');
        }
        
        if (method === 'qris') {
            document.getElementById('qrisTotal').innerHTML = 'Rp ' + totalAmount.toLocaleString('id-ID');
            new bootstrap.Modal(document.getElementById('qrisModal')).show();
        } else if (method === 'virtual_account') {
            new bootstrap.Modal(document.getElementById('vaModal')).show();
        } else if (method === 'ewallet') {
            new bootstrap.Modal(document.getElementById('ewalletModal')).show();
        } else if (method === 'cod') {
            processOrder();
        }
    }
    
    function selectBank(bank) {
        selectedBank = bank;
        document.getElementById('selected_bank').value = bank;
        var vaModal = bootstrap.Modal.getInstance(document.getElementById('vaModal'));
        if (vaModal) vaModal.hide();
        processOrder();
    }
    
    function selectEWallet(ewallet) {
        selectedEWallet = ewallet;
        document.getElementById('selected_ewallet').value = ewallet;
        var ewalletModal = bootstrap.Modal.getInstance(document.getElementById('ewalletModal'));
        if (ewalletModal) ewalletModal.hide();
        processOrder();
    }
    
    function confirmQRIS() {
        var qrisModal = bootstrap.Modal.getInstance(document.getElementById('qrisModal'));
        if (qrisModal) qrisModal.hide();
        processOrder();
    }
    
    // ========== LOCATION FUNCTIONS ==========
    function loadProvinces() {
        fetch('/locations/provinces')
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById('province_id');
                select.innerHTML = '<option value="">Pilih Provinsi</option>';
                data.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.id;
                    option.textContent = province.name;
                    select.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading provinces:', error));
    }
    
    document.getElementById('province_id').addEventListener('change', function() {
        const provinceId = this.value;
        const regencySelect = document.getElementById('regency_id');
        const districtSelect = document.getElementById('district_id');
        const villageSelect = document.getElementById('village_id');
        
        if (!provinceId) {
            regencySelect.disabled = true;
            regencySelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
            districtSelect.disabled = true;
            villageSelect.disabled = true;
            return;
        }
        
        regencySelect.disabled = false;
        regencySelect.innerHTML = '<option value="">Loading...</option>';
        districtSelect.disabled = true;
        villageSelect.disabled = true;
        
        fetch(`/locations/regencies/${provinceId}`)
            .then(response => response.json())
            .then(data => {
                regencySelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                data.forEach(regency => {
                    const option = document.createElement('option');
                    option.value = regency.id;
                    option.textContent = regency.name;
                    regencySelect.appendChild(option);
                });
            });
    });
    
    document.getElementById('regency_id').addEventListener('change', function() {
        const regencyId = this.value;
        const districtSelect = document.getElementById('district_id');
        const villageSelect = document.getElementById('village_id');
        
        if (!regencyId) {
            districtSelect.disabled = true;
            districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            villageSelect.disabled = true;
            return;
        }
        
        districtSelect.disabled = false;
        districtSelect.innerHTML = '<option value="">Loading...</option>';
        villageSelect.disabled = true;
        
        fetch(`/locations/districts/${regencyId}`)
            .then(response => response.json())
            .then(data => {
                districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                data.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.id;
                    option.textContent = district.name;
                    districtSelect.appendChild(option);
                });
            });
    });
    
    document.getElementById('district_id').addEventListener('change', function() {
        const districtId = this.value;
        const villageSelect = document.getElementById('village_id');
        const shippingContainer = document.getElementById('shippingOptionsContainer');
        
        if (!districtId) {
            villageSelect.disabled = true;
            villageSelect.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
            if (!isFreeShipping) {
                shippingContainer.innerHTML = '<div class="text-center py-4 text-muted"><i class="fas fa-map-marker-alt fa-2x mb-2 opacity-50"></i><p class="mb-0">Silakan pilih kecamatan terlebih dahulu</p></div>';
            }
            return;
        }
        
        villageSelect.disabled = false;
        villageSelect.innerHTML = '<option value="">Loading...</option>';
        
        fetch(`/locations/villages/${districtId}`)
            .then(response => response.json())
            .then(data => {
                villageSelect.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
                data.forEach(village => {
                    const option = document.createElement('option');
                    option.value = village.id;
                    option.textContent = village.name;
                    villageSelect.appendChild(option);
                });
            });
    });
    
    document.getElementById('village_id').addEventListener('change', function() {
        if (isFreeShipping) return;
        
        const villageId = this.value;
        const shippingContainer = document.getElementById('shippingOptionsContainer');
        document.getElementById('destination_id').value = villageId;
        
        if (!villageId) {
            shippingContainer.innerHTML = '<div class="text-center py-4 text-muted"><i class="fas fa-map-marker-alt fa-2x mb-2 opacity-50"></i><p class="mb-0">Silakan pilih desa/kelurahan</p></div>';
            return;
        }
        
        shippingContainer.innerHTML = '<div class="text-center py-4"><div class="loading-spinner" style="width: 30px; height: 30px;"></div><p class="mt-2 text-muted">Memuat ongkos kirim...</p></div>';
        
        setTimeout(() => {
            const shippingData = {
                success: true,
                shipping_costs: [
                    { courier: 'jne', courier_name: 'JNE', service: 'REG', cost: 15000, estimated_days: 2 },
                    { courier: 'jne', courier_name: 'JNE', service: 'YES', cost: 25000, estimated_days: 1 },
                    { courier: 'tiki', courier_name: 'TIKI', service: 'REG', cost: 14000, estimated_days: 3 },
                    { courier: 'sicepat', courier_name: 'SiCepat', service: 'REG', cost: 12000, estimated_days: 2 }
                ]
            };
            
            if (shippingData.success && shippingData.shipping_costs.length > 0) {
                let html = '';
                shippingData.shipping_costs.forEach(cost => {
                    html += `
                        <div class="shipping-option" onclick="selectShipping(${cost.cost}, '${cost.courier}', '${cost.service}', ${cost.estimated_days || 0}, this)">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${cost.courier_name}</strong>
                                    <br>
                                    <small class="text-muted">${cost.service || 'Reguler'}</small>
                                    ${cost.estimated_days ? `<small class="text-muted"> - Estimasi ${cost.estimated_days} hari</small>` : ''}
                                </div>
                                <div class="text-end">
                                    <strong class="text-primary">Rp ${cost.cost.toLocaleString('id-ID')}</strong>
                                </div>
                            </div>
                        </div>
                    `;
                });
                shippingContainer.innerHTML = html;
            } else {
                shippingContainer.innerHTML = '<div class="alert alert-warning alert-custom mb-0">Belum ada ongkos kirim untuk lokasi ini</div>';
            }
        }, 500);
    });
    
    function selectShipping(cost, courier, service, estimatedDays, element) {
        const selectedOption = element;
        document.querySelectorAll('.shipping-option').forEach(opt => {
            opt.classList.remove('selected');
        });
        selectedOption.classList.add('selected');
        
        shippingCost = cost;
        document.getElementById('selected_shipping').value = cost;
        document.getElementById('selected_courier').value = courier;
        document.getElementById('selected_service').value = service;
        
        document.getElementById('shippingRow').style.display = 'flex';
        document.getElementById('shippingAmount').innerHTML = 'Rp ' + cost.toLocaleString('id-ID');
        if (!isFreeShipping) {
            document.getElementById('shippingAmount').classList.remove('text-success');
        }
        
        updateTotal();
    }
    
    function updateTotal() {
        const total = subtotal - discountAmount + tax + (isFreeShipping ? 0 : shippingCost);
        document.getElementById('cartTotal').innerHTML = 'Rp ' + total.toLocaleString('id-ID');
        totalAmount = total;
    }
    
    function updateAddressPreview() {
        const province = document.getElementById('province_id').options[document.getElementById('province_id').selectedIndex]?.text || '';
        const regency = document.getElementById('regency_id').options[document.getElementById('regency_id').selectedIndex]?.text || '';
        const district = document.getElementById('district_id').options[document.getElementById('district_id').selectedIndex]?.text || '';
        const village = document.getElementById('village_id').options[document.getElementById('village_id').selectedIndex]?.text || '';
        const detail = document.getElementById('customer_address_detail').value;
        
        if (province && regency && district && village) {
            const fullAddress = `${detail ? detail + ', ' : ''}${village}, ${district}, ${regency}, ${province}`;
            document.getElementById('addressPreviewText').innerText = fullAddress;
            document.getElementById('addressPreview').style.display = 'block';
        } else {
            document.getElementById('addressPreview').style.display = 'none';
        }
    }
    
    document.getElementById('village_id').addEventListener('change', updateAddressPreview);
    document.getElementById('customer_address_detail').addEventListener('input', updateAddressPreview);
    
    // ========== PROCESS ORDER ==========
    function processOrder() {
        const name = document.getElementById('customer_name').value.trim();
        const email = document.getElementById('customer_email').value.trim();
        const phone = document.getElementById('customer_phone').value.trim();
        const province = document.getElementById('province_id').options[document.getElementById('province_id').selectedIndex]?.text || '';
        const regency = document.getElementById('regency_id').options[document.getElementById('regency_id').selectedIndex]?.text || '';
        const district = document.getElementById('district_id').options[document.getElementById('district_id').selectedIndex]?.text || '';
        const village = document.getElementById('village_id').options[document.getElementById('village_id').selectedIndex]?.text || '';
        const addressDetail = document.getElementById('customer_address_detail').value;
        
        const fullAddress = `${addressDetail ? addressDetail + ', ' : ''}${village}, ${district}, ${regency}, ${province}`;
        
        if (!name || !email || !phone || !province || !regency || !district || !village) {
            alert('⚠️ Silakan lengkapi semua data alamat!');
            return;
        }
        
        if (!selectedPayment) {
            alert('⚠️ Pilih metode pembayaran terlebih dahulu!');
            return;
        }
        
        if (!isFreeShipping && shippingCost === 0 && selectedPayment !== 'cod') {
            alert('⚠️ Silakan pilih ekspedisi dan ongkos kirim!');
            return;
        }
        
        document.getElementById('loadingOverlay').style.display = 'flex';
        document.getElementById('payNowBtn').disabled = true;
        
        const cart = @json($cart);
        const total = subtotal - discountAmount + tax + (isFreeShipping ? 0 : shippingCost);
        
        fetch('{{ route("order.process") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                customer_name: name,
                customer_email: email,
                customer_phone: phone,
                customer_address: fullAddress,
                payment_method: selectedPayment,
                payment_detail: selectedBank || selectedEWallet || null,
                notes: document.getElementById('notes').value,
                items: cart,
                total_amount: total,
                discount_code: '{{ $discount["code"] ?? null }}',
                discount_amount: discountAmount,
                shipping_cost: isFreeShipping ? 0 : shippingCost,
                courier: isFreeShipping ? 'free' : document.getElementById('selected_courier').value,
                shipping_service: isFreeShipping ? 'Gratis Ongkir' : document.getElementById('selected_service').value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("order.success") }}?order_id=' + data.order_id;
            } else {
                alert('❌ Gagal: ' + data.message);
                document.getElementById('loadingOverlay').style.display = 'none';
                document.getElementById('payNowBtn').disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan: ' + error.message);
            document.getElementById('loadingOverlay').style.display = 'none';
            document.getElementById('payNowBtn').disabled = false;
        });
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        loadProvinces();
    });
</script>
@endsection