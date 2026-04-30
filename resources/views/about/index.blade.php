@extends('layouts.app')

@section('content')
<style>
    /* Hero Section */
    .about-hero {
        background: linear-gradient(135deg, #1a1a2e 0%, #2C1810 100%);
        padding: 80px 0;
        color: white;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .about-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.05)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
        background-size: cover;
        opacity: 0.3;
    }
    
    .about-hero .container {
        position: relative;
        z-index: 1;
    }
    
    .about-hero h1 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 20px;
    }
    
    .about-hero p {
        font-size: 1.2rem;
        max-width: 700px;
        margin: 0 auto;
        opacity: 0.9;
    }
    
    /* Story Section */
    .story-section {
        padding: 70px 0;
        background: #fdf9f5;
    }
    
    .story-image {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 35px rgba(0,0,0,0.1);
    }
    
    .story-image img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .story-image:hover img {
        transform: scale(1.05);
    }
    
    .story-content h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #2C1810;
        margin-bottom: 20px;
    }
    
    .story-content p {
        color: #666;
        line-height: 1.8;
        margin-bottom: 20px;
    }
    
    /* Stats Section */
    .stats-section {
        background: linear-gradient(135deg, #2C1810 0%, #4A2C1A 100%);
        padding: 60px 0;
        color: white;
    }
    
    .stat-card {
        text-align: center;
        padding: 20px;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
        color: #FFD89B;
    }
    
    .stat-label {
        font-size: 1rem;
        opacity: 0.9;
    }
    
    /* Values Section */
    .values-section {
        padding: 70px 0;
        background: white;
    }
    
    .section-title {
        text-align: center;
        font-size: 2rem;
        font-weight: 700;
        color: #2C1810;
        margin-bottom: 15px;
    }
    
    .section-subtitle {
        text-align: center;
        color: #666;
        margin-bottom: 50px;
    }
    
    .value-card {
        text-align: center;
        padding: 30px 20px;
        border-radius: 20px;
        transition: all 0.3s ease;
        background: #fdf9f5;
        height: 100%;
    }
    
    .value-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    .value-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #C49A6C, #A67C52);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    
    .value-icon i {
        font-size: 2rem;
        color: white;
    }
    
    .value-card h4 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2C1810;
        margin-bottom: 15px;
    }
    
    .value-card p {
        color: #666;
        line-height: 1.6;
    }
    
    /* Team Section */
    .team-section {
        padding: 70px 0;
        background: #fdf9f5;
    }
    
    .team-card {
        text-align: center;
        background: white;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        height: 100%;
    }
    
    .team-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 35px rgba(0,0,0,0.1);
    }
    
    .team-image {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        margin: 30px auto 20px;
        border: 5px solid #C49A6C;
    }
    
    .team-card h4 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2C1810;
        margin-bottom: 5px;
    }
    
    .team-position {
        color: #C49A6C;
        font-size: 0.85rem;
        margin-bottom: 15px;
    }
    
    .team-description {
        color: #666;
        font-size: 0.9rem;
        padding: 0 20px 20px;
    }
    
    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, #C49A6C 0%, #A67C52 100%);
        padding: 60px 0;
        text-align: center;
        color: white;
    }
    
    .cta-section h2 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 20px;
    }
    
    .cta-section p {
        font-size: 1.1rem;
        margin-bottom: 30px;
        opacity: 0.95;
    }
    
    .btn-cta {
        background: white;
        color: #C49A6C;
        border: none;
        padding: 12px 35px;
        border-radius: 40px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-cta:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        color: #A67C52;
    }
    
    @media (max-width: 768px) {
        .about-hero h1 {
            font-size: 2rem;
        }
        .stat-number {
            font-size: 1.8rem;
        }
        .story-content {
            margin-top: 30px;
        }
    }
</style>

<!-- Hero Section -->
<div class="about-hero">
    <div class="container">
        <h1><i class="fas fa-mug-hot"></i> Tentang Kopi Ancol</h1>
        <p>Kisah perjalanan kami dalam menyajikan kopi terbaik dari hati ke hati</p>
    </div>
</div>

<!-- Story Section -->
<div class="story-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="story-image">
                    <img src="https://images.unsplash.com/photo-1442512595331-e89e73853f31?w=600&h=400&fit=crop" alt="Kopi Ancol Story">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="story-content">
                    <h2>Perjalanan Kami</h2>
                    <p>Kopi Ancol lahir dari kecintaan yang mendalam terhadap kopi Indonesia. Berawal dari sebuah mimpi kecil di tahun 2020, kami memulai perjalanan untuk membawa cita rasa kopi terbaik dari Colol, Manggarai Timur ke seluruh penjuru Nusantara.</p>
                    <p>Setiap cangkir kopi yang kami sajikan adalah hasil dari perjalanan panjang - mulai dari pemilihan biji kopi terbaik dari petani lokal, proses roasting yang teliti, hingga penyajian yang penuh dedikasi oleh barista kami yang berpengalaman.</p>
                    <p>Kami percaya bahwa secangkir kopi bukan hanya tentang rasa, tetapi juga tentang cerita, kebersamaan, dan semangat yang ingin kami bagikan kepada setiap pelanggan.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-number">{{ number_format($totalProducts) }}+</div>
                    <div class="stat-label">Varian Kopi</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-number">{{ number_format($totalBranches) }}</div>
                    <div class="stat-label">Cabang</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-number">{{ number_format($totalCustomers) }}+</div>
                    <div class="stat-label">Pelanggan</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="stat-number">{{ number_format($totalOrders) }}+</div>
                    <div class="stat-label">Pesanan</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Values Section -->
<div class="values-section">
    <div class="container">
        <h2 class="section-title">Nilai Kami</h2>
        <p class="section-subtitle">Prinsip yang menjadi fondasi setiap langkah kami</p>
        <div class="row g-4">
            @foreach($values as $value)
            <div class="col-md-3 col-sm-6">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas {{ $value['icon'] }}"></i>
                    </div>
                    <h4>{{ $value['title'] }}</h4>
                    <p>{{ $value['description'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Team Section -->
<div class="team-section">
    <div class="container">
        <h2 class="section-title">Tim Kami</h2>
        <p class="section-subtitle">Orang-orang di balik setiap cangkir kopi istimewa</p>
        <div class="row g-4">
            @foreach($team as $member)
            <div class="col-md-3 col-sm-6">
                <div class="team-card">
                    @if($member['image'])
                        <img src="{{ asset($member['image']) }}" class="team-image" alt="{{ $member['name'] }}">
                    @else
                        <div class="team-image bg-secondary d-flex align-items-center justify-content-center mx-auto">
                            <i class="fas fa-user fa-3x text-white"></i>
                        </div>
                    @endif
                    <h4>{{ $member['name'] }}</h4>
                    <div class="team-position">{{ $member['position'] }}</div>
                    <p class="team-description">{{ $member['description'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="cta-section">
    <div class="container">
        <h2>Yuk, Nikmati Kopi Terbaik Kami!</h2>
        <p>Kunjungi cabang terdekat atau pesan online sekarang juga</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('shop.products') }}" class="btn btn-cta">
                <i class="fas fa-coffee"></i> Pesan Sekarang
            </a>
            <a href="{{ route('locations.index') }}" class="btn btn-cta">
                <i class="fas fa-map-marker-alt"></i> Lihat Cabang Kami
            </a>
        </div>
    </div>
</div>
@endsection