@extends('layouts.owner')

@section('content')
<style>
    .dashboard-title {
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .stats-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        height: 100%;
    }
    
    .stats-value {
        font-size: 28px;
        font-weight: 800;
    }
    
    .chart-container {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }
    
    .btn-add {
        background: linear-gradient(135deg, #C49A6C, #A67C52);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 30px;
    }
    
    .status-stock {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
    }
    
    .status-danger { background: #dc3545; color: white; }
    .status-warning { background: #ffc107; color: #856404; }
    .status-success { background: #28a745; color: white; }
    
    .btn-edit {
        background: #17a2b8;
        color: white;
        border: none;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 12px;
        margin: 2px;
    }
    
    .btn-edit:hover {
        background: #138496;
        color: white;
    }
    
    .btn-delete {
        background: #dc3545;
        color: white;
        border: none;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 12px;
        margin: 2px;
    }
    
    .btn-delete:hover {
        background: #c82333;
        color: white;
    }
    
    .modal-content {
        border-radius: 20px;
    }
    
    .modal-header {
        background: linear-gradient(135deg, #2C1810, #4A2C1A);
        color: white;
        border-radius: 20px 20px 0 0;
    }
    
    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }
</style>

<div class="dashboard-title">
    <h3><i class="fas fa-boxes"></i> Manajemen Bahan Baku</h3>
    <p>Kelola stok bahan baku kopi (biji kopi, susu, gula, dll)</p>
</div>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-value">{{ $totalMaterials }}</div>
            <div class="stats-label">Total Bahan Baku</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-value text-warning">{{ $lowStockMaterials }}</div>
            <div class="stats-label">Stok Menipis</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-value text-danger">{{ $outOfStock }}</div>
            <div class="stats-label">Stok Habis</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-value text-primary">Rp {{ number_format($totalStockValue, 0, ',', '.') }}</div>
            <div class="stats-label">Nilai Stok</div>
        </div>
    </div>
</div>

<div class="chart-container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Bahan Baku</h5>
        <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addMaterialModal">
            <i class="fas fa-plus"></i> Tambah Bahan Baku
        </button>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                    <th>Min. Stok</th>
                    <th>Status</th>
                    <th>Supplier</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $material)
                @php $status = $material->stock_status; @endphp
                <tr>
                    <td><strong>{{ $material->name }}</strong></td>
                    <td>{{ $material->category ?? '-' }}</td>
                    <td>{{ number_format($material->stock) }} {{ $material->unit }}</td>
                    <td>Rp {{ number_format($material->unit_price, 0, ',', '.') }}/{{ $material->unit }}</td>
                    <td>{{ $material->min_stock }} {{ $material->unit }}</td>
                    <td>
                        <span class="status-stock status-{{ $status['class'] }}">
                            {{ $status['text'] }}
                        </span>
                    </td>
                    <td>{{ $material->supplier ?? '-' }}</td>
                    <td>
                        <button class="btn-edit" onclick="editMaterial({{ $material->id }})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-delete" onclick="deleteMaterial({{ $material->id }})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <i class="fas fa-boxes fa-3x text-muted mb-2"></i>
                        <p>Belum ada bahan baku</p>
                        <button class="btn btn-add btn-sm" data-bs-toggle="modal" data-bs-target="#addMaterialModal">
                            <i class="fas fa-plus"></i> Tambah Bahan Baku Pertama
                        </button>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Bahan Baku -->
<div class="modal fade" id="addMaterialModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus"></i> Tambah Bahan Baku</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('owner.raw-materials.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Bahan Baku *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Kategori</label>
                        <select name="category" class="form-select">
                            <option value="">Pilih Kategori</option>
                            <option value="Kopi">Kopi</option>
                            <option value="Susu">Susu</option>
                            <option value="Gula">Gula</option>
                            <option value="Syrup">Syrup</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Satuan *</label>
                            <select name="unit" class="form-select" required>
                                <option value="kg">Kilogram (kg)</option>
                                <option value="gram">Gram (g)</option>
                                <option value="liter">Liter (L)</option>
                                <option value="ml">Mililiter (ml)</option>
                                <option value="pcs">Pcs</option>
                                <option value="pack">Pack</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Harga per Satuan *</label>
                            <input type="number" name="unit_price" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Minimal Stok</label>
                            <input type="number" name="min_stock" class="form-control" value="10">
                        </div>
                        <div class="col-md-6">
                            <label>Supplier</label>
                            <input type="text" name="supplier" class="form-control">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Bahan Baku -->
<div class="modal fade" id="editMaterialModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Bahan Baku</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editMaterialForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label>Nama Bahan Baku *</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Kategori</label>
                        <select name="category" id="edit_category" class="form-select">
                            <option value="">Pilih Kategori</option>
                            <option value="Kopi">Kopi</option>
                            <option value="Susu">Susu</option>
                            <option value="Gula">Gula</option>
                            <option value="Syrup">Syrup</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Satuan *</label>
                            <select name="unit" id="edit_unit" class="form-select" required>
                                <option value="kg">Kilogram (kg)</option>
                                <option value="gram">Gram (g)</option>
                                <option value="liter">Liter (L)</option>
                                <option value="ml">Mililiter (ml)</option>
                                <option value="pcs">Pcs</option>
                                <option value="pack">Pack</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Harga per Satuan *</label>
                            <input type="number" name="unit_price" id="edit_unit_price" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Stok Saat Ini</label>
                            <input type="number" name="stock" id="edit_stock" class="form-control" value="0">
                        </div>
                        <div class="col-md-6">
                            <label>Minimal Stok</label>
                            <input type="number" name="min_stock" id="edit_min_stock" class="form-control" value="10">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Supplier</label>
                            <input type="text" name="supplier" id="edit_supplier" class="form-control">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label>Deskripsi</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Bahan Baku</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Edit Material Function
    function editMaterial(id) {
        // Fetch data dari server
        fetch('/owner/raw-materials/' + id + '/edit')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const material = data.material;
                    
                    // Isi form edit
                    document.getElementById('edit_id').value = material.id;
                    document.getElementById('edit_name').value = material.name;
                    document.getElementById('edit_category').value = material.category || '';
                    document.getElementById('edit_unit').value = material.unit;
                    document.getElementById('edit_unit_price').value = material.unit_price;
                    document.getElementById('edit_stock').value = material.stock;
                    document.getElementById('edit_min_stock').value = material.min_stock;
                    document.getElementById('edit_supplier').value = material.supplier || '';
                    document.getElementById('edit_description').value = material.description || '';
                    
                    // Set form action
                    document.getElementById('editMaterialForm').action = '/owner/raw-materials/' + id;
                    
                    // Show modal
                    var modal = new bootstrap.Modal(document.getElementById('editMaterialModal'));
                    modal.show();
                } else {
                    alert('Gagal mengambil data: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data');
            });
    }
    
    // Delete Material Function
    function deleteMaterial(id) {
        if (confirm('Apakah Anda yakin ingin menghapus bahan baku ini?')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/owner/raw-materials/' + id;
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection