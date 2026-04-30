@extends('layouts.app')

@section('content')
<style>
    .premium-hero {
        background: linear-gradient(135deg, #1a1a2e 0%, #2C1810 100%);
        padding: 80px 0;
        text-align: center;
        color: white;
    }
    
    .premium-hero h1 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 20px;
    }
    
    .quality-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
        text-align: center;
    }
    
    .quality-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 35px rgba(0,0,0,0.15);
    }
    
    .quality-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #C49A6C, #A67C52);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    
    .quality-icon i {
        font-size: 35px;
        color: white;
    }
    
    .quality-card h3 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2C1810;
        margin-bottom: 15px;
    }
    
    .process-timeline {
        position: relative;
        padding: 40px 0;
    }
    
    .timeline-item {
        display: flex;
        margin-bottom: 40px;
        position: relative;
    }
    
    .timeline-number {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #C49A6C, #A67C52);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 1.2rem;
        margin-right: 20px;
        flex-shrink: 0;
    }
    
    .timeline-content {
        flex: 1;
    }
    
    .testimonial-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .testimonial-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #C49A6C;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }
    
    .testimonial-avatar i {
        font-size: 30px;
        color: white;
    }
    
    .rating {
        color: #FFD700;
        margin-top: 10px;
    }
</style>

<!-- Hero Section -->
<div class="premium-hero">
    <div class="container">
        <h1><i class="fas fa-crown"></i> Premium Quality</h1>
        <p>100% biji kopi asli Indonesia pilihan</p>
    </div>
</div>

<div class="container py-5">
    <!-- Quality Standards -->
    <div class="row mb-5">
        <div class="col-12 text-center mb-4">
            <h2 class="fw-bold">Standar Kualitas Premium</h2>
            <p class="text-muted">Kami mempertahankan standar tertinggi di setiap tahapan</p>
        </div>
        <div class="col-md-3 mb-3">
            <div class="quality-card">
                <div class="quality-icon">
                    <i class="fas fa-seedling"></i>
                </div>
                <h3>100% Organik</h3>
                <p class="text-muted">Tanpa pestisida dan bahan kimia berbahaya</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="quality-card">
                <div class="quality-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <h3>Specialty Grade</h3>
                <p class="text-muted">Skor cupping 85+, kualitas ekspor</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="quality-card">
                <div class="quality-icon">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <h3>Fair Trade</h3>
                <p class="text-muted">Mendukung kesejahteraan petani lokal</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="quality-card">
                <div class="quality-icon">
                    <i class="fas fa-fire"></i>
                </div>
                <h3>Fresh Roasted</h3>
                <p class="text-muted">Diroasting setelah order untuk kesegaran maksimal</p>
            </div>
        </div>
    </div>
    
    <!-- Process Timeline -->
    <div class="row mb-5">
        <div class="col-12 text-center mb-4">
            <h2 class="fw-bold">Proses Premium Quality</h2>
            <p class="text-muted">Dari perkebunan hingga ke cangkir Anda</p>
        </div>
        <div class="col-md-12">
            <div class="process-timeline">
                <div class="timeline-item">
                    <div class="timeline-number">1</div>
                    <div class="timeline-content">
                        <h4>Pemilihan Biji Pilihan</h4>
                        <p>Biji kopi dipilih secara manual oleh petani berpengalaman. Hanya biji matang sempurna yang dipilih.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-number">2</div>
                    <div class="timeline-content">
                        <h4>Pemrosesan Basah & Kering</h4>
                        <p>Diolah dengan metode wet hulling khas Sumatera untuk menghasilkan rasa yang khas.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-number">3</div>
                    <div class="timeline-content">
                        <h4>Roasting Sempurna</h4>
                        <p>Diroasting dengan suhu dan waktu yang tepat untuk menghasilkan profil rasa terbaik.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-number">4</div>
                    <div class="timeline-content">
                        <h4>Quality Control</h4>
                        <p>Setiap batch diuji oleh barista profesional untuk memastikan kualitas konsisten.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- CTA -->
    <div class="row">
        <div class="col-12 text-center">
            <div class="bg-light rounded-4 p-5">
                <h3>Rasakan Sendiri Kualitas Premium Kopi Ancol</h3>
                <p class="text-muted mb-4">Dapatkan pengalaman kopi terbaik dengan biji pilihan dari perkebunan terbaik</p>
                <a href="{{ route('shop.products') }}" class="btn-premium">
                    <i class="fas fa-shopping-cart"></i> Beli Sekarang
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-premium {
        display: inline-flex;
        align-items: center;
        background: linear-gradient(135deg, #C49A6C, #A67C52);
        color: white;
        padding: 12px 28px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-premium:hover {
        transform: translateY(-3px);
        color: white;
        background: linear-gradient(135deg, #A67C52, #8B5E3C);
    }
</style>
@endsection