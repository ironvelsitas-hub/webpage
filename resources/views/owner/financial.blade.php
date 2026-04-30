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
        position: relative;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }
    
    .stats-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        position: absolute;
        top: 20px;
        right: 20px;
    }
    
    .stats-value {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 5px;
    }
    
    .stats-label {
        font-size: 13px;
        color: #666;
    }
    
    .stats-sub {
        font-size: 11px;
        color: #999;
        margin-top: 5px;
    }
    
    .chart-container {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }
    
    .info-card {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .progress-custom {
        height: 8px;
        border-radius: 10px;
        background-color: #e9ecef;
        margin-top: 5px;
    }
    
    .progress-bar-custom {
        height: 100%;
        border-radius: 10px;
        transition: width 0.3s ease;
    }
    
    .btn-export {
        background: #28a745;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 30px;
    }
    
    .badge-delivery {
        background: #17a2b8;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
    }
    
    .badge-dinein {
        background: #6f42c1;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
    }
    
    .profit-percentage {
        font-size: 36px;
        font-weight: 800;
        text-align: center;
    }
</style>

<div class="dashboard-title">
    <h3><i class="fas fa-chart-line"></i> Laporan Keuangan</h3>
    <p>Ringkasan pendapatan terintegrasi (Delivery + Dine In)</p>
</div>

<!-- Statistik Utama (4 Kolom) -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-icon bg-success bg-opacity-10 text-success">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stats-value text-success">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
            <div class="stats-label">Total Pendapatan</div>
            <div class="stats-sub">
                <span class="badge-delivery"><i class="fas fa-truck"></i> Delivery: Rp {{ number_format($deliveryRevenue ?? 0, 0, ',', '.') }}</span>
                <span class="badge-dinein ms-1"><i class="fas fa-utensils"></i> Dine In: Rp {{ number_format($dineInRevenue ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-icon bg-danger bg-opacity-10 text-danger">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stats-value text-danger">Rp {{ number_format($totalExpense ?? 0, 0, ',', '.') }}</div>
            <div class="stats-label">Total Pengeluaran</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stats-value text-primary">Rp {{ number_format($netProfit ?? 0, 0, ',', '.') }}</div>
            <div class="stats-label">Laba Bersih</div>
        </div>
    </div>
</div>
<!-- Profit Margin -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="info-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h6><i class="fas fa-chart-pie"></i> Margin Keuntungan</h6>
                    @php
                        $profitMargin = ($totalRevenue ?? 0) > 0 ? (($netProfit ?? 0) / ($totalRevenue ?? 1)) * 100 : 0;
                    @endphp
                    <div class="progress mb-2" style="height: 25px; border-radius: 15px;">
                        <div class="progress-bar bg-success" 
                             role="progressbar" 
                             style="width: {{ $profitMargin }}%; border-radius: 15px;">
                            {{ number_format($profitMargin, 1) }}%
                        </div>
                    </div>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-info-circle"></i> Rata-rata nilai pesanan: 
                        <strong>Rp {{ number_format($averageOrderValue ?? 0, 0, ',', '.') }}</strong>
                    </p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="profit-percentage">
                        {{ number_format($profitMargin, 1) }}%
                        <div class="small-text">Laba Bersih / Pendapatan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Pendapatan per Bulan (Gabungan) -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="chart-container">
            <h5 class="mb-3"><i class="fas fa-chart-line"></i> Pendapatan per Bulan (Delivery + Dine In)</h5>
            <canvas id="monthlyRevenueChart" style="height: 300px;"></canvas>
            <div class="mt-2 text-center">
                <span class="badge-delivery me-2"><i class="fas fa-truck"></i> Delivery</span>
                <span class="badge-dinein"><i class="fas fa-utensils"></i> Dine In</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="chart-container">
            <h5 class="mb-3"><i class="fas fa-chart-pie"></i> Komposisi Pendapatan</h5>
            <canvas id="compositionChart" style="height: 250px;"></canvas>
            <div class="mt-3">
                <div class="d-flex justify-content-between mb-2">
                    <span><i class="fas fa-circle" style="color: #17a2b8;"></i> Delivery</span>
                    <span>{{ number_format($deliveryPercentage ?? 0, 1) }}%</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span><i class="fas fa-circle" style="color: #6f42c1;"></i> Dine In</span>
                    <span>{{ number_format($dineInPercentage ?? 0, 1) }}%</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Perbandingan Delivery vs Dine In per Bulan -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="chart-container">
            <h5 class="mb-3"><i class="fas fa-chart-bar"></i> Perbandingan Delivery vs Dine In per Bulan</h5>
            <canvas id="comparisonChart" style="height: 300px;"></canvas>
        </div>
    </div>
</div>

<!-- Rincian Pengeluaran -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="chart-container">
            <h5 class="mb-3"><i class="fas fa-list"></i> Rincian Pengeluaran</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th class="text-end">Nominal</th>
                            <th class="text-end">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Gaji Karyawan (Data Real) -->
                        <tr style="background-color: #FFF8F0;">
                            <td>
                                <i class="fas fa-users"></i> 
                                <strong>Gaji Karyawan</strong> 
                                <small class="text-muted">(Data real dari manajemen gaji)</small>
                            </td>
                            <td class="text-end">
                                <strong>Rp {{ number_format($totalGajiKaryawan ?? 0, 0, ',', '.') }}</strong>
                            </td>
                            <td class="text-end">
                                <div class="progress-custom">
                                    <div class="progress-bar-custom" style="width: {{ min($percentageGaji ?? 0, 100) }}%; background-color: #C49A6C;"></div>
                                </div>
                                <small>{{ number_format($percentageGaji ?? 0, 1) }}%</small>
                            </td>
                        </tr>
                        
                        <!-- Pembelian Bahan Baku (Data Real) -->
                        <tr>
                            <td>
                                <i class="fas fa-coffee"></i> 
                                <strong>Pembelian Bahan Baku</strong>
                                <small class="text-muted">(Dari purchase order)</small>
                            </td>
                            <td class="text-end">
                                <strong>Rp {{ number_format($totalPembelianBahanBaku ?? 0, 0, ',', '.') }}</strong>
                            </td>
                            <td class="text-end">
                                <div class="progress-custom">
                                    <div class="progress-bar-custom bg-info" style="width: {{ min($percentageBahanBaku ?? 0, 100) }}%;"></div>
                                </div>
                                <small>{{ number_format($percentageBahanBaku ?? 0, 1) }}%</small>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td class="fw-bold fs-5">TOTAL PENGELUARAN</td>
                            <td class="text-end fw-bold fs-5">Rp {{ number_format(($totalGajiKaryawan ?? 0) + ($totalPembelianBahanBaku ?? 0), 0, ',', '.') }}</td>
                            <td class="text-end fw-bold fs-5">100%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <!-- Info Sumber Data -->
            <div class="alert alert-info mt-3 small">
                <i class="fas fa-info-circle"></i> 
                <strong>Keterangan:</strong><br>
                - <strong>Gaji Karyawan</strong> diambil dari data real <strong>Manajemen Gaji</strong> (tabel salaries)<br>
                - <strong>Pembelian Bahan Baku</strong> diambil dari data real <strong>Purchase Order</strong> yang sudah diterima (status: received)
            </div>
        </div>
    </div>
</div>
<!-- Export Section -->
<div class="row">
    <div class="col-md-12">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-0"><i class="fas fa-file-export"></i> Export Laporan</h5>
                    <small class="text-muted">Download laporan keuangan lengkap</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('owner.export', ['period' => 'month']) }}" class="btn btn-export btn-sm">
                        <i class="fas fa-calendar-alt"></i> Bulan Ini
                    </a>
                    <a href="{{ route('owner.export', ['period' => 'year']) }}" class="btn btn-export btn-sm">
                        <i class="fas fa-calendar-year"></i> Tahun Ini
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Pendapatan per Bulan (Stacked Bar)
    const monthlyCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: @json(array_keys($monthlyRevenue ?? [])),
            datasets: [
                {
                    label: 'Delivery',
                    data: @json(array_column($monthlyRevenue ?? [], 'delivery')),
                    backgroundColor: '#17a2b8',
                    borderRadius: 8
                },
                {
                    label: 'Dine In',
                    data: @json(array_column($monthlyRevenue ?? [], 'dinein')),
                    backgroundColor: '#6f42c1',
                    borderRadius: 8
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: { stacked: true },
                y: {
                    stacked: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
    
    // Grafik Komposisi Pendapatan (Doughnut)
    const compositionCtx = document.getElementById('compositionChart').getContext('2d');
    new Chart(compositionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Delivery', 'Dine In'],
            datasets: [{
                data: [{{ $deliveryRevenue ?? 0 }}, {{ $dineInRevenue ?? 0 }}],
                backgroundColor: ['#17a2b8', '#6f42c1'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
    
    // Grafik Perbandingan per Bulan (Line)
    const comparisonCtx = document.getElementById('comparisonChart').getContext('2d');
    new Chart(comparisonCtx, {
        type: 'line',
        data: {
            labels: @json(array_column($monthlyComparison ?? [], 'month')),
            datasets: [
                {
                    label: 'Delivery',
                    data: @json(array_column($monthlyComparison ?? [], 'delivery')),
                    borderColor: '#17a2b8',
                    backgroundColor: 'rgba(23, 162, 184, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Dine In',
                    data: @json(array_column($monthlyComparison ?? [], 'dinein')),
                    borderColor: '#6f42c1',
                    backgroundColor: 'rgba(111, 66, 193, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
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
</script>
@endsection