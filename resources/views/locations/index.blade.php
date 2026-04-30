@extends('layouts.app')

@section('content')
<style>
    /* Location Page Styles */
    .location-hero {
        background: linear-gradient(135deg, #1a1a2e 0%, #2C1810 100%);
        padding: 60px 0;
        color: white;
        text-align: center;
        margin-bottom: 40px;
    }
    
    .branch-card {
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        height: 100%;
        border: none;
    }
    
    .branch-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 35px rgba(0,0,0,0.15);
    }
    
    .branch-image {
        height: 200px;
        object-fit: cover;
        width: 100%;
    }
    
    .branch-status {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
        z-index: 1;
    }
    
    .status-open {
        background: #28a745;
        color: white;
    }
    
    .status-closed {
        background: #dc3545;
        color: white;
    }
    
    .branch-info-item {
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .branch-info-item i {
        width: 25px;
        color: #C49A6C;
    }
    
    .map-container {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        height: 400px;
    }
    
    .map-container iframe {
        width: 100%;
        height: 100%;
        border: 0;
    }
    
    .btn-direction {
        background: linear-gradient(135deg, #C49A6C, #A67C52);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 30px;
        transition: all 0.3s ease;
    }
    
    .btn-direction:hover {
        transform: translateX(5px);
        color: white;
    }
    
    .btn-call {
        background: #25D366;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 30px;
    }
    
    .btn-call:hover {
        background: #128C7E;
        color: white;
    }
    
    .hours-badge {
        background: #f8f9fa;
        padding: 8px 15px;
        border-radius: 10px;
        display: inline-block;
    }
    
    @media (max-width: 768px) {
        .location-hero {
            padding: 40px 0;
        }
        .map-container {
            height: 300px;
        }
    }
</style>

<div class="location-hero">
    <div class="container">
        <h1 class="display-4 fw-bold">
            <i class="fas fa-map-marker-alt"></i> Lokasi Kami
        </h1>
        <p class="lead">Temukan cabang Kopi Ancol terdekat dari lokasi Anda</p>
    </div>
</div>

<div class="container mb-5">
<!-- Map Cabang Utama - Versi Fix (Tanpa API Key) -->
<div class="row mb-5">
    <div class="col-12">
        <div class="map-container">
            @if($mainBranch->latitude && $mainBranch->longitude)
                <!-- Jika ada koordinat, gunakan embed dengan koordinat -->
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3950!2d{{ $mainBranch->longitude }}!3d{{ $mainBranch->latitude }}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x{{ substr(md5($mainBranch->latitude . $mainBranch->longitude), 0, 16) }}%3A0x0!2z{{ urlencode($mainBranch->latitude) }},{{ urlencode($mainBranch->longitude) }}!5e0!3m2!1sid!2sid!4v{{ time() }}"
                    width="100%" 
                    height="100%" 
                    style="border:0" 
                    allowfullscreen="" 
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            @else
                <!-- Fallback: Gunakan pencarian berdasarkan alamat -->
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3950!2d120.4629!3d-8.5352!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2db4c8c8c8c8c8c8%3A0x0!2zOMKwMzInMTIuNyJTIDExOTs0NiczOS4zIkU!5e0!3m2!1sid!2sid!4v{{ time() }}"
                    width="100%" 
                    height="100%" 
                    style="border:0" 
                    allowfullscreen="" 
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            @endif
        </div>
    </div>
</div>    
    <!-- Grid Cabang -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-center mb-4 fw-bold" style="color: #2C1810;">
                <i class="fas fa-store"></i> Semua Cabang Kopi Ancol
            </h2>
        </div>
    </div>
    
    <div class="row g-4">
        @foreach($branches as $branch)
        <div class="col-md-6 col-lg-4">
            <div class="card branch-card position-relative">
                <div class="position-relative">
                    @if($branch->image)
                        <img src="{{ asset($branch->image) }}" class="branch-image" alt="{{ $branch->name }}">
                    @else
                        <img src="https://placehold.co/600x400/e8d5b7/2C1810?text={{ urlencode($branch->name) }}" class="branch-image" alt="{{ $branch->name }}">
                    @endif
                    <span class="branch-status {{ $branch->is_open ? 'status-open' : 'status-closed' }}">
                        <i class="fas {{ $branch->is_open ? 'fa-check-circle' : 'fa-clock' }}"></i>
                        {{ $branch->is_open ? 'Buka' : 'Tutup' }}
                    </span>
                </div>
                <div class="card-body">
                    <h4 class="card-title fw-bold" style="color: #2C1810;">
                        <i class="fas fa-mug-hot"></i> {{ $branch->name }}
                    </h4>
                    
                    <div class="branch-info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <small class="text-muted">{{ Str::limit($branch->address, 60) }}</small>
                    </div>
                    
                    <div class="branch-info-item">
                        <i class="fas fa-phone"></i>
                        <a href="tel:{{ $branch->phone }}" class="text-decoration-none">{{ $branch->phone }}</a>
                    </div>
                    
                    <div class="branch-info-item">
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:{{ $branch->email }}" class="text-decoration-none">{{ $branch->email }}</a>
                    </div>
                    
                    <div class="branch-info-item">
                        <i class="fas fa-clock"></i>
                        <span class="hours-badge">
                            <i class="fas fa-hourglass-half"></i> {{ $branch->operating_hours }}
                        </span>
                    </div>
                    
                    @if($branch->description)
                        <div class="branch-info-item">
                            <i class="fas fa-info-circle"></i>
                            <small>{{ Str::limit($branch->description, 80) }}</small>
                        </div>
                    @endif
                    
                    <div class="mt-3">
                        <div class="d-flex gap-2">
                            <a href="{{ $branch->google_maps_url }}" target="_blank" class="btn btn-direction flex-grow-1">
                                <i class="fas fa-directions"></i> Petunjuk Arah
                            </a>
                            <a href="tel:{{ $branch->phone }}" class="btn btn-call">
                                <i class="fas fa-phone-alt"></i>
                            </a>
                        </div>
                        <a href="{{ route('locations.show', $branch->slug) }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="fas fa-info-circle"></i> Detail Cabang
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Feature: Deteksi Lokasi Terdekat -->
<div class="container mb-5">
    <div class="card bg-light border-0 rounded-4 p-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="mb-2">
                    <i class="fas fa-location-dot text-primary"></i> Cari Cabang Terdekat
                </h4>
                <p class="text-muted mb-0">Izinkan akses lokasi untuk menemukan cabang Kopi Ancol terdekat dari lokasi Anda</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <button onclick="findNearestBranch()" class="btn btn-primary-custom">
                    <i class="fas fa-crosshairs"></i> Deteksi Lokasi Saya
                </button>
            </div>
        </div>
    </div>
    <div id="nearestBranchResult" class="mt-3" style="display: none;"></div>
</div>

<script>
    // Fungsi untuk mencari cabang terdekat
    function findNearestBranch() {
        if (!navigator.geolocation) {
            showNearestError("Browser Anda tidak mendukung geolokasi");
            return;
        }
        
        const resultDiv = document.getElementById('nearestBranchResult');
        resultDiv.innerHTML = '<div class="alert alert-info"><i class="fas fa-spinner fa-spin"></i> Mendeteksi lokasi Anda...</div>';
        resultDiv.style.display = 'block';
        
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                
                // Hitung jarak ke setiap cabang
                const branches = @json($branches);
                let nearest = null;
                let minDistance = Infinity;
                
                branches.forEach(branch => {
                    if (branch.latitude && branch.longitude) {
                        const distance = calculateDistance(
                            userLat, userLng,
                            parseFloat(branch.latitude), parseFloat(branch.longitude)
                        );
                        if (distance < minDistance) {
                            minDistance = distance;
                            nearest = branch;
                        }
                    }
                });
                
                if (nearest) {
                    resultDiv.innerHTML = `
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> 
                            <strong>Cabang Terdekat:</strong> ${nearest.name}<br>
                            <i class="fas fa-map-marker-alt"></i> Jarak: ${minDistance.toFixed(2)} km<br>
                            <a href="${nearest.google_maps_url}" target="_blank" class="btn btn-sm btn-success mt-2">
                                <i class="fas fa-directions"></i> Buka Google Maps
                            </a>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = '<div class="alert alert-warning">Tidak dapat menentukan cabang terdekat</div>';
                }
            },
            function(error) {
                let errorMessage = "Gagal mendapatkan lokasi: ";
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage += "Izin lokasi ditolak. Silakan izinkan akses lokasi.";
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage += "Informasi lokasi tidak tersedia.";
                        break;
                    case error.TIMEOUT:
                        errorMessage += "Waktu permintaan lokasi habis.";
                        break;
                    default:
                        errorMessage += "Terjadi kesalahan.";
                }
                showNearestError(errorMessage);
            }
        );
    }
    
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Radius bumi dalam km
        const dLat = deg2rad(lat2 - lat1);
        const dLon = deg2rad(lon2 - lon1);
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                  Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }
    
    function deg2rad(deg) {
        return deg * (Math.PI/180);
    }
    
    function showNearestError(message) {
        const resultDiv = document.getElementById('nearestBranchResult');
        resultDiv.innerHTML = `<div class="alert alert-danger">${message}</div>`;
    }
</script>
@endsection