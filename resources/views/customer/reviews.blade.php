@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Menu Customer</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('customer.dashboard') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="{{ route('customer.orders') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-shopping-cart"></i> Pesanan Saya
                    </a>
                    <a href="{{ route('customer.reviews') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-star"></i> Review Saya
                    </a>
                    <a href="{{ route('customer.profile') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user-edit"></i> Profil Saya
                    </a>
                    <a href="{{ route('customer.chat.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-comments"></i> Chat Admin
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="list-group-item list-group-item-action text-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-star"></i> Review Saya</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    @if($reviews->count() > 0)
                        @foreach($reviews as $review)
                        <div class="border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    @if($review->product->image && file_exists(public_path($review->product->image)))
                                        <img src="{{ asset($review->product->image) }}" class="img-fluid rounded" style="height: 100px; object-fit: cover; width: 100%;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 100px;">
                                            <i class="fas fa-mug-hot fa-2x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-9">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $review->product->name }}</h6>
                                            <div class="mb-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-muted"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <p class="text-muted small">{{ $review->comment }}</p>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-{{ $review->status == 'approved' ? 'success' : ($review->status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($review->status) }}
                                            </span>
                                            <br>
                                            <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                    
                                    @if($review->status == 'pending')
                                    <div class="mt-2">
                                        <a href="{{ route('review.edit', $review->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('review.destroy', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus review ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <p>Belum ada review yang Anda tulis.</p>
                            <a href="{{ route('shop.products') }}" class="btn btn-primary-custom">
                                <i class="fas fa-shopping-cart"></i> Belanja Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection