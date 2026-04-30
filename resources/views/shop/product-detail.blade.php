@extends('layouts.app')

@section('content')
<style>
    /* Product Detail Image Carousel */
    .product-detail-image {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        position: relative;
    }
    
    /* Carousel Styles */
    .carousel-container {
        position: relative;
        width: 100%;
        overflow: hidden;
        border-radius: 20px;
    }
    
    .carousel-slide {
        display: flex;
        transition: transform 0.5s ease-in-out;
    }
    
    .carousel-slide img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        flex-shrink: 0;
    }
    
    .carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.5);
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        z-index: 10;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .carousel-btn:hover {
        background: #FF6B35;
        transform: translateY(-50%) scale(1.1);
    }
    
    .carousel-btn-prev {
        left: 15px;
    }
    
    .carousel-btn-next {
        right: 15px;
    }
    
    .carousel-dots {
        position: absolute;
        bottom: 15px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
        z-index: 10;
    }
    
    .carousel-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(255,255,255,0.5);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .carousel-dot.active {
        background: #FF6B35;
        width: 25px;
        border-radius: 10px;
    }
    
    /* Thumbnail Navigation */
    .thumbnail-container {
        display: flex;
        gap: 10px;
        margin-top: 15px;
        overflow-x: auto;
        padding-bottom: 5px;
    }
    
    .thumbnail-item {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        opacity: 0.6;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    
    .thumbnail-item.active {
        opacity: 1;
        border: 2px solid #FF6B35;
        transform: scale(1.05);
    }
    
    .thumbnail-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .thumbnail-item:hover {
        opacity: 1;
    }
    
    /* Fallback jika hanya 1 gambar */
    .single-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
    }
    
    /* Product Info Styles */
    .product-title {
        font-size: 2rem;
        font-weight: 800;
        color: #2C1810;
        margin-bottom: 15px;
    }
    .product-category {
        display: inline-block;
        padding: 5px 15px;
        background: #FF6B35;
        color: white;
        border-radius: 20px;
        font-size: 0.8rem;
        margin-bottom: 15px;
    }
    .product-price {
        font-size: 2rem;
        font-weight: 800;
        color: #FF6B35;
        margin-bottom: 20px;
    }
    .product-description {
        color: #6c757d;
        line-height: 1.8;
        margin-bottom: 25px;
    }
    .product-stock {
        padding: 10px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .stock-available {
        background: #E8F5E9;
        color: #2E7D32;
    }
    .stock-low {
        background: #FFF3E0;
        color: #ED8936;
    }
    .stock-out {
        background: #FFEBEE;
        color: #DC3545;
    }
    .quantity-input {
        width: 80px;
        text-align: center;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 10px;
    }
    .btn-buy-now {
        background: linear-gradient(135deg, #FF6B35, #FF8C42);
        border: none;
        padding: 12px 30px;
        border-radius: 30px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-buy-now:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255,107,53,0.3);
    }
    .btn-add-cart-detail {
        background: #2C1810;
        border: none;
        padding: 12px 30px;
        border-radius: 30px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-add-cart-detail:hover {
        background: #4A2C1A;
        transform: translateY(-2px);
    }
    .btn-dinein-detail {
        background: #2E7D32;
        border: none;
        padding: 12px 30px;
        border-radius: 30px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-dinein-detail:hover {
        background: #1B5E20;
        transform: translateY(-2px);
    }
    .action-buttons-detail {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        flex-wrap: wrap;
    }
    .specs-list {
        list-style: none;
        padding: 0;
        margin: 20px 0;
    }
    .specs-list li {
        padding: 10px 0;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        align-items: center;
    }
    .specs-list li i {
        width: 30px;
        color: #FF6B35;
    }
    
    /* Review Section Styles */
    .rating-summary {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
    }
    .review-item {
        transition: all 0.2s ease;
    }
    .review-item:hover {
        background: #f8f9fa;
        padding-left: 10px;
    }
    .btn-write-review {
        background: linear-gradient(135deg, #FF6B35, #FF8C42);
        border: none;
        padding: 10px 20px;
        border-radius: 25px;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-write-review:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255,107,53,0.3);
        color: white;
    }
    
    /* Related Products */
    .related-product-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        height: 100%;
    }
    .related-product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .related-product-image {
        height: 180px;
        object-fit: cover;
        width: 100%;
    }
    
    @media (max-width: 768px) {
        .carousel-slide img, .single-image {
            height: 300px;
        }
        .thumbnail-item {
            width: 60px;
            height: 60px;
        }
    }
</style>

<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.products') }}">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Image Carousel -->
        <div class="col-md-6 mb-4">
            @php
                // Kumpulkan semua gambar
                $images = [];
                
                // Gambar utama
                if($product->image && file_exists(public_path($product->image))) {
                    $images[] = asset($product->image);
                }
                
                // Gambar tambahan dari database (asumsi ada kolom images json)
                if(isset($product->images) && $product->images) {
                    $additionalImages = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                    if(is_array($additionalImages)) {
                        foreach($additionalImages as $img) {
                            if($img && file_exists(public_path($img))) {
                                $images[] = asset($img);
                            }
                        }
                    }
                }
                
                // Fallback jika tidak ada gambar
                if(empty($images)) {
                    $images[] = 'https://placehold.co/600x400/e8d5b7/2C1810?text=' . urlencode($product->name);
                }
            @endphp
            
            @if(count($images) > 1)
                <!-- Carousel untuk multiple images -->
                <div class="product-detail-image">
                    <div class="carousel-container">
                        <button class="carousel-btn carousel-btn-prev" onclick="prevSlide()">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        
                        <div class="carousel-slide" id="carouselSlide">
                            @foreach($images as $img)
                                <img src="{{ $img }}" alt="{{ $product->name }}">
                            @endforeach
                        </div>
                        
                        <button class="carousel-btn carousel-btn-next" onclick="nextSlide()">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        
                        <div class="carousel-dots" id="carouselDots">
                            @foreach($images as $index => $img)
                                <div class="carousel-dot {{ $index == 0 ? 'active' : '' }}" onclick="goToSlide({{ $index }})"></div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Thumbnail Navigation -->
                    <div class="thumbnail-container" id="thumbnailContainer">
                        @foreach($images as $index => $img)
                            <div class="thumbnail-item {{ $index == 0 ? 'active' : '' }}" onclick="goToSlide({{ $index }})">
                                <img src="{{ $img }}" alt="Thumbnail {{ $index + 1 }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Single image (no carousel) -->
                <div class="product-detail-image">
                    <img src="{{ $images[0] }}" alt="{{ $product->name }}" class="single-image">
                </div>
            @endif
        </div>
        
        <!-- Product Info -->
        <div class="col-md-6">
            <span class="product-category">{{ $product->category }}</span>
            <h1 class="product-title">{{ $product->name }}</h1>
            
            <div class="product-price">
                Rp {{ number_format($product->price, 0, ',', '.') }}
                <small class="text-muted">/pack</small>
            </div>
            
            <div class="product-description">
                {{ $product->description }}
            </div>
            
            <!-- Stock Status -->
            <div class="product-stock">
                @if($product->stock > 10)
                    <div class="stock-available p-2 rounded">
                        <i class="fas fa-check-circle"></i> Stok tersedia ({{ $product->stock }} pcs)
                    </div>
                @elseif($product->stock > 0 && $product->stock <= 10)
                    <div class="stock-low p-2 rounded">
                        <i class="fas fa-exclamation-triangle"></i> Stok terbatas! Tersisa {{ $product->stock }} pcs
                    </div>
                @else
                    <div class="stock-out p-2 rounded">
                        <i class="fas fa-times-circle"></i> Stok habis
                    </div>
                @endif
            </div>
            
            <!-- Specs -->
            <ul class="specs-list">
                <li><i class="fas fa-tag"></i> Kategori: {{ $product->category }}</li>
                <li><i class="fas fa-seedling"></i> Asal: Indonesia</li>
                <li><i class="fas fa-fire"></i> 100% Premium Quality</li>
            </ul>
            
            <!-- Action Buttons -->
            @if($product->stock > 0)
                <div class="action-buttons-detail">
                    <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1" id="detailQuantity">
                        <button type="submit" class="btn-add-cart-detail">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                    </form>
                    
                    <button type="button" class="btn-dinein-detail" 
                            data-bs-toggle="modal" data-bs-target="#dineInModal{{ $product->id }}">
                        <i class="fas fa-utensils"></i> Dine In
                    </button>
                    
                    <a href="{{ route('checkout') }}" class="btn-buy-now">
                        <i class="fas fa-bolt"></i> Buy Now
                    </a>
                </div>
            @else
                <button class="btn btn-secondary btn-lg w-100" disabled>
                    <i class="fas fa-times-circle"></i> Out of Stock
                </button>
            @endif
        </div>
    </div>
    
    <!-- ==================== REVIEW SECTION ==================== -->
    <div class="row mt-5">
        <div class="col-12">
            <h4 class="fw-bold">Customer Reviews</h4>
            <div class="divider" style="width: 60px; height: 3px; background: #FF6B35; margin-bottom: 20px;"></div>
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="text-center p-4 bg-light rounded">
                        <h2 class="display-4 fw-bold text-primary">{{ number_format($averageRating, 1) }}</h2>
                        <div class="mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($averageRating))
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-muted"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="text-muted">Dari {{ $totalReviews }} ulasan</p>
                    </div>
                </div>
                <div class="col-md-8">
                    @auth
                        @if(!$hasReviewed)
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Sudah membeli produk ini? 
                                <a href="{{ route('review.create', $product->id) }}" class="alert-link">Tulis ulasan Anda</a>
                            </div>
                        @else
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> Anda sudah memberikan ulasan untuk produk ini.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            <a href="{{ route('login') }}">Login</a> untuk menulis ulasan.
                        </div>
                    @endauth
                </div>
            </div>
            
            @if($reviews->count() > 0)
                @foreach($reviews as $review)
                <div class="border rounded p-3 mb-3 review-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-muted"></i>
                                    @endif
                                @endfor
                            </div>
                            <p class="mb-1">{{ $review->comment }}</p>
                            <small class="text-muted">
                                Oleh <strong>{{ $review->user->name }}</strong> • {{ $review->created_at->diffForHumans() }}
                            </small>
                        </div>
                        @if($review->is_verified_purchase)
                            <span class="badge bg-success"><i class="fas fa-check-circle"></i> Verified Purchase</span>
                        @endif
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-4">
                    <i class="fas fa-comment-dots fa-3x text-muted mb-2"></i>
                    <p class="text-muted">Belum ada ulasan untuk produk ini.</p>
                    <p class="text-muted">Jadilah yang pertama memberikan ulasan!</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Related Products Section -->
    @if($relatedProducts->count() > 0)
    <div class="row mt-5 pt-4">
        <div class="col-12">
            <h3 class="fw-bold mb-4">You May Also Like</h3>
            <div class="divider" style="width: 60px; height: 3px; background: #FF6B35; margin-bottom: 20px;"></div>
        </div>
        
        <div class="row">
            @foreach($relatedProducts as $related)
            <div class="col-md-3 col-6 mb-4">
                <div class="related-product-card">
                    @if($related->image && file_exists(public_path($related->image)))
                        <img src="{{ asset($related->image) }}" class="related-product-image" alt="{{ $related->name }}">
                    @else
                        <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 180px;">
                            <i class="fas fa-mug-hot fa-3x text-white"></i>
                        </div>
                    @endif
                    <div class="p-3">
                        <h6 class="fw-bold mb-1">{{ $related->name }}</h6>
                        <p class="text-primary fw-bold">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                        <a href="{{ route('product.detail', $related->id) }}" class="btn btn-sm btn-outline-primary w-100">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Dine In Modal -->
<div class="modal fade" id="dineInModal{{ $product->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #2C1810 0%, #4A2C1A 100%); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-utensils"></i> Pesan di Tempat - {{ $product->name }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('dinein.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" value="{{ $product->name }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Harga</label>
                                <input type="text" class="form-control" value="Rp {{ number_format($product->price, 0, ',', '.') }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Jumlah *</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="table_number" class="form-label">Nomor Meja *</label>
                                <input type="number" name="table_number" id="table_number" class="form-control" placeholder="Contoh: 5" min="1" max="50" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="customer_name" class="form-label">Nama Customer (Opsional)</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="Masukkan nama Anda">
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan (Opsional)</label>
                        <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Contoh: Tidak pakai gula, extra panas, dll"></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Pesanan akan langsung dikirim ke dapur/barista.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="fas fa-paper-plane"></i> Pesan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let currentSlide = 0;
    let totalSlides = {{ count($images) }};
    
    function updateCarousel() {
        const slide = document.getElementById('carouselSlide');
        if (slide) {
            slide.style.transform = `translateX(-${currentSlide * 100}%)`;
        }
        
        // Update dots
        document.querySelectorAll('.carousel-dot').forEach((dot, index) => {
            dot.classList.toggle('active', index === currentSlide);
        });
        
        // Update thumbnails
        document.querySelectorAll('.thumbnail-item').forEach((thumb, index) => {
            thumb.classList.toggle('active', index === currentSlide);
        });
    }
    
    function nextSlide() {
        if (currentSlide < totalSlides - 1) {
            currentSlide++;
        } else {
            currentSlide = 0; // Loop back to first
        }
        updateCarousel();
    }
    
    function prevSlide() {
        if (currentSlide > 0) {
            currentSlide--;
        } else {
            currentSlide = totalSlides - 1; // Loop to last
        }
        updateCarousel();
    }
    
    function goToSlide(index) {
        currentSlide = index;
        updateCarousel();
    }
    
    // Auto slide every 5 seconds
    let autoSlideInterval;
    
    function startAutoSlide() {
        autoSlideInterval = setInterval(() => {
            nextSlide();
        }, 5000);
    }
    
    function stopAutoSlide() {
        if (autoSlideInterval) {
            clearInterval(autoSlideInterval);
        }
    }
    
    // Initialize carousel if multiple images
    @if(count($images) > 1)
    document.addEventListener('DOMContentLoaded', function() {
        startAutoSlide();
        
        // Pause auto slide on hover
        const carouselContainer = document.querySelector('.carousel-container');
        if (carouselContainer) {
            carouselContainer.addEventListener('mouseenter', stopAutoSlide);
            carouselContainer.addEventListener('mouseleave', startAutoSlide);
        }
    });
    @endif
    
    // Auto close modal after submit
    document.querySelectorAll('form[action*="dinein.store"]').forEach(form => {
        form.addEventListener('submit', function() {
            setTimeout(() => {
                let modal = bootstrap.Modal.getInstance(document.querySelector('.modal.show'));
                if (modal) modal.hide();
            }, 100);
        });
    });
    
    // Update quantity saat diubah
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantityInput.addEventListener('change', function() {
            if (this.value < 1) this.value = 1;
            if (this.value > {{ $product->stock }}) {
                this.value = {{ $product->stock }};
                alert('Stok maksimal {{ $product->stock }} item');
            }
        });
    }
</script>
@endsection