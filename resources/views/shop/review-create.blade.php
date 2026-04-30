@extends('layouts.app')

@section('content')
<style>
    .rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 10px;
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
    .image-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }
    .preview-item {
        position: relative;
        width: 100px;
        height: 100px;
    }
    .preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
    }
    .remove-image {
        position: absolute;
        top: -8px;
        right: -8px;
        background: red;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">
                        <i class="fas fa-star text-warning"></i> 
                        Tulis Ulasan untuk {{ $product->name }}
                    </h4>
                </div>
                <div class="card-body">
                    @if(!$hasPurchased)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Anda hanya bisa memberikan ulasan untuk produk yang sudah Anda beli dan pesanan sudah selesai.
                        </div>
                    @endif
                    
                    <form action="{{ route('review.store', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Rating -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Rating <span class="text-danger">*</span></label>
                            <div class="rating-input">
                                <input type="radio" name="rating" value="5" id="star5" required>
                                <label for="star5">★</label>
                                <input type="radio" name="rating" value="4" id="star4">
                                <label for="star4">★</label>
                                <input type="radio" name="rating" value="3" id="star3">
                                <label for="star3">★</label>
                                <input type="radio" name="rating" value="2" id="star2">
                                <label for="star2">★</label>
                                <input type="radio" name="rating" value="1" id="star1">
                                <label for="star1">★</label>
                            </div>
                        </div>
                        
                        <!-- Comment -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Ulasan <span class="text-danger">*</span></label>
                            <textarea name="comment" class="form-control" rows="5" 
                                      placeholder="Ceritakan pengalaman Anda menggunakan produk ini..." 
                                      required></textarea>
                            <small class="text-muted">Minimal 10 karakter, maksimal 1000 karakter</small>
                        </div>
                        
                        <!-- Images -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Foto (Opsional)</label>
                            <input type="file" name="images[]" class="form-control" accept="image/*" multiple id="imageInput">
                            <small class="text-muted">Upload maksimal 5 foto (format JPG, PNG, WEBP, maks 2MB per foto)</small>
                            <div class="image-preview" id="imagePreview"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('product.detail', $product) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary" {{ !$hasPurchased ? 'disabled' : '' }}>
                                <i class="fas fa-paper-plane"></i> Kirim Ulasan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        const files = Array.from(e.target.files);
        
        files.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'preview-item';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <div class="remove-image" onclick="removeImage(${index})">×</div>
                `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    });
    
    function removeImage(index) {
        const dt = new DataTransfer();
        const input = document.getElementById('imageInput');
        const files = Array.from(input.files);
        files.splice(index, 1);
        files.forEach(file => dt.items.add(file));
        input.files = dt.files;
        document.getElementById('imageInput').dispatchEvent(new Event('change'));
    }
</script>
@endsection