@extends('layouts.owner')

@section('content')
<style>
    .dashboard-title {
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .dashboard-title h3 {
        font-weight: 700;
        color: #2C1810;
    }
    
    .stats-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .stats-value {
        font-size: 28px;
        font-weight: 800;
        color: #2C1810;
        margin-bottom: 5px;
    }
    
    .stats-label {
        font-size: 14px;
        color: #666;
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
    }
    
    .btn-edit {
        background: #17a2b8;
        color: white;
        border: none;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 12px;
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
    
    .form-control:focus, .form-select:focus {
        border-color: #C49A6C;
        box-shadow: 0 0 0 0.2rem rgba(196, 154, 108, 0.25);
    }
    
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
    }
    
    .status-active {
        background: #28a745;
        color: white;
    }
    
    .status-inactive {
        background: #dc3545;
        color: white;
    }
    
    .role-badge {
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .role-admin {
        background: #6f42c1;
        color: white;
    }
    
    .role-staff {
        background: #17a2b8;
        color: white;
    }
    
    .role-kasir {
        background: #fd7e14;
        color: white;
    }
    
    .role-barista {
        background: #20c997;
        color: white;
    }
    
    .info-note {
        background: #e7f3ff;
        border-left: 4px solid #C49A6C;
        padding: 12px 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
</style>

<div class="dashboard-title">
    <h3><i class="fas fa-users"></i> Manajemen Karyawan</h3>
    <p>Kelola data karyawan (Admin, Staff, Kasir, Barista)</p>
</div>

<!-- Info Note -->
<div class="info-note">
    <i class="fas fa-info-circle"></i> 
    <strong>Catatan:</strong> Halaman ini hanya menampilkan karyawan. Customer tidak ditampilkan di sini.
</div>

<!-- Statistik -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="stats-card">
            <div class="stats-value">{{ $totalStaff }}</div>
            <div class="stats-label">Total Karyawan</div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stats-card">
            <div class="stats-value text-success">{{ $activeStaff }}</div>
            <div class="stats-label">Aktif</div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stats-card">
            <div class="stats-value text-warning">{{ $pendingStaff }}</div>
            <div class="stats-label">Tidak Aktif</div>
        </div>
    </div>
</div>

<!-- Daftar Karyawan -->
<div class="chart-container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Karyawan</h5>
        <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addStaffModal">
            <i class="fas fa-plus"></i> Tambah Karyawan
        </button>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staff as $member)
                <tr>
                    <td><strong>{{ $member->name }}</strong></td>
                    <td>{{ $member->email }}</td>
                    <td>
                        @php
                            $roleClass = 'role-staff';
                            if($member->role == 'admin') $roleClass = 'role-admin';
                            elseif($member->role == 'kasir') $roleClass = 'role-kasir';
                            elseif($member->role == 'barista') $roleClass = 'role-barista';
                        @endphp
                        <span class="role-badge {{ $roleClass }}">
                            {{ ucfirst($member->role) }}
                        </span>
                    </td>
                    <td>
                        @if($member->is_active ?? true)
                            <span class="status-badge status-active">Aktif</span>
                        @else
                            <span class="status-badge status-inactive">Tidak Aktif</span>
                        @endif
                    </td>
                    <td>{{ $member->created_at->format('d/m/Y') }}</td>
                    <td>
                        <button class="btn-edit" onclick="editStaff({{ $member->id }}, '{{ $member->name }}', '{{ $member->email }}', '{{ $member->role }}', {{ $member->is_active ?? 1 }})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-delete" onclick="deleteStaff({{ $member->id }}, '{{ $member->name }}')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <p>Belum ada karyawan</p>
                        <button class="btn btn-add btn-sm" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                            <i class="fas fa-plus"></i> Tambah Karyawan Pertama
                        </button>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Karyawan -->
<div class="modal fade" id="addStaffModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus"></i> Tambah Karyawan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addStaffForm" method="POST" action="{{ route('owner.staff.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="email@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="kasir">Kasir</option>
                            <option value="barista">Barista</option>
                        </select>
                        <small class="text-muted">Customer tidak dapat ditambahkan dari sini</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon (Opsional)</label>
                        <input type="text" name="phone" class="form-control" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle"></i> Karyawan akan dapat mengakses panel admin sesuai role masing-masing.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-add">Simpan Karyawan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Karyawan -->
<div class="modal fade" id="editStaffModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit"></i> Edit Karyawan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editStaffForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password (Kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" id="edit_role" class="form-select">
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="kasir">Kasir</option>
                            <option value="barista">Barista</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="is_active" id="edit_status" class="form-select">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-add">Update Karyawan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Edit Staff
    function editStaff(id, name, email, role, isActive) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_role').value = role;
        document.getElementById('edit_status').value = isActive;
        
        // Set form action
        document.getElementById('editStaffForm').action = '/owner/staff/' + id;
        
        // Show modal
        var modal = new bootstrap.Modal(document.getElementById('editStaffModal'));
        modal.show();
    }
    
    // Delete Staff
    function deleteStaff(id, name) {
        if (confirm('Apakah Anda yakin ingin menghapus karyawan "' + name + '"?')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/owner/staff/' + id;
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    // Auto close modal after submit
    @if(session('success'))
        setTimeout(function() {
            var modal = bootstrap.Modal.getInstance(document.getElementById('addStaffModal'));
            if (modal) modal.hide();
            var editModal = bootstrap.Modal.getInstance(document.getElementById('editStaffModal'));
            if (editModal) editModal.hide();
        }, 1000);
    @endif
</script>
@endsection