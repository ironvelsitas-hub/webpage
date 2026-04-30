@extends('layouts.admin')

@section('content')
<style>
    .form-card {
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        border: none;
    }
    .form-card .card-header {
        background: linear-gradient(135deg, #2C1810 0%, #4A2C1A 100%);
        color: white;
        border-radius: 15px 15px 0 0;
        padding: 15px 20px;
    }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #FF6B35;
        box-shadow: 0 0 0 0.2rem rgba(255,107,53,0.25);
    }
    .btn-save {
        background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 100%);
        border: none;
        padding: 10px 25px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255,107,53,0.3);
    }
    .preview-image {
        max-width: 100%;
        max-height: 200px;
        border-radius: 10px;
        margin-top: 10px;
    }
    .current-image {
        position: relative;
        display: inline-block;
    }
    .current-image img {
        border: 2px solid #FF6B35;
        padding: 5px;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit" style="color: #FF6B35;"></i> Edit Produk
    </h1>
    <a href="{{ route('admin.products') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card form-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit"></i> Form Edit Produk</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="category" class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('category') is-invalid @enderror" 
                                id="category" name="category" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Espresso" {{ old('category', $product->category) == 'Espresso' ? 'selected' : '' }}>☕ Espresso</option>
                            <option value="Single Origin" {{ old('category', $product->category) == 'Single Origin' ? 'selected' : '' }}>🌱 Single Origin</option>
                            <option value="Blend" {{ old('category', $product->category) == 'Blend' ? 'selected' : '' }}>🔄 Blend</option>
                            <option value="Instant" {{ old('category', $product->category) == 'Instant' ? 'selected' : '' }}>⚡ Instant</option>
                            <option value="Premium" {{ old('category', $product->category) == 'Premium' ? 'selected' : '' }}>💎 Premium</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="5" required>{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label fw-bold">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stock" class="form-label fw-bold">Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                       id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Gambar Saat Ini</label>
                        <div class="current-image">
                            @if($product->image && file_exists(public_path($product->image)))
                                <img src="{{ asset($product->image) }}" width="150" height="150" style="object-fit: cover; border-radius: 10px;">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-secondary rounded" style="width: 150px; height: 150px;">
                                    <i class="fas fa-mug-hot fa-3x text-white"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label fw-bold">Ganti Gambar (Opsional)</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*" onchange="previewImage(this)">
                        <small class="text-muted">Maksimal 2MB. Format: JPG, JPEG, PNG, WEBP. Kosongkan jika tidak ingin mengganti gambar.</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="imagePreview" class="mt-2"></div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <i class="fas fa-eye"></i> Aktif (produk akan ditampilkan ke customer)
                            </label>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.products') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-save"></i> Update Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi</h5>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-0">
                    <i class="fas fa-info-circle"></i> 
                    Produk yang aktif akan langsung tampil di halaman utama website customer.
                </p>
                <hr>
                <p class="small text-muted mb-0">
                    <i class="fas fa-box"></i> 
                    <strong>Stok saat ini:</strong> {{ $product->stock }}
                </p>
                <hr>
                <p class="small text-muted mb-0">
                    <i class="fas fa-clock"></i> 
                    <strong>Dibuat:</strong> {{ $product->created_at ? $product->created_at->format('d/m/Y H:i') : '-' }}
                </p>
                <p class="small text-muted mb-0">
                    <i class="fas fa-edit"></i> 
                    <strong>Terakhir update:</strong> {{ $product->updated_at ? $product->updated_at->format('d/m/Y H:i') : '-' }}
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '200px';
                img.style.borderRadius = '10px';
                img.style.border = '1px solid #ddd';
                img.style.padding = '5px';
                preview.appendChild(img);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection