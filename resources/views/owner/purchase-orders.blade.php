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
    
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
    }
    
    .status-pending { background: #ffc107; color: #856404; }
    .status-approved { background: #17a2b8; color: white; }
    .status-received { background: #28a745; color: white; }
    .status-cancelled { background: #dc3545; color: white; }
    
    .btn-sm-custom {
        padding: 4px 10px;
        font-size: 12px;
        border-radius: 15px;
        margin: 2px;
    }
    
    .alert-warning {
        background: #fff3cd;
        border: 1px solid #ffeeba;
        color: #856404;
        border-radius: 12px;
        padding: 12px 15px;
    }
    
    .alert-link {
        color: #856404;
        font-weight: 600;
        text-decoration: underline;
    }
    
    .alert-link:hover {
        color: #533f03;
    }
</style>

<div class="dashboard-title">
    <h3><i class="fas fa-shopping-cart"></i> Pembelian Bahan Baku</h3>
    <p>Manajemen purchase order bahan baku</p>
</div>

<!-- Statistik -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="stats-card">
            <div class="stats-value text-warning">{{ $totalPending ?? 0 }}</div>
            <div class="stats-label">Pending PO</div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stats-card">
            <div class="stats-value text-success">{{ $totalReceived ?? 0 }}</div>
            <div class="stats-label">PO Diterima</div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stats-card">
            <div class="stats-value text-primary">Rp {{ number_format($totalSpent ?? 0, 0, ',', '.') }}</div>
            <div class="stats-label">Total Belanja</div>
        </div>
    </div>
</div>

<!-- Daftar Purchase Order -->
<div class="chart-container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Purchase Order</h5>
        <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addPOModal">
            <i class="fas fa-plus"></i> Buat PO Baru
        </button>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No. PO</th>
                    <th>Supplier</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal Order</th>
                    <th>Tanggal Terima</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchaseOrders ?? [] as $po)
                <tr>
                    <td><strong>{{ $po->po_number ?? '-' }}</strong></td>
                    <td>{{ $po->supplier ?? '-' }}</td>
                    <td>Rp {{ number_format($po->total_amount ?? 0, 0, ',', '.') }}</td>
                    <td>
                        @php
                            $statusClass = 'status-pending';
                            $statusText = 'Pending';
                            if(($po->status ?? '') == 'approved') {
                                $statusClass = 'status-approved';
                                $statusText = 'Approved';
                            } elseif(($po->status ?? '') == 'received') {
                                $statusClass = 'status-received';
                                $statusText = 'Received';
                            } elseif(($po->status ?? '') == 'cancelled') {
                                $statusClass = 'status-cancelled';
                                $statusText = 'Cancelled';
                            }
                        @endphp
                        <span class="status-badge {{ $statusClass }}">
                            {{ $statusText }}
                        </span>
                    </td>
                    <td>{{ isset($po->order_date) ? $po->order_date->format('d/m/Y') : '-' }}</td>
                    <td>{{ isset($po->received_date) ? $po->received_date->format('d/m/Y') : '-' }}</td>
                    <td>
                        @if(($po->status ?? '') == 'pending')
                            <button class="btn btn-success btn-sm-custom" onclick="updateStatus({{ $po->id ?? 0 }}, 'approved')">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button class="btn btn-danger btn-sm-custom" onclick="updateStatus({{ $po->id ?? 0 }}, 'cancelled')">
                                <i class="fas fa-times"></i> Batal
                            </button>
                        @endif
                        @if(($po->status ?? '') == 'approved')
                            <button class="btn btn-primary btn-sm-custom" onclick="updateStatus({{ $po->id ?? 0 }}, 'received')">
                                <i class="fas fa-boxes"></i> Terima
                            </button>
                        @endif
                        @if(($po->status ?? '') != 'received' && ($po->status ?? '') != 'received')
                            <button class="btn btn-danger btn-sm-custom" onclick="deletePO({{ $po->id ?? 0 }})">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                        <p>Belum ada purchase order</p>
                        <button class="btn btn-add btn-sm" data-bs-toggle="modal" data-bs-target="#addPOModal">
                            <i class="fas fa-plus"></i> Buat PO Pertama
                        </button>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Buat PO Baru -->
<div class="modal fade" id="addPOModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus"></i> Buat Purchase Order Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('owner.purchase-orders.store') }}" id="poForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Supplier</label>
                            <input type="text" name="supplier" class="form-control" placeholder="Nama supplier">
                        </div>
                        <div class="col-md-6">
                            <label>Tanggal Order *</label>
                            <input type="date" name="order_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label>Tanggal Perkiraan Tiba</label>
                            <input type="date" name="expected_date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Catatan</label>
                            <input type="text" name="notes" class="form-control" placeholder="Catatan tambahan">
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    <h6><i class="fas fa-box"></i> Item Pembelian</h6>
                    
                    <!-- Di dalam modal Buat PO Baru, bagian item pembelian -->
                    <div id="poItemsContainer">
                        <div class="row mb-2 po-item">
                            <div class="col-md-5">
                                <select name="items[0][material_id]" class="form-select" required>
                                    <option value="">Pilih Bahan Baku</option>
                                    @if($materials && count($materials) > 0)
                                        @foreach($materials as $material)
                                            <option value="{{ $material->id }}">{{ $material->name }} ({{ $material->unit }}) - Rp {{ number_format($material->unit_price, 0, ',', '.') }}</option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>Tidak ada bahan baku, silakan tambah terlebih dahulu</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="items[0][quantity]" class="form-control" placeholder="Jumlah" required>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="items[0][price]" class="form-control" placeholder="Harga" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">Hapus</button>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addItem()">
                        <i class="fas fa-plus"></i> Tambah Item
                    </button>
                    
                    <!-- Tambahkan alert jika tidak ada bahan baku -->
                    @if(!$materials || count($materials) == 0)
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle"></i> 
                        <strong>Belum ada bahan baku!</strong> Silakan tambahkan bahan baku terlebih dahulu di menu 
                        <a href="{{ route('owner.raw-materials') }}" class="alert-link">Bahan Baku</a> sebelum membuat purchase order.
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan PO</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let itemIndex = 1;
    
    function addItem() {
        const container = document.getElementById('poItemsContainer');
        const newItem = document.createElement('div');
        newItem.className = 'row mb-2 po-item';
        newItem.innerHTML = `
            <div class="col-md-5">
                <select name="items[${itemIndex}][material_id]" class="form-select" required>
                    <option value="">Pilih Bahan Baku</option>
                    @if($materials && count($materials) > 0)
                        @foreach($materials as $material)
                            <option value="{{ $material->id }}">{{ $material->name }} ({{ $material->unit }}) - Rp {{ number_format($material->unit_price, 0, ',', '.') }}</option>
                        @endforeach
                    @else
                        <option value="" disabled>Tidak ada bahan baku, silakan tambah terlebih dahulu</option>
                    @endif
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${itemIndex}][quantity]" class="form-control" placeholder="Jumlah" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="items[${itemIndex}][price]" class="form-control" placeholder="Harga" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">Hapus</button>
            </div>
        `;
        container.appendChild(newItem);
        itemIndex++;
    }
    
    function removeItem(btn) {
        btn.closest('.po-item').remove();
    }
    
    function updateStatus(poId, status) {
        if (confirm(`Apakah Anda yakin ingin mengubah status PO ini menjadi ${status.toUpperCase()}?`)) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/owner/purchase-orders/' + poId + '/status';
            form.innerHTML = '@csrf @method("PUT") <input type="hidden" name="status" value="' + status + '">';
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    function deletePO(poId) {
        if (confirm('Yakin ingin menghapus PO ini?')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/owner/purchase-orders/' + poId;
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection