@extends('layouts.app')

@section('content')
<style>
    .free-shipping-hero {
        background: linear-gradient(135deg, #1a1a2e 0%, #2C1810 100%);
        padding: 80px 0;
        text-align: center;
        color: white;
    }
    
    .info-card {
        background: white;
        border-radius: 24px;
        padding: 30px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        margin-bottom: 30px;
    }
</style>

<div class="free-shipping-hero">
    <div class="container">
        <h1><i class="fas fa-truck"></i> Gratis Ongkir</h1>
        <p>Nikmati pengiriman gratis ke seluruh Indonesia</p>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="info-card">
                <h3 class="text-center mb-4">🚚 Syarat & Ketentuan Gratis Ongkir</h3>
                
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                    <div>
                        <strong>Minimal Belanja Rp 100.000</strong>
                        <p class="text-muted mb-0">Pembelian minimal Rp 100.000 untuk mendapatkan gratis ongkir</p>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                    <div>
                        <strong>Berlaku untuk Semua Produk</strong>
                        <p class="text-muted mb-0">Semua produk Kopi Ancol termasuk dalam promo ini</p>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                    <div>
                        <strong>Otomatis Terpotong</strong>
                        <p class="text-muted mb-0">Diskon ongkir akan otomatis terpotong saat checkout</p>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                    <div>
                        <strong>Kode Promo: FREESHIP</strong>
                        <p class="text-muted mb-0">Masukkan kode FREESHIP saat checkout</p>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <a href="{{ route('shop.products') }}" class="btn btn-primary-custom">
                        <i class="fas fa-shopping-cart"></i> Belanja Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-primary-custom {
        background: linear-gradient(135deg, #C49A6C, #A67C52);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 30px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-primary-custom:hover {
        background: linear-gradient(135deg, #A67C52, #8B5E3C);
        color: white;
    }
</style>
@endsection