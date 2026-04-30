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
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .stats-value {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 5px;
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
    
    .status-paid {
        background: #28a745;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
    }
    
    .status-pending {
        background: #ffc107;
        color: #856404;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
    }
    
    .period-selector {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .period-selector select {
        border-radius: 20px;
        padding: 5px 15px;
        border: 1px solid #ddd;
    }
    
    .salary-input {
        width: 120px;
    }
    
    .salary-input-sm {
        width: 100px;
    }
    
    .btn-action {
        padding: 4px 10px;
        font-size: 12px;
        margin: 2px;
        border-radius: 15px;
    }
    
    .btn-pay {
        background: #28a745;
        color: white;
        border: none;
    }
    
    .btn-pay:hover {
        background: #218838;
        color: white;
    }
    
    .btn-delete-salary {
        background: #dc3545;
        color: white;
        border: none;
    }
    
    .btn-delete-salary:hover {
        background: #c82333;
        color: white;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .salary-total {
        font-weight: bold;
        color: #C49A6C;
    }
    
    .alert-custom {
        border-radius: 15px;
        padding: 12px 20px;
        margin-bottom: 20px;
    }
</style>

<div class="dashboard-title">
    <h3><i class="fas fa-money-bill-wave"></i> Manajemen Gaji Karyawan</h3>
    <p>Kelola gaji karyawan (Admin, Staff, Kasir, Barista)</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Statistik -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-value text-primary">Rp {{ number_format($totalSalaryThisMonth, 0, ',', '.') }}</div>
            <div class="stats-label">Total Gaji Bulan Ini</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-value text-success">Rp {{ number_format($paidSalaryThisMonth, 0, ',', '.') }}</div>
            <div class="stats-label">Sudah Dibayar</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-value text-warning">Rp {{ number_format($pendingSalaryThisMonth, 0, ',', '.') }}</div>
            <div class="stats-label">Belum Dibayar</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-value text-info">Rp {{ number_format($totalSalaryThisYear, 0, ',', '.') }}</div>
            <div class="stats-label">Total Gaji Tahun Ini</div>
        </div>
    </div>
</div>

<!-- Filter Periode -->
<div class="chart-container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
        <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Periode Gaji</h5>
        <div class="period-selector">
            <form method="GET" action="{{ route('owner.salaries') }}" class="d-flex gap-2 align-items-center">
                <select name="month" class="form-select form-select-sm" style="width: auto;">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $currentMonth == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                        </option>
                    @endfor
                </select>
                <select name="year" class="form-select form-select-sm" style="width: auto;">
                    @for($i = 2023; $i <= 2026; $i++)
                        <option value="{{ $i }}" {{ $currentYear == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-eye"></i> Tampilkan
                </button>
            </form>
            <a href="{{ route('owner.salaries.export', ['month' => $currentMonth, 'year' => $currentYear]) }}" class="btn btn-sm btn-success">
                <i class="fas fa-download"></i> Export CSV
            </a>
        </div>
    </div>
</div>

<!-- Daftar Gaji Karyawan -->
<div class="chart-container">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 15%">Nama Karyawan</th>
                    <th style="width: 10%">Role</th>
                    <th style="width: 12%">Gaji Pokok</th>
                    <th style="width: 10%">Tunjangan</th>
                    <th style="width: 10%">Bonus</th>
                    <th style="width: 10%">Potongan</th>
                    <th style="width: 12%">Total Gaji</th>
                    <th style="width: 10%">Status</th>
                    <th style="width: 16%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $index => $employee)
                @php
                    $salary = $salaries[$employee->id] ?? null;
                    $totalSalary = $salary->total_salary ?? ($salary ? ($salary->base_salary + $salary->allowance + $salary->bonus - $salary->deduction) : 3500000);
                @endphp
                <form action="{{ route('owner.salaries.store') }}" method="POST" class="salary-form">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $employee->id }}">
                    <input type="hidden" name="month" value="{{ $currentMonth }}">
                    <input type="hidden" name="year" value="{{ $currentYear }}">
                    
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $employee->name }}</strong>
                            <input type="hidden" name="employee_name" value="{{ $employee->name }}">
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ ucfirst($employee->role) }}</span>
                        </td>
                        <td>
                            <input type="number" name="base_salary" class="form-control form-control-sm salary-input" 
                                   value="{{ $salary->base_salary ?? 0 }}" required>
                        </td>
                        <td>
                            <input type="number" name="allowance" class="form-control form-control-sm salary-input" 
                                   value="{{ $salary->allowance ?? 0 }}">
                        </td>
                        <td>
                            <input type="number" name="bonus" class="form-control form-control-sm salary-input" 
                                   value="{{ $salary->bonus ?? 0 }}">
                        </td>
                        <td>
                            <input type="number" name="deduction" class="form-control form-control-sm salary-input" 
                                   value="{{ $salary->deduction ?? 0 }}">
                        </td>
                        <td>
                            <span class="salary-total" id="total-{{ $employee->id }}">
                                Rp {{ number_format($totalSalary, 0, ',', '.') }}
                            </span>
                        </td>
                        <td>
                            @if($salary)
                                @if($salary->status == 'paid')
                                    <span class="status-paid"><i class="fas fa-check-circle"></i> Sudah Dibayar</span>
                                @else
                                    <span class="status-pending"><i class="fas fa-clock"></i> Belum Dibayar</span>
                                @endif
                            @else
                                <span class="status-pending"><i class="fas fa-clock"></i> Belum Diset</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="submit" class="btn btn-primary btn-action">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                
                                @if($salary)
                                    @if($salary->status == 'pending')
                                        <button type="button" class="btn btn-success btn-action" onclick="paySalary({{ $salary->id }})">
                                            <i class="fas fa-money-bill-wave"></i> Bayar
                                        </button>
                                    @endif
                                    
                                    <button type="button" class="btn btn-danger btn-action" onclick="deleteSalary({{ $salary->id }}, '{{ $employee->name }}')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                </form>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <p>Belum ada karyawan</p>
                        <a href="{{ route('owner.staff') }}" class="btn btn-add btn-sm">
                            <i class="fas fa-plus"></i> Tambah Karyawan
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Grafik Gaji per Bulan -->
<div class="chart-container">
    <h5 class="mb-3"><i class="fas fa-chart-line"></i> Grafik Gaji per Bulan ({{ $currentYear }})</h5>
    <canvas id="salaryChart" style="height: 300px;"></canvas>
</div>

<!-- Form untuk Pay Salary (Hidden) -->
<form id="paySalaryForm" method="POST" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="status" value="paid">
</form>

<!-- Form untuk Delete Salary (Hidden) -->
<form id="deleteSalaryForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Gaji per Bulan
    const salaryCtx = document.getElementById('salaryChart').getContext('2d');
    new Chart(salaryCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Total Gaji (Rp)',
                data: @json($monthlySalaryChart),
                borderColor: '#C49A6C',
                backgroundColor: 'rgba(196, 154, 108, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#A67C52',
                pointBorderColor: '#fff',
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
    
    // Auto-calculate total salary when inputs change
    document.querySelectorAll('.salary-input').forEach(input => {
        input.addEventListener('change', function() {
            const row = this.closest('tr');
            const baseSalary = parseFloat(row.querySelector('input[name="base_salary"]').value) || 0;
            const allowance = parseFloat(row.querySelector('input[name="allowance"]').value) || 0;
            const bonus = parseFloat(row.querySelector('input[name="bonus"]').value) || 0;
            const deduction = parseFloat(row.querySelector('input[name="deduction"]').value) || 0;
            
            const total = baseSalary + allowance + bonus - deduction;
            const totalCell = row.querySelector('.salary-total');
            totalCell.innerHTML = 'Rp ' + total.toLocaleString('id-ID');
        });
    });
    
    // Pay Salary Function
    function paySalary(salaryId) {
        if (confirm('Apakah Anda yakin ingin menandai gaji ini sebagai SUDAH DIBAYAR?')) {
            const form = document.getElementById('paySalaryForm');
            form.action = '/owner/salaries/' + salaryId + '/status';
            form.submit();
        }
    }
    
    // Delete Salary Function
    function deleteSalary(salaryId, employeeName) {
        if (confirm('Apakah Anda yakin ingin menghapus data gaji ' + employeeName + '?')) {
            const form = document.getElementById('deleteSalaryForm');
            form.action = '/owner/salaries/' + salaryId;
            form.submit();
        }
    }
</script>
@endsection