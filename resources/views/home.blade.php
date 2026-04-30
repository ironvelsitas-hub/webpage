<!-- Premium Quality Section -->
<div class="premium-quality-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="premium-image">
                    <img src="https://images.unsplash.com/photo-1442512595331-e89e73853f31?w=600&h=500&fit=crop" alt="Premium Coffee" class="img-fluid rounded-4 shadow-lg">
                    <div class="premium-badge">
                        <i class="fas fa-certificate"></i> 100% Premium
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="premium-content">
                    <span class="premium-tag">✨ Premium Quality</span>
                    <h2 class="premium-title">100% Biji Kopi Asli <br>Indonesia Pilihan</h2>
                    <p class="premium-desc">Kami hanya menggunakan biji kopi terbaik dari perkebunan premium di Colol, Manggarai Timur. Setiap biji dipilih secara manual oleh petani berpengalaman untuk memastikan kualitas terbaik.</p>
                    
                    <div class="premium-features">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-seedling"></i>
                            </div>
                            <div class="feature-text">
                                <h5>100% Organik</h5>
                                <p>Tumbuh alami tanpa pestisida</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="feature-text">
                                <h5>Specialty Grade</h5>
                                <p>Skor cupping 85+</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-hand-holding-heart"></i>
                            </div>
                            <div class="feature-text">
                                <h5>Fair Trade</h5>
                                <p>Harga adil untuk petani</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-fire"></i>
                            </div>
                            <div class="feature-text">
                                <h5>Fresh Roasted</h5>
                                <p>Diroasting setelah order</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="premium-certificate">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5a/Organic_certification_logo.svg/60px-Organic_certification_logo.svg.png" alt="Organic">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Fairtrade_Logo.svg/60px-Fairtrade_Logo.svg.png" alt="Fairtrade">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2b/UTZ_Certified_logo.svg/60px-UTZ_Certified_logo.svg.png" alt="UTZ">
                        <div class="certificate-text">
                            <i class="fas fa-check-circle text-success"></i> Tersertifikasi Premium Quality
                        </div>
                    </div>
                    
                    <a href="{{ route('shop.products') }}" class="btn-premium">
                        <i class="fas fa-shopping-cart"></i> Beli Sekarang
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Quality Section */
    .premium-quality-section {
        padding: 80px 0;
        background: linear-gradient(135deg, #fff8f0 0%, #fdf9f5 100%);
        position: relative;
        overflow: hidden;
    }
    
    .premium-quality-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(196,154,108,0.03)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
        background-size: cover;
        pointer-events: none;
    }
    
    .premium-image {
        position: relative;
        border-radius: 30px;
        overflow: hidden;
    }
    
    .premium-image img {
        transition: transform 0.5s ease;
        width: 100%;
        height: 450px;
        object-fit: cover;
    }
    
    .premium-image:hover img {
        transform: scale(1.05);
    }
    
    .premium-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #C49A6C, #A67C52);
        color: white;
        padding: 8px 16px;
        border-radius: 40px;
        font-size: 12px;
        font-weight: 600;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .premium-tag {
        display: inline-block;
        background: rgba(196, 154, 108, 0.15);
        color: #C49A6C;
        padding: 6px 15px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 20px;
        letter-spacing: 1px;
    }
    
    .premium-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #2C1810;
        margin-bottom: 20px;
        line-height: 1.2;
    }
    
    .premium-desc {
        color: #666;
        line-height: 1.7;
        margin-bottom: 30px;
    }
    
    .premium-features {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .feature-item {
        display: flex;
        align-items: center;
        gap: 15px;
        background: white;
        padding: 15px;
        border-radius: 16px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .feature-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .feature-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #C49A6C, #A67C52);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .feature-icon i {
        font-size: 24px;
        color: white;
    }
    
    .feature-text h5 {
        font-size: 1rem;
        font-weight: 700;
        color: #2C1810;
        margin-bottom: 3px;
    }
    
    .feature-text p {
        font-size: 0.75rem;
        color: #999;
        margin: 0;
    }
    
    .premium-certificate {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        padding: 15px 0;
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
        margin-bottom: 25px;
    }
    
    .premium-certificate img {
        height: 35px;
        width: auto;
        opacity: 0.7;
        transition: opacity 0.3s ease;
    }
    
    .premium-certificate img:hover {
        opacity: 1;
    }
    
    .certificate-text {
        font-size: 12px;
        color: #666;
    }
    
    .btn-premium {
        display: inline-flex;
        align-items: center;
        background: linear-gradient(135deg, #C49A6C, #A67C52);
        color: white;
        padding: 14px 32px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(196, 154, 108, 0.3);
    }
    
    .btn-premium:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(196, 154, 108, 0.4);
        color: white;
    }
    
    @media (max-width: 768px) {
        .premium-quality-section {
            padding: 50px 0;
        }
        .premium-title {
            font-size: 1.8rem;
        }
        .premium-features {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        .premium-image img {
            height: 300px;
        }
    }
</style>