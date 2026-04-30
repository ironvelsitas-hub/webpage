@extends('layouts.app')

@section('content')
<style>
    .rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 5px;
    }
    .rating-input input {
        display: none;
    }
    .rating-input label {
        font-size: 30px;
        color: #ddd;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .rating-input label:hover,
    .rating-input label:hover ~ label,
    .rating-input input:checked ~ label {
        color: #FFD700;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header" style="background: #2C1810; color: white;">
                    <h4 class="mb-0"><i class="fas fa-star"></i> Tulis Review</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            @if($product->image && file_exists(public_path($product->image)))
                                <img src="{{ asset($product->image) }}" class="img-fluid rounded" alt="{{ $product->name }}">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                                    <i class="fas fa-mug-hot fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h5>{{ $product->name }}</h5>
                            <p class="text-muted">{{ $product->description }}</p>
                            <p class="text-primary fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    
                    <form action="{{ route('review.store', $product->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold">Rating *</label>
                            <div class="rating-input">
                                <input type="radio" name="rating" id="star5" value="5" required>
                                <label for="star5">★</label>
                                <input type="radio" name="rating" id="star4" value="4">
                                <label for="star4">★</label>
                                <input type="radio" name="rating" id="star3" value="3">
                                <label for="star3">★</label>
                                <input type="radio" name="rating" id="star2" value="2">
                                <label for="star2">★</label>
                                <input type="radio" name="rating" id="star1" value="1">
                                <label for="star1">★</label>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="comment" class="form-label fw-bold">Ulasan Anda *</label>
                            <textarea name="comment" id="comment" class="form-control" rows="5" placeholder="Ceritakan pengalaman Anda dengan produk ini..." required></textarea>
                            <small class="text-muted">Minimal 10 karakter.</small>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="fas fa-paper-plane"></i> Kirim Review
                            </button>
                            <a href="{{ route('product.detail', $product->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection