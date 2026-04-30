@extends('layouts.app')

@section('content')
<style>
    /* ========== VARIABLES & GLOBAL ========== */
    :root {
        --primary: #1c18f8;
        --primary-dark: #080f09;
        --primary-light: #E8D5B7;
        --secondary: #0ac3fc;
        --accent: #E67E22;
        --light-bg: #FDF9F5;
        --dark-bg: #1A1A2E;
        --text-dark: #f00c0c;
        --text-muted: rgb(15, 15, 13);
        --border-radius: 16px;
        --shadow-sm: 0 4px 12px rgba(0,0,0,0.05);
        --shadow-md: 0 8px 24px rgba(0,0,0,0.08);
        --shadow-lg: 0 16px 32px rgba(0,0,0,0.12);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* ========== HERO SECTION ========== */
    .products-header {
        background: linear-gradient(135deg, #eb4d4d 0%, #0c0c0c 100%);
        color: white;
        padding: 60px 0;
        margin-bottom: 40px;
        border-radius: 0 0 40px 40px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .products-header::before {
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
    
    .products-header .container {
        position: relative;
        z-index: 1;
    }
    
    .products-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        margin-bottom: 12px;
    }
    
    .products-header p {
        font-size: 1.1rem;
        opacity: 0.9;
        font-weight: 400;
    }

    /* ========== FILTER SECTION ========== */
    .filter-section {
        background: white;
        padding: 20px 24px;
        border-radius: var(--border-radius);
        margin-bottom: 32px;
        box-shadow: var(--shadow-md);
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .filter-section .form-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 6px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .search-box {
        position: relative;
    }
    
    .search-box i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary);
        font-size: 0.9rem;
        z-index: 1;
    }
    
    .search-box input {
        padding-left: 40px;
        border-radius: 40px;
        border: 1px solid #E8E2D9;
        background: var(--light-bg);
        transition: var(--transition);
        font-size: 0.85rem;
        padding-top: 8px;
        padding-bottom: 8px;
    }
    
    .search-box input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(196, 154, 108, 0.15);
        background: white;
    }
    
    .filter-section select.form-select {
        border-radius: 40px;
        border: 1px solid #E8E2D9;
        background: var(--light-bg);
        transition: var(--transition);
        cursor: pointer;
        font-size: 0.85rem;
        padding: 0.5rem 2rem 0.5rem 1rem;
    }
    
    .filter-section select.form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(196, 154, 108, 0.15);
    }
    
    .filter-section .btn-secondary {
        background: var(--secondary);
        border: none;
        border-radius: 40px;
        padding: 0.5rem;
        font-weight: 500;
        font-size: 0.85rem;
        transition: var(--transition);
        color: white;
    }
    
    .filter-section .btn-secondary:hover {
        background: #1a252f;
        transform: translateY(-2px);
    }

    /* ========== PRODUCT CARD - UKURAN LEBIH KECIL UNTUK 5 PRODUK PER BARIS ========== */
    .product-card {
        border-radius: 16px;
        overflow: hidden;
        transition: var(--transition);
        box-shadow: var(--shadow-sm);
        height: 100%;
        border: none;
        background: white;
        position: relative;
    }
    
    .product-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-lg);
    }
    
    .product-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--accent));
        transform: scaleX(0);
        transition: var(--transition);
    }
    
    .product-card:hover::after {
        transform: scaleX(1);
    }
    
    /* GAMBAR DIPERKECIL */
    .product-image {
        height: 160px;
        object-fit: cover;
        width: 100%;
        transition: transform 0.4s ease;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.03);
    }
    
    .product-image.bg-secondary {
        background: linear-gradient(135deg, #E8D5B7, #D4B896) !important;
    }
    
    .product-body {
        padding: 12px 12px 16px;
    }
    
    .product-category {
        font-size: 0.6rem;
        color: var(--primary);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        display: inline-block;
        background: rgba(196, 154, 108, 0.1);
        padding: 2px 8px;
        border-radius: 12px;
    }
    
    .product-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 6px;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .product-body .text-muted {
        color: var(--text-muted) !important;
        font-size: 0.7rem;
        line-height: 1.4;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-price {
        font-size: 1rem;
        font-weight: 800;
        color: var(--primary-dark);
        margin-bottom: 10px;
    }
    
    /* ========== BUTTONS - UKURAN LEBIH KECIL ========== */
    .btn-add-cart {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border: none;
        padding: 7px 8px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.7rem;
        color: white;
        width: 100%;
        transition: var(--transition);
        letter-spacing: 0.3px;
    }
    
    .btn-add-cart i {
        font-size: 0.7rem;
    }
    
    .btn-add-cart:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(196, 154, 108, 0.4);
        background: linear-gradient(135deg, var(--primary-dark), #8B5E3C);
    }
    
    .btn-add-cart:active {
        transform: scale(0.98);
    }
    
    .btn-dinein {
        background: #2E7D32;
        border: none;
        padding: 7px 8px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.7rem;
        color: white;
        transition: var(--transition);
        width: 100%;
    }
    
    .btn-dinein i {
        font-size: 0.7rem;
    }
    
    .btn-dinein:hover {
        background: #1B5E20;
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
    }
    
    .btn-detail {
        background: #6C757D;
        border: none;
        padding: 6px 8px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.65rem;
        color: white;
        transition: var(--transition);
        width: 100%;
        margin-top: 8px;
        display: inline-block;
        text-align: center;
        text-decoration: none;
    }
    
    .btn-detail i {
        font-size: 0.65rem;
    }
    
    .btn-detail:hover {
        background: #5a6268;
        transform: scale(1.02);
        color: white;
    }
    
    .action-buttons {
        display: flex;
        gap: 8px;
        margin-top: 0;
    }
    
    .action-buttons form {
        flex: 1;
    }
    
    .btn-add-cart:disabled {
        background: #adb5bd;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* ========== GRID 5 PRODUK PER BARIS ========== */
    /* Untuk layar sangat besar (desktop) */
    @media (min-width: 1400px) {
        .products-grid-custom {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
        }
        .product-item {
            width: 100%;
        }
    }
    
    /* Untuk layar besar (laptop) */
    @media (min-width: 1200px) and (max-width: 1399px) {
        .products-grid-custom {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
        }
        .product-item {
            width: 100%;
        }
    }
    
    /* Untuk layar sedang (tablet landscape) */
    @media (min-width: 992px) and (max-width: 1199px) {
        .products-grid-custom {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }
        .product-item {
            width: 100%;
        }
    }
    
    /* Untuk layar tablet */
    @media (min-width: 768px) and (max-width: 991px) {
        .products-grid-custom {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
        .product-item {
            width: 100%;
        }
    }
    
    /* Untuk layar mobile */
    @media (max-width: 767px) {
        .products-grid-custom {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        .product-item {
            width: 100%;
        }
    }
    
    /* Untuk layar sangat kecil */
    @media (max-width: 480px) {
        .products-grid-custom {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 12px;
        }
    }

    /* ========== MODAL STYLING ========== */
    .modal-content {
        border-radius: 20px;
        border: none;
        overflow: hidden;
    }
    
    .modal-header {
        background: linear-gradient(135deg, #2C1810 0%, #4A2C1A 100%);
        color: white;
        border: none;
        padding: 16px 20px;
    }
    
    .modal-header .modal-title {
        font-weight: 700;
        font-size: 1rem;
    }
    
    .modal-body {
        padding: 20px;
    }
    
    .modal-body .form-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 4px;
        font-size: 0.75rem;
    }
    
    .modal-body .form-control {
        border-radius: 10px;
        border: 1px solid #E8E2D9;
        padding: 8px 12px;
        font-size: 0.85rem;
        transition: var(--transition);
    }
    
    .modal-body .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(196, 154, 108, 0.15);
    }
    
    .modal-body .alert-info {
        background: rgba(196, 154, 108, 0.1);
        border: none;
        border-radius: 10px;
        color: var(--primary-dark);
        font-size: 0.75rem;
        padding: 10px;
    }
    
    .modal-footer {
        border-top: 1px solid #EFE9E3;
        padding: 16px 20px;
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border: none;
        padding: 8px 20px;
        border-radius: 25px;
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
        transition: var(--transition);
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(196, 154, 108, 0.3);
    }
    
    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .products-header {
            padding: 40px 0;
            margin-bottom: 24px;
        }
        
        .products-header h1 {
            font-size: 1.8rem;
        }
        
        .filter-section {
            padding: 16px;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 6px;
        }
        
        .modal-body {
            padding: 16px;
        }
    }
    
    @media (min-width: 1400px) {
        .container {
            max-width: 1400px;
        }
    }
    
    /* ========== ANIMATIONS ========== */
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
    
    .product-item {
        animation: fadeInUp 0.4s ease forwards;
    }
    
    /* Alert styling */
    .alert-warning, .alert-info {
        border-radius: 14px;
        border: none;
        background: #FFF8F0;
        color: var(--primary-dark);
        font-size: 0.9rem;
    }
    
    /* Empty state */
    .col-12 .alert {
        border-radius: 16px;
        padding: 32px 16px;
    }
</style>

<div class="products-header">
    <div class="container">
        <h1 class="display-5 fw-bold">Our Coffee Collection</h1>
        <p class="lead">Temukan kopi yang sempurna untuk selera Anda</p>
    </div>
</div>

<div class="container">
    <!-- Filter Section -->
    <div class="filter-section">
        <div class="row align-items-end">
            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">Cari Produk</label>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari kopi favoritmu..." onkeyup="filterProducts()">
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label fw-bold">Filter Kategori</label>
                <select id="categoryFilter" class="form-select" onchange="filterProducts()">
                    <option value="all">Semua Kategori</option>
                    <option value="Espresso">Espresso</option>
                    <option value="Single Origin">Single Origin</option>
                    <option value="Blend">Blend</option>
                    <option value="Instant">Instant</option>
                    <option value="Premium">Premium</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label fw-bold">Urutkan</label>
                <select id="sortFilter" class="form-select" onchange="filterProducts()">
                    <option value="default">Default</option>
                    <option value="price_asc">Termurah</option>
                    <option value="price_desc">Termahal</option>
                    <option value="name_asc">Nama A-Z</option>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <button class="btn btn-secondary w-100" onclick="resetFilters()">
                    <i class="fas fa-undo-alt"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Products Grid - Menggunakan grid custom untuk 5 produk per baris -->
    <div class="products-grid-custom" id="productsGrid">
        @forelse($products as $product)
        <div class="product-item" 
             data-name="{{ strtolower($product->name) }}" 
             data-category="{{ $product->category }}"
             data-price="{{ $product->price }}">
            <div class="card product-card h-100">
                @if($product->image && file_exists(public_path($product->image)))
                    <img src="{{ asset($product->image) }}" class="product-image" alt="{{ $product->name }}">
                @else
                    <div class="product-image bg-secondary d-flex align-items-center justify-content-center">
                        <i class="fas fa-mug-hot fa-3x text-white"></i>
                    </div>
                @endif
                <div class="product-body">
                    <div class="product-category">{{ $product->category }}</div>
                    <h5 class="product-title" title="{{ $product->name }}">{{ $product->name }}</h5>
                    <p class="text-muted small">{{ Str::limit($product->description, 60) }}</p>
                    <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    
                    @if($product->stock > 0)
                        <div class="action-buttons">
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <button type="submit" class="btn-add-cart">
                                    <i class="fas fa-cart-plus"></i> beli
                                </button>
                            </form>
                            <button type="button" class="btn-dinein" 
                                    data-bs-toggle="modal" data-bs-target="#dineInModal{{ $product->id }}">
                                <i class="fas fa-utensils"></i> Dine In
                            </button>
                        </div>
                        <!-- Tombol Detail -->
                        <a href="{{ route('product.detail', $product->id) }}" class="btn btn-detail">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    @else
                        <button class="btn-add-cart" disabled style="background: #adb5bd; cursor: not-allowed;">
                            <i class="fas fa-times-circle"></i> Stok Habis
                        </button>
                        <!-- Tombol Detail tetap muncul meskipun stok habis -->
                        <a href="{{ route('product.detail', $product->id) }}" class="btn btn-detail">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Dine In Modal -->
        <div class="modal fade" id="dineInModal{{ $product->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background: linear-gradient(135deg, #db521c 0%, #4A2C1A 100%); color: white;">
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
                                        <label for="quantity_{{ $product->id }}" class="form-label">Jumlah *</label>
                                        <input type="number" name="quantity" id="quantity_{{ $product->id }}" class="form-control" value="1" min="1" max="{{ $product->stock }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="table_number_{{ $product->id }}" class="form-label">Nomor Meja *</label>
                                        <input type="number" name="table_number" id="table_number_{{ $product->id }}" class="form-control" placeholder="Contoh: 5" min="1" max="50" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="customer_name_{{ $product->id }}" class="form-label">Nama Customer (Opsional)</label>
                                <input type="text" name="customer_name" id="customer_name_{{ $product->id }}" class="form-control" placeholder="Masukkan nama Anda">
                            </div>
                            <div class="mb-3">
                                <label for="notes_{{ $product->id }}" class="form-label">Catatan (Opsional)</label>
                                <textarea name="notes" id="notes_{{ $product->id }}" class="form-control" rows="2" placeholder="Contoh: Tidak pakai gula, extra panas, dll"></textarea>
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
        @empty
        <div class="col-12">
            <div class="alert alert-warning text-center">
                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                <h5>Belum ada produk</h5>
                <p>Silakan cek kembali nanti.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<script>
    function filterProducts() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const category = document.getElementById('categoryFilter').value;
        const sortBy = document.getElementById('sortFilter').value;
        
        let products = Array.from(document.querySelectorAll('.product-item'));
        
        // Filter by search
        products = products.filter(product => {
            const name = product.getAttribute('data-name');
            return name.includes(searchTerm);
        });
        
        // Filter by category
        if (category !== 'all') {
            products = products.filter(product => {
                const productCategory = product.getAttribute('data-category');
                return productCategory === category;
            });
        }
        
        // Sort products
        if (sortBy === 'price_asc') {
            products.sort((a, b) => {
                const priceA = parseInt(a.getAttribute('data-price'));
                const priceB = parseInt(b.getAttribute('data-price'));
                return priceA - priceB;
            });
        } else if (sortBy === 'price_desc') {
            products.sort((a, b) => {
                const priceA = parseInt(a.getAttribute('data-price'));
                const priceB = parseInt(b.getAttribute('data-price'));
                return priceB - priceA;
            });
        } else if (sortBy === 'name_asc') {
            products.sort((a, b) => {
                const nameA = a.getAttribute('data-name');
                const nameB = b.getAttribute('data-name');
                return nameA.localeCompare(nameB);
            });
        }
        
        // Update display
        const grid = document.getElementById('productsGrid');
        grid.innerHTML = '';
        
        if (products.length === 0) {
            grid.innerHTML = '<div class="col-12"><div class="alert alert-info text-center">Tidak ada produk yang ditemukan</div></div>';
        } else {
            products.forEach(product => {
                grid.appendChild(product);
            });
        }
    }
    
    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('categoryFilter').value = 'all';
        document.getElementById('sortFilter').value = 'default';
        filterProducts();
    }
    
    // Auto close modal after submit
    document.querySelectorAll('form[action*="dinein.store"]').forEach(form => {
        form.addEventListener('submit', function() {
            setTimeout(() => {
                let modal = bootstrap.Modal.getInstance(document.querySelector('.modal.show'));
                if (modal) modal.hide();
            }, 100);
        });
    });
</script>
@endsection