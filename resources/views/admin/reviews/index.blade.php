@extends('layouts.admin')

@section('content')
<style>
    .stats-card {
        border-radius: 12px;
        transition: all 0.3s ease;
        margin-bottom: 20px;
        border: none;
    }
    .review-card {
        border-radius: 12px;
        transition: all 0.3s ease;
        margin-bottom: 20px;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .review-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .rating-stars {
        color: #FFD700;
        font-size: 14px;
    }
    .filter-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        border: 1px solid #e0e0e0;
    }
    .review-status-pending {
        border-left: 4px solid #FFC107;
    }
    .review-status-approved {
        border-left: 4px solid #28A745;
    }
    .review-status-rejected {
        border-left: 4px solid #DC3545;
    }
</style>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-star" style="color: #FF6B35;"></i> Manajemen Review Customer
    </h1>
    <div>
        <button class="btn btn-sm btn-success" onclick="exportToExcel()">
            <i class="fas fa-file-excel"></i> Export Excel
        </button>
        <button class="btn btn-sm btn-primary" onclick="window.print()">
            <i class="fas fa-print"></i> Print
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card bg-primary text-white">
            <div class="card-body">
                <h6 class="card-title">Total Review</h6>
                <h3 class="mb-0">{{ $reviews->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card bg-warning text-dark">
            <div class="card-body">
                <h6 class="card-title">Menunggu Persetujuan</h6>
                <h3 class="mb-0">{{ $pendingCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card bg-success text-white">
            <div class="card-body">
                <h6 class="card-title">Disetujui</h6>
                <h3 class="mb-0">{{ $approvedCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card bg-danger text-white">
            <div class="card-body">
                <h6 class="card-title">Ditolak</h6>
                <h3 class="mb-0">{{ $rejectedCount }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-section">
    <div class="row align-items-end g-2">
        <div class="col-md-3">
            <label class="form-label small fw-bold text-muted">FILTER STATUS</label>
            <select id="statusFilter" class="form-select form-select-sm" onchange="filterReviews()">
                <option value="all">Semua Status</option>
                <option value="pending">🟡 Pending</option>
                <option value="approved">✅ Approved</option>
                <option value="rejected">❌ Rejected</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-muted">CARI PRODUK</label>
            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari produk atau customer..." onkeyup="filterReviews()">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-muted">URUTKAN</label>
            <select id="sortFilter" class="form-select form-select-sm" onchange="filterReviews()">
                <option value="newest">Terbaru</option>
                <option value="oldest">Terlama</option>
                <option value="highest_rating">Rating Tertinggi</option>
                <option value="lowest_rating">Rating Terendah</option>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-sm btn-secondary w-100" onclick="resetFilters()">
                <i class="fas fa-undo-alt"></i> Reset Filter
            </button>
        </div>
    </div>
</div>

<!-- Reviews Grid -->
<div class="row" id="reviewsGrid">
    @forelse($reviews as $review)
    <div class="col-md-6 col-lg-4 review-item" 
         data-status="{{ $review->status }}" 
         data-product="{{ strtolower($review->product->name) }}"
         data-customer="{{ strtolower($review->user->name) }}"
         data-rating="{{ $review->rating }}"
         data-date="{{ $review->created_at->timestamp }}">
        <div class="review-card review-status-{{ $review->status }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <h6 class="mt-2 mb-1">{{ $review->product->name }}</h6>
                        <small class="text-muted">
                            <i class="fas fa-user"></i> {{ $review->user->name }}
                        </small>
                    </div>
                    <span class="badge bg-{{ $review->status == 'approved' ? 'success' : ($review->status == 'pending' ? 'warning' : 'danger') }}">
                        {{ ucfirst($review->status) }}
                    </span>
                </div>
                
                <p class="text-muted small mb-2">{{ $review->comment }}</p>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-calendar"></i> {{ $review->created_at->format('d/m/Y H:i') }}
                        </small>
                        <br>
                        @if($review->is_verified_purchase)
                            <span class="badge bg-success mt-1">Verified Purchase</span>
                        @endif
                    </div>
                    <div>
                        @if($review->status == 'pending')
                            <div class="btn-group-vertical btn-group-sm">
                                <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success mb-1 w-100">
                                        <i class="fas fa-check"></i> Setujui
                                    </button>
                                </form>
                                <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" onsubmit="return confirm('Tolak review ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger w-100">
                                        <i class="fas fa-times"></i> Tolak
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="text-center">
                                <small class="text-muted">Status: {{ ucfirst($review->status) }}</small>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Tombol Lihat Detail Produk -->
                <div class="mt-2 pt-2 border-top">
                    <a href="{{ route('product.detail', $review->product_id) }}" class="btn btn-sm btn-outline-info w-100" target="_blank">
                        <i class="fas fa-eye"></i> Lihat Produk
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center">
            <i class="fas fa-inbox fa-2x mb-2"></i>
            <h5>Belum ada review dari customer</h5>
            <p>Review akan muncul setelah customer memberikan ulasan.</p>
        </div>
    </div>
    @endforelse
</div>

<script>
    function filterReviews() {
        const status = document.getElementById('statusFilter').value;
        const search = document.getElementById('searchInput').value.toLowerCase();
        const sortBy = document.getElementById('sortFilter').value;
        
        let items = Array.from(document.querySelectorAll('.review-item'));
        
        // Filter by status
        if (status !== 'all') {
            items = items.filter(item => item.getAttribute('data-status') === status);
        }
        
        // Filter by search
        if (search) {
            items = items.filter(item => {
                const product = item.getAttribute('data-product');
                const customer = item.getAttribute('data-customer');
                return product.includes(search) || customer.includes(search);
            });
        }
        
        // Sort items
        if (sortBy === 'newest') {
            items.sort((a, b) => parseInt(b.getAttribute('data-date')) - parseInt(a.getAttribute('data-date')));
        } else if (sortBy === 'oldest') {
            items.sort((a, b) => parseInt(a.getAttribute('data-date')) - parseInt(b.getAttribute('data-date')));
        } else if (sortBy === 'highest_rating') {
            items.sort((a, b) => parseInt(b.getAttribute('data-rating')) - parseInt(a.getAttribute('data-rating')));
        } else if (sortBy === 'lowest_rating') {
            items.sort((a, b) => parseInt(a.getAttribute('data-rating')) - parseInt(b.getAttribute('data-rating')));
        }
        
        // Update display
        const grid = document.getElementById('reviewsGrid');
        grid.innerHTML = '';
        
        if (items.length === 0) {
            grid.innerHTML = '<div class="col-12"><div class="alert alert-info text-center">Tidak ada review yang ditemukan</div></div>';
        } else {
            items.forEach(item => grid.appendChild(item));
        }
    }
    
    function resetFilters() {
        document.getElementById('statusFilter').value = 'all';
        document.getElementById('searchInput').value = '';
        document.getElementById('sortFilter').value = 'newest';
        filterReviews();
    }
    
    function exportToExcel() {
        const items = document.querySelectorAll('.review-item');
        let csv = [['No', 'Produk', 'Customer', 'Rating', 'Review', 'Status', 'Verified', 'Tanggal']];
        let no = 1;
        
        items.forEach(item => {
            if (item.style.display !== 'none') {
                const reviewCard = item.querySelector('.review-card');
                const ratingStars = reviewCard.querySelector('.rating-stars');
                const rating = ratingStars.querySelectorAll('.fa-star').length;
                const product = reviewCard.querySelector('h6')?.innerText || '';
                const customer = reviewCard.querySelector('.text-muted i.fa-user')?.parentElement?.innerText || '';
                const comment = reviewCard.querySelector('p')?.innerText || '';
                const statusBadge = reviewCard.querySelector('.badge')?.innerText || '';
                const verified = reviewCard.querySelector('.badge-success') ? 'Yes' : 'No';
                const date = reviewCard.querySelector('.fa-calendar')?.parentElement?.innerText || '';
                
                csv.push([no++, product, customer, rating, comment, statusBadge, verified, date]);
            }
        });
        
        const blob = new Blob([csv.map(row => row.join(',')).join('\n')], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.download = 'reviews_' + new Date().toISOString().slice(0,19) + '.csv';
        a.click();
        URL.revokeObjectURL(url);
    }
</script>
@endsection