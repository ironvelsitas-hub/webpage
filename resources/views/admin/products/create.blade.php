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
        display: none;
    }
    .tip-card {
        background: #FFF8F0;
        border: 1px solid #FFE0B5;
        border-radius: 15px;
    }
    
    /* Styles untuk Multiple Image Upload */
    .image-upload-container {
        border: 2px dashed #ddd;
        border-radius: 12px;
        padding: 20px;
        background: #fafafa;
        transition: all 0.3s ease;
    }
    .image-upload-container:hover {
        border-color: #FF6B35;
        background: #fffaf5;
    }
    .image-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }
    .image-preview-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        background: white;
    }
    .image-preview-item img {
        width: 100%;
        height: 120px;
        object-fit: cover;
    }
    .image-preview-item .remove-image {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .image-preview-item .remove-image:hover {
        background: #dc3545;
        transform: scale(1.1);
    }
    .image-preview-item .image-order {
        position: absolute;
        bottom: 5px;
        left: 5px;
        background: rgba(0,0,0,0.6);
        color: white;
        border-radius: 15px;
        padding: 2px 8px;
        font-size: 10px;
    }
    .upload-btn-custom {
        background: #FF6B35;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 30px;
        transition: all 0.2s;
    }
    .upload-btn-custom:hover {
        background: #e55a2b;
        transform: translateY(-2px);
    }
    .badge-primary-custom {
        background: #FF6B35;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus-circle" style="color: #FF6B35;"></i> Tambah Produk Baru
    </h1>
    <a href="{{ route('admin.products') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card form-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Produk</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Kopi Arabica Gayo" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="category" class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('category') is-invalid @enderror" 
                                id="category" name="category" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Espresso" {{ old('category') == 'Espresso' ? 'selected' : '' }}>☕ Espresso</option>
                            <option value="Single Origin" {{ old('category') == 'Single Origin' ? 'selected' : '' }}>🌱 Single Origin</option>
                            <option value="Blend" {{ old('category') == 'Blend' ? 'selected' : '' }}>🔄 Blend</option>
                            <option value="Instant" {{ old('category') == 'Instant' ? 'selected' : '' }}>⚡ Instant</option>
                            <option value="Premium" {{ old('category') == 'Premium' ? 'selected' : '' }}>💎 Premium</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="5" 
                                  placeholder="Masukkan deskripsi lengkap produk..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimal 20 karakter untuk deskripsi yang baik.</small>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label fw-bold">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}" placeholder="25000" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stock" class="form-label fw-bold">Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                       id="stock" name="stock" value="{{ old('stock') }}" placeholder="100" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- MULTIPLE IMAGE UPLOAD SECTION -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Gambar Produk <span class="text-danger">*</span></label>
                        <div class="image-upload-container text-center">
                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                            <p class="text-muted mb-2">Upload hingga 3 gambar produk</p>
                            <input type="file" id="productImages" name="images[]" accept="image/*" multiple style="display: none;">
                            <button type="button" class="upload-btn-custom" onclick="document.getElementById('productImages').click()">
                                <i class="fas fa-folder-open"></i> Pilih Gambar
                            </button>
                            <small class="text-muted d-block mt-2">
                                <span id="selectedCount">0</span> dari 3 gambar dipilih
                            </small>
                            <small class="text-muted">Maksimal 2MB per gambar. Format: JPG, JPEG, PNG, WEBP</small>
                        </div>
                        
                        <!-- Preview Container -->
                        <div id="imagePreviewGrid" class="image-preview-grid"></div>
                        <div id="imageError" class="text-danger small mt-2" style="display: none;"></div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
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
                        <button type="submit" class="btn btn-save" id="submitBtn">
                            <i class="fas fa-save"></i> Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card tip-card">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Tips Produk</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success"></i> 
                        <strong>Nama Produk:</strong> Gunakan nama yang jelas dan mudah diingat
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success"></i> 
                        <strong>Deskripsi:</strong> Jelaskan rasa, aroma, dan keunggulan produk
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success"></i> 
                        <strong>Gambar:</strong> Upload 2-3 foto dari sudut berbeda untuk tampilan terbaik
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success"></i> 
                        <strong>Harga:</strong> Sesuaikan dengan kualitas produk
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success"></i> 
                        <strong>Stok:</strong> Update stok secara berkala
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-question-circle"></i> Informasi</h5>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-0">
                    <i class="fas fa-info-circle"></i> 
                    Produk yang sudah disimpan akan langsung tampil di halaman utama website.
                    Pastikan semua data sudah benar sebelum menyimpan.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    let selectedFiles = [];
    const MAX_FILES = 3;
    const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB
    const ALLOWED_TYPES = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    
    // Trigger file input
    document.getElementById('productImages').addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        const errorDiv = document.getElementById('imageError');
        errorDiv.style.display = 'none';
        errorDiv.innerHTML = '';
        
        // Validate total files
        if (selectedFiles.length + files.length > MAX_FILES) {
            errorDiv.style.display = 'block';
            errorDiv.innerHTML = `Maksimal upload ${MAX_FILES} gambar. Anda sudah memilih ${selectedFiles.length} gambar.`;
            return;
        }
        
        // Validate each file
        let validFiles = [];
        for (let file of files) {
            // Check type
            if (!ALLOWED_TYPES.includes(file.type)) {
                errorDiv.style.display = 'block';
                errorDiv.innerHTML = `File "${file.name}" tidak didukung. Format yang diperbolehkan: JPG, JPEG, PNG, WEBP.`;
                continue;
            }
            // Check size
            if (file.size > MAX_FILE_SIZE) {
                errorDiv.style.display = 'block';
                errorDiv.innerHTML = `File "${file.name}" melebihi batas 2MB.`;
                continue;
            }
            validFiles.push(file);
        }
        
        if (validFiles.length > 0) {
            selectedFiles = [...selectedFiles, ...validFiles];
            updatePreview();
            updateSelectedCount();
        }
        
        // Reset input value
        e.target.value = '';
    });
    
    function updatePreview() {
        const container = document.getElementById('imagePreviewGrid');
        container.innerHTML = '';
        
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'image-preview-item';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview ${index + 1}">
                    <div class="image-order">Gambar ${index + 1}</div>
                    <div class="remove-image" onclick="removeImage(${index})">
                        <i class="fas fa-times"></i>
                    </div>
                `;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
    
    function removeImage(index) {
        selectedFiles.splice(index, 1);
        updatePreview();
        updateSelectedCount();
        
        // Reset file input
        const fileInput = document.getElementById('productImages');
        fileInput.value = '';
        
        // If no files left, hide error
        if (selectedFiles.length === 0) {
            document.getElementById('imageError').style.display = 'none';
        }
    }
    
    function updateSelectedCount() {
        const countSpan = document.getElementById('selectedCount');
        countSpan.textContent = selectedFiles.length;
        
        // Change style if max reached
        if (selectedFiles.length >= MAX_FILES) {
            countSpan.style.color = '#dc3545';
            countSpan.style.fontWeight = 'bold';
        } else {
            countSpan.style.color = '';
            countSpan.style.fontWeight = '';
        }
    }
    
    // Form validation before submit
    document.getElementById('productForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        
        // Validate images
        if (selectedFiles.length === 0) {
            e.preventDefault();
            const errorDiv = document.getElementById('imageError');
            errorDiv.style.display = 'block';
            errorDiv.innerHTML = 'Silakan upload minimal 1 gambar produk.';
            return;
        }
        
        // Clear existing image inputs
        const existingInputs = document.querySelectorAll('input[name="images[]"]');
        existingInputs.forEach(input => input.remove());
        
        // Add new image inputs
        for (let i = 0; i < selectedFiles.length; i++) {
            const input = document.createElement('input');
            input.type = 'file';
            input.name = 'images[]';
            input.style.display = 'none';
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(selectedFiles[i]);
            input.files = dataTransfer.files;
            document.getElementById('productForm').appendChild(input);
        }
        
        if (submitBtn.disabled) {
            e.preventDefault();
            return;
        }
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    });
    
    // Drag and drop support
    const dropZone = document.querySelector('.image-upload-container');
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.style.borderColor = '#FF6B35';
        dropZone.style.background = '#fffaf5';
    });
    
    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropZone.style.borderColor = '#ddd';
        dropZone.style.background = '#fafafa';
    });
    
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.style.borderColor = '#ddd';
        dropZone.style.background = '#fafafa';
        
        const files = Array.from(e.dataTransfer.files);
        const fileInput = document.getElementById('productImages');
        
        // Create a DataTransfer object to set files
        const dataTransfer = new DataTransfer();
        files.forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;
        
        // Trigger change event
        const event = new Event('change', { bubbles: true });
        fileInput.dispatchEvent(event);
    });
</script>
@endsection