@extends('layouts.app')

@section('content')
<style>
    .branch-detail-hero {
        background: linear-gradient(135deg, #1a1a2e 0%, #2C1810 100%);
        padding: 40px 0;
        color: white;
    }
    
    .detail-map {
        height: 400px;
        border-radius: 20px;
        overflow: hidden;
    }
    
    .info-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }
</style>

<div class="branch-detail-hero">
    <div class="container">
        <h1 class="display-5 fw-bold">{{ $branch->name }}</h1>
        <p>{{ $branch->description }}</p>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-7">
            <div class="detail-map mb-4">
                <iframe src="https://www.google.com/maps/embed/v1/place?key=YOUR_API_KEY&q={{ urlencode($branch->address) }}" 
                        allowfullscreen style="width:100%;height:100%;border:0">
                </iframe>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="info-card">
                <h4><i class="fas fa-info-circle"></i> Informasi Cabang</h4>
                <hr>
                <p><i class="fas fa-map-marker-alt"></i> <strong>Alamat:</strong><br>{{ $branch->address }}</p>
                <p><i class="fas fa-phone"></i> <strong>Telepon:</strong><br>{{ $branch->phone }}</p>
                <p><i class="fas fa-envelope"></i> <strong>Email:</strong><br>{{ $branch->email }}</p>
                <p><i class="fas fa-clock"></i> <strong>Jam Operasional:</strong><br>{{ $branch->operating_hours }}</p>
                <div class="d-flex gap-2 mt-3">
                    <a href="{{ $branch->google_maps_url }}" target="_blank" class="btn btn-primary-custom">
                        <i class="fas fa-directions"></i> Buka Maps
                    </a>
                    <a href="tel:{{ $branch->phone }}" class="btn btn-success">
                        <i class="fas fa-phone-alt"></i> Telepon
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    @if($otherBranches->count() > 0)
    <div class="mt-5">
        <h3 class="mb-4 text-center">Cabang Lainnya</h3>
        <div class="row g-4">
            @foreach($otherBranches as $other)
            <div class="col-md-4">
                <div class="card branch-card h-100">
                    <div class="card-body">
                        <h5>{{ $other->name }}</h5>
                        <p class="text-muted small">{{ Str::limit($other->address, 60) }}</p>
                        <a href="{{ route('locations.show', $other->slug) }}" class="btn btn-sm btn-outline-primary">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection