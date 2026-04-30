@extends('layouts.app')

@section('content')
<style>
    /* ==================== HERO SECTION ==================== */
    .hero-aesthetic {
        background: linear-gradient(135deg, #000000 0%, #aa2a35 50%, #4b1a1a 100%);
        border-radius: 40px;
        padding: 80px 60px;
        margin-bottom: 60px;
        position: relative;
        overflow: hidden;
    }
    .hero-aesthetic::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(198,124,78,0.15) 0%, rgba(198,124,78,0) 70%);
        border-radius: 50%;
    }
    .hero-aesthetic::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(198,124,78,0.1) 0%, rgba(198,124,78,0) 70%);
        border-radius: 50%;
    }
    .hero-badge {
        display: inline-block;
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        padding: 8px 20px;
        border-radius: 40px;
        font-size: 0.8rem;
        color: #FFD89B;
        margin-bottom: 20px;
        letter-spacing: 1px;
    }
    .hero-title {
        font-size: 4rem;
        font-weight: 800;
        color: white;
        line-height: 1.2;
        margin-bottom: 20px;
    }
    .hero-title span {
        background: linear-gradient(135deg, #FFD89B, #C67C4E);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .hero-desc {
        font-size: 1.1rem;
        color: rgba(255,255,255,0.8);
        margin-bottom: 30px;
        line-height: 1.6;
        max-width: 500px;
    }
    .hero-stats {
        display: flex;
        gap: 40px;
        margin-top: 30px;
    }
    .hero-stat {
        text-align: center;
    }
    .hero-stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: #FFD89B;
    }
    .hero-stat-label {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.6);
    }
    .hero-image {
        position: relative;
        animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
    }
    
    /* ==================== FEATURES SECTION ==================== */
    .features-section {
        padding: 60px 0;
        background: linear-gradient(135deg, #f9f5f0 0%, #fff 100%);
        border-radius: 40px;
        margin-bottom: 60px;
    }
    .feature-card {
        text-align: center;
        padding: 30px 20px;
        transition: all 0.4s ease;
        border-radius: 20px;
        background: white;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .feature-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #FF6B35, #FF8C42);
        border-radius: 25px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    .feature-card:hover .feature-icon {
        transform: rotateY(180deg);
        border-radius: 50%;
    }
    .feature-icon i {
        font-size: 35px;
        color: white;
    }
    .feature-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2C1810;
        margin-bottom: 10px;
    }
    .feature-desc {
        font-size: 0.85rem;
        color: #718096;
        line-height: 1.5;
    }
    
    /* ==================== ABOUT SECTION ==================== */
    .about-section {
        padding: 60px 0;
        margin-bottom: 60px;
    }
    .about-image {
        border-radius: 30px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .about-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #2C1810;
        margin-bottom: 20px;
    }
    .about-text {
        color: #718096;
        line-height: 1.8;
        margin-bottom: 20px;
    }
    .about-signature {
        font-family: 'Brush Script MT', cursive;
        font-size: 1.8rem;
        color: #C67C4E;
        margin-top: 20px;
    }
    
    /* ==================== TESTIMONIAL SECTION ==================== */
    .testimonial-section {
        background: linear-gradient(135deg, #2C1810 0%, #4A2C1A 100%);
        border-radius: 40px;
        padding: 60px;
        margin-bottom: 60px;
        color: white;
    }
    .testimonial-card {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 30px;
        transition: all 0.3s ease;
        height: 100%;
    }
    .testimonial-card:hover {
        transform: translateY(-5px);
        background: rgba(255,255,255,0.15);
    }
    .testimonial-quote {
        font-size: 2rem;
        color: #FFD89B;
        margin-bottom: 15px;
    }
    .testimonial-text {
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 20px;
        color: rgba(255,255,255,0.9);
    }
    .testimonial-author {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .testimonial-avatar {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #FF6B35, #FF8C42);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
    }
    .testimonial-name {
        font-weight: 700;
        margin-bottom: 5px;
    }
    .testimonial-role {
        font-size: 0.75rem;
        color: #FFD89B;
    }
    .stars {
        color: #FFD700;
        margin-bottom: 10px;
    }
    
    /* ==================== CTA SECTION ==================== */
    .cta-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 40px;
        padding: 60px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .cta-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
    }
    .cta-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 20px;
    }
    .cta-desc {
        color: rgba(255,255,255,0.9);
        margin-bottom: 30px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }
    .btn-cta {
        background: white;
        color: #667eea;
        padding: 15px 40px;
        border-radius: 40px;
        font-weight: 700;
        transition: all 0.3s ease;
        display: inline-block;
    }
    .btn-cta:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        color: #667eea;
    }
    
    /* ==================== DIVIDER ==================== */
    .divider-aesthetic {
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #FF6B35, #FFD89B);
        margin: 20px auto;
        border-radius: 3px;
    }
    
    /* ==================== RESPONSIVE ==================== */
    @media (max-width: 768px) {
        .hero-aesthetic {
            padding: 50px 25px;
        }
        .hero-title {
            font-size: 2.5rem;
        }
        .about-title {
            font-size: 1.8rem;
        }
        .cta-title {
            font-size: 1.8rem;
        }
        .testimonial-section {
            padding: 30px;
        }
    }
</style>

<!-- Hero Section Aesthetic -->
<div class="hero-aesthetic">
    <div class="row align-items-center">
        <div class="col-lg-6">
            <div class="hero-badge">✦ IRON VELSITAS ✦</div>
            <h1 class="hero-title">
                Start Your Day with<br>
                <span>Perfect Coffee</span><br>
                <span class="hero-title" style="font-size: 25px;"></span>"mai inung kopi"</span>
            </h1>
            <p class="hero-desc">
                Rasakan pengalaman kopi terbaik dengan biji kopi pilihan dari perkebunan terbaik Di Colol. 
                Diolah dengan sempurna untuk menghasilkan cita rasa yang tak terlupakan.
            </p>
            <div class="mt-4">
                <a href="{{ route('shop.products') }}" class="btn-cta">
                    Shop Now <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-6 text-center">
            <div class="hero-image">
                <img src="https://cdn-icons-png.flaticon.com/512/924/924514.png" alt="Coffee" style="width: 280px;">
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="features-section">
    <div class="container">
        <div class="row">
            <!-- Free Delivery -->
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h5 class="feature-title">Free Delivery</h5>
                    <p class="feature-desc">
                        <a href="{{ route('free.shipping') }}" class="feature-link">
                            <i class="fas fa-crown"></i> gratis ongkir
                        </a>
                    </p>
                </div>
            </div>
            
            <!-- Premium Quality -->
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h5 class="feature-title">Premium Quality</h5>
                    <p class="feature-desc">
                        <a href="{{ route('premium.quality') }}" class="feature-link">
                            <i class="fas fa-crown"></i> 100% biji kopi asli Indonesia pilihan
                        </a>
                    </p>
                </div>
            </div>
            
            <!-- 24/7 Support -->
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h5 class="feature-title">24/7 Support</h5>
                    <p class="feature-desc">
                        <a href="{{ route('customer.service') }}" class="feature-link">
                            <i class="fas fa-headset"></i> Layanan customer service 24 jam
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .features-section {
        padding: 60px 0;
        background: #fdf9f5;
    }
    
    .feature-card {
        background: white;
        border-radius: 20px;
        padding: 35px 25px;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        height: 100%;
    }
    
    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    .feature-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #f78c01, #fcd00c);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    
    .feature-icon i {
        font-size: 28px;
        color: white;
    }
    
    .feature-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2C1810;
        margin-bottom: 12px;
    }
    
    .feature-desc {
        font-size: 0.85rem;
        color: #666;
        line-height: 1.5;
        margin: 0;
    }
    
    .feature-link {
        color: #666;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }
    
    .feature-link:hover {
        color: #C49A6C;
        transform: translateX(3px);
    }
    
    .feature-link i {
        margin-right: 5px;
        color: #C49A6C;
    }
    
    @media (max-width: 768px) {
        .features-section {
            padding: 40px 0;
        }
        .feature-card {
            padding: 25px 20px;
        }
        .feature-icon {
            width: 55px;
            height: 55px;
        }
        .feature-icon i {
            font-size: 22px;
        }
    }
</style>
<!-- About Section -->
<div class="about-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=600&h=400&fit=crop" alt="Coffee Shop" class="w-100" style="height: 350px; object-fit: cover;">
                </div>
            </div>
            <div class="col-lg-6">
                <h2 class="about-title">About Kopi Ancol</h2>
                <div class="divider-aesthetic" style="margin-left: 0;"></div>
                <p class="about-text">
                    Kopi Ancol adalah coffee shop premium yang menyajikan biji kopi terbaik dari daerah Colol,Manggarai Timur,NTT. 
                    Kami berkomitmen untuk memberikan pengalaman kopi terbaik dengan cita rasa yang autentik dan berkualitas tinggi.
                </p>
                <p class="about-text">
                    Setiap biji kopi kami pilih langsung dari petani lokal dan diolah dengan standar internasional 
                    untuk menghasilkan cita rasa yang sempurna di setiap cangkir.
                </p>
                <div class="about-signature">
                    — Kopi Ancol Team
                </div>
            </div>
        </div>
    </div>
</div>
<!-- CTA Section -->
<div class="cta-section">
    <div class="container">
        <h2 class="cta-title">Ready to Experience the Best Coffee?</h2>
        <p class="cta-desc">
            Temukan kopi favorit Anda sekarang dan rasakan kenikmatan sejati dari biji kopi pilihan Indonesia.
        </p>
        <a href="{{ route('shop.products') }}" class="btn-cta">
            Shop Now <i class="fas fa-arrow-right ms-2"></i>
        </a>
    </div>
</div>
@endsection