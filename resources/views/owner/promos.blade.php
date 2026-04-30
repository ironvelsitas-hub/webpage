@extends('layouts.owner')

@section('content')
<style>
    .stats-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        height: 100%;
        transition: all 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
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
    
    .btn-add:hover {
        background: #A67C52;
        color: white;
        transform: translateY(-2px);
    }
    
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
    
    .status-active {
        background: #28a745;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
    }
    
    .status-expired {
        background: #dc3545;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
    }
    
    .status-inactive {
        background: #6c757d;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
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
    
    .form-control:focus, .form-select:focus {
        border-color: #C49A6C;
        box-shadow: 0 0 0 0.2rem rgba(196, 154, 108, 0.25);
    }
</style>

<div class="dashboard-title">
    <h3><i class="fas fa-tags"></i> Manajemen Promo & Voucher</h3>
    <p>Buat dan kelola promo untuk meningkatkan penjualan</p>
</div>

<!-- Statistik -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-value text-primary">{{ $activePromos ?? 0 }}</div>
            <div class="stats-label">Promo Aktif</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-value">{{ $totalPromos ?? 0 }}</div>
            <div class="stats-label">Total Promo</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-value text-success">{{ $totalUsed ?? 0 }}</div>
            <div class="stats-label">Total Digunakan</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-value text-warning">Rp {{ number_format($totalDiscountGiven ?? 0, 0, ',', '.') }}</div>
            <div class="stats-label">Total Diskon</div>
        </div>
    </div>
</div>

<!-- Daftar Promo -->
<div class="chart-container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Promo</h5>
        <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addPromoModal">
            <i class="fas fa-plus"></i> Tambah Promo
        </button>
    </div>
    
    @if($promos->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Diskon</th>
                    <th>Min. Belanja</th>
                    <th>Berlaku</th>
                    <th>Terpakai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($promos as $promo)
                @php
                    $isActive = $promo->is_active && $promo->end_date >= \Carbon\Carbon::now();
                    $usageText = ($promo->used_count ?? 0) . ' / ' . ($promo->usage_limit ?? '∞');
                    
                    if ($isActive) {
                        $statusClass = 'status-active';
                        $statusText = 'Aktif';
                    } elseif (!$promo->is_active) {
                        $statusClass = 'status-inactive';
                        $statusText = 'Nonaktif';
                    } else {
                        $statusClass = 'status-expired';
                        $statusText = 'Kadaluarsa';
                    }
                @endphp
                <tr>
                    <td><strong>{{ $promo->code }}</strong></td>
                    <td>{{ $promo->name }}</td>
                    <td>
                        @if($promo->type == 'percentage')
                            {{ $promo->value }}%
                        @else
                            Rp {{ number_format($promo->value, 0, ',', '.') }}
                        @endif
                    </td>
                    <td>Rp {{ number_format($promo->min_purchase, 0, ',', '.') }}</td>
                    <td>
                        <small>{{ \Carbon\Carbon::parse($promo->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($promo->end_date)->format('d/m/Y') }}</small>
                    </td>
                    <td>
                        <strong>{{ $usageText }}</strong>
                    </td>
                    <td>
                        <span class="{{ $statusClass }}">{{ $statusText }}</span>
                    </td>
                    <td>
                        <button class="btn-edit" onclick="editPromo({{ $promo->id }})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-delete" onclick="deletePromo({{ $promo->id }})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-tags fa-4x text-muted mb-3"></i>
        <h5>Belum Ada Promo</h5>
        <p class="text-muted">Klik tombol "Tambah Promo" untuk membuat promo pertama Anda</p>
        <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addPromoModal">
            <i class="fas fa-plus"></i> Tambah Promo Sekarang
        </button>
    </div>
    @endif
</div>

<!-- Modal Tambah Promo -->
<div class="modal fade" id="addPromoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus"></i> Tambah Promo Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('owner.promos.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Kode Promo *</label>
                        <input type="text" name="code" class="form-control" placeholder="CONTOH10" required>
                        <small class="text-muted">Contoh: WELCOME10, NEWUSER20</small>
                    </div>
                    <div class="mb-3">
                        <label>Nama Promo *</label>
                        <input type="text" name="name" class="form-control" placeholder="Diskon Welcome 10%" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Tipe Diskon *</label>
                            <select name="type" class="form-select" required>
                                <option value="percentage">Persentase (%)</option>
                                <option value="fixed">Nominal Tetap (Rp)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Nilai Diskon *</label>
                            <input type="number" name="value" class="form-control" placeholder="10 atau 50000" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label>Min. Belanja *</label>
                            <input type="number" name="min_purchase" class="form-control" value="0" required>
                        </div>
                        <div class="col-md-6">
                            <label>Maks. Diskon (Opsional)</label>
                            <input type="number" name="max_discount" class="form-control" placeholder="Maksimal potongan">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label>Batas Penggunaan (Opsional)</label>
                            <input type="number" name="usage_limit" class="form-control" placeholder="Kosongkan jika tidak terbatas">
                        </div>
                        <div class="col-md-6">
                            <label>Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1">🟢 Aktif</option>
                                <option value="0">🔴 Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label>Tanggal Mulai *</label>
                            <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label>Tanggal Berakhir *</label>
                            <input type="date" name="end_date" class="form-control" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                        </div>
                    </div>
                    <div class="mt-2">
                        <label>Deskripsi (Opsional)</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Deskripsi promo"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Promo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Promo -->
<div class="modal fade" id="editPromoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Promo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editPromoForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label>Kode Promo *</label>
                        <input type="text" name="code" id="edit_code" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Nama Promo *</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Tipe Diskon *</label>
                            <select name="type" id="edit_type" class="form-select" required>
                                <option value="percentage">Persentase (%)</option>
                                <option value="fixed">Nominal Tetap (Rp)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Nilai Diskon *</label>
                            <input type="number" name="value" id="edit_value" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label>Min. Belanja *</label>
                            <input type="number" name="min_purchase" id="edit_min_purchase" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Maks. Diskon</label>
                            <input type="number" name="max_discount" id="edit_max_discount" class="form-control" placeholder="Opsional">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label>Batas Penggunaan</label>
                            <input type="number" name="usage_limit" id="edit_usage_limit" class="form-control" placeholder="Kosongkan jika tidak terbatas">
                        </div>
                        <div class="col-md-6">
                            <label>Status</label>
                            <select name="is_active" id="edit_is_active" class="form-select">
                                <option value="1">🟢 Aktif</option>
                                <option value="0">🔴 Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label>Tanggal Mulai *</label>
                            <input type="date" name="start_date" id="edit_start_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Tanggal Berakhir *</label>
                            <input type="date" name="end_date" id="edit_end_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="mt-2">
                        <label>Deskripsi</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Promo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Edit Promo Function
    function editPromo(id) {
        fetch('/owner/promos/' + id + '/data')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const promo = data.promo;
                    document.getElementById('edit_id').value = promo.id;
                    document.getElementById('edit_code').value = promo.code;
                    document.getElementById('edit_name').value = promo.name;
                    document.getElementById('edit_type').value = promo.type;
                    document.getElementById('edit_value').value = promo.value;
                    document.getElementById('edit_min_purchase').value = promo.min_purchase;
                    document.getElementById('edit_max_discount').value = promo.max_discount || '';
                    document.getElementById('edit_usage_limit').value = promo.usage_limit || '';
                    document.getElementById('edit_is_active').value = promo.is_active ? '1' : '0';
                    document.getElementById('edit_start_date').value = promo.start_date ? promo.start_date.substring(0, 10) : '';
                    document.getElementById('edit_end_date').value = promo.end_date ? promo.end_date.substring(0, 10) : '';
                    document.getElementById('edit_description').value = promo.description || '';
                    
                    document.getElementById('editPromoForm').action = '/owner/promos/' + id;
                    
                    var modal = new bootstrap.Modal(document.getElementById('editPromoModal'));
                    modal.show();
                } else {
                    alert('Gagal mengambil data promo: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            });
    }
    
    // Delete Promo Function
    function deletePromo(id) {
        if (confirm('Yakin ingin menghapus promo ini?')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/owner/promos/' + id;
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection