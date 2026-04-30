@extends('layouts.admin')

@section('content')
<style>
    .report-header {
        background: linear-gradient(135deg, #2C1810, #4A2C1A);
        color: white;
        padding: 30px;
        border-radius: 20px;
        margin-bottom: 30px;
    }
    
    .info-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        height: 100%;
        transition: transform 0.3s ease;
    }
    
    .info-card:hover {
        transform: translateY(-5px);
    }
    
    .btn-print {
        background: #6c757d;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 30px;
        transition: all 0.3s ease;
    }
    
    .btn-print:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }
    
    .btn-export {
        background: #28a745;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 30px;
        transition: all 0.3s ease;
    }
    
    .btn-export:hover {
        background: #218838;
        transform: translateY(-2px);
    }
    
    .status-approved { 
        background: #28a745; 
        color: white; 
        padding: 5px 15px; 
        border-radius: 20px; 
        display: inline-block; 
    }
    
    .status-submitted { 
        background: #ffc107; 
        color: #856404; 
        padding: 5px 15px; 
        border-radius: 20px; 
        display: inline-block; 
    }
    
    .info-value {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 5px;
    }
    
    .info-label {
        font-size: 13px;
        color: #666;
        margin-bottom: 10px;
    }
    
    .table th {
        background: #f8f9fa;
        font-weight: 600;
    }
    
    .border-bottom-custom {
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }
</style>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-file-alt" style="color: #FF6B35;"></i> Detail Laporan
    </h1>
    <div>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="report-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h3><i class="fas fa-chart-line"></i> {{ $report->title }}</h3>
            <p class="mb-0 mt-2">
                <i class="fas fa-calendar-alt"></i> Periode: {{ $report->period_start->format('d/m/Y') }} - {{ $report->period_end->format('d/m/Y') }}
            </p>
            <p class="mb-0">
                <i class="fas fa-user-circle"></i> Dibuat oleh: {{ $report->creator->name }} | {{ $report->created_at->format('d/m/Y H:i:s') }}
            </p>
        </div>
        <div class="mt-2 mt-md-0">
            @if($report->status == 'submitted')
                <span class="status-submitted"><i class="fas fa-clock"></i> Menunggu Approve</span>
                @if(auth()->user()->role == 'owner')
                <form action="{{ route('admin.reports.approve', $report->id) }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm w-100">
                        <i class="fas fa-check-circle"></i> Approve Laporan
                    </button>
                </form>
                @endif
            @elseif($report->status == 'approved')
                <span class="status-approved"><i class="fas fa-check-circle"></i> Disetujui</span>
            @endif
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="info-card text-center">
            <div class="info-label">Total Pendapatan</div>
            <div class="info-value text-success">
                Rp {{ number_format($report->total_revenue, 0, ',', '.') }}
            </div>
            <small class="text-muted">Dari semua pesanan</small>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="info-card text-center">
            <div class="info-label">Total Pesanan</div>
            <div class="info-value text-primary">
                {{ number_format($report->total_orders) }}
            </div>
            <small class="text-muted">Delivery + Dine In</small>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="info-card text-center">
            <div class="info-label">Rata-rata per Pesanan</div>
            <div class="info-value text-info">
                Rp {{ number_format($report->total_orders > 0 ? $report->total_revenue / $report->total_orders : 0, 0, ',', '.') }}
            </div>
            <small class="text-muted">Average Order Value</small>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="info-card">
            <h5 class="border-bottom-custom">
                <i class="fas fa-chart-line text-primary"></i> Rincian Pendapatan
            </h5>
            <div class="row">
                <div class="col-6">
                    <div class="text-center p-3 bg-light rounded-3">
                        <small class="text-muted">Delivery</small>
                        <h4 class="text-info mb-0">
                            Rp {{ number_format($report->data['delivery_revenue'] ?? 0, 0, ',', '.') }}
                        </h4>
                        <small>{{ number_format($report->data['delivery_count'] ?? 0) }} pesanan</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="text-center p-3 bg-light rounded-3">
                        <small class="text-muted">Dine In</small>
                        <h4 class="text-purple mb-0" style="color: #6f42c1;">
                            Rp {{ number_format($report->data['dinein_revenue'] ?? 0, 0, ',', '.') }}
                        </h4>
                        <small>{{ number_format($report->data['dinein_count'] ?? 0) }} pesanan</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="info-card">
            <h5 class="border-bottom-custom">
                <i class="fas fa-chart-pie text-success"></i> Margin Keuntungan
            </h5>
            <div class="text-center">
                <div class="progress mb-3" style="height: 35px; border-radius: 20px; background-color: #e9ecef;">
                    <div class="progress-bar bg-success" role="progressbar" 
                         style="width: {{ min($report->data['profit_margin'] ?? 0, 100) }}%; border-radius: 20px; font-weight: bold; font-size: 14px;">
                        {{ number_format($report->data['profit_margin'] ?? 0, 1) }}%
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-6">
                        <div class="p-2 bg-light rounded-3">
                            <small class="text-muted">Total Pendapatan</small>
                            <div class="fw-bold">100%</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-light rounded-3">
                            <small class="text-muted">Margin Laba</small>
                            <div class="fw-bold text-success">{{ number_format($report->data['profit_margin'] ?? 0, 1) }}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="info-card">
            <h5 class="border-bottom-custom">
                <i class="fas fa-trophy text-warning"></i> Top 5 Produk Terlaris
            </h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Produk</th>
                            <th class="text-end">Terjual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($report->data['top_products'] ?? [] as $product => $quantity)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <i class="fas fa-coffee text-primary"></i> 
                                <strong>{{ $product }}</strong>
                            </td>
                            <td class="text-end">
                                <span class="badge bg-primary rounded-pill">{{ number_format($quantity) }}x</span>
                            </td>
                        </tr>
                        @empty
                        </table>
                            <td colspan="3" class="text-center py-4">
                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                <p class="mb-0">Belum ada data produk terlaris</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="info-card">
            <h5 class="border-bottom-custom">
                <i class="fas fa-chart-simple"></i> Ringkasan Eksekutif
            </h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3 mb-2 mb-md-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-truck text-info"></i> Delivery</span>
                            <span class="fw-bold">{{ number_format(($report->data['delivery_revenue'] ?? 0) / max($report->total_revenue, 1) * 100, 1) }}%</span>
                        </div>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar bg-info" style="width: {{ ($report->data['delivery_revenue'] ?? 0) / max($report->total_revenue, 1) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-utensils text-purple" style="color: #6f42c1;"></i> Dine In</span>
                            <span class="fw-bold">{{ number_format(($report->data['dinein_revenue'] ?? 0) / max($report->total_revenue, 1) * 100, 1) }}%</span>
                        </div>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar" style="width: {{ ($report->data['dinein_revenue'] ?? 0) / max($report->total_revenue, 1) * 100 }}%; background-color: #6f42c1;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12 text-center">
        <button onclick="window.print()" class="btn btn-print me-2">
            <i class="fas fa-print"></i> Cetak Laporan
        </button>
        <a href="{{ route('admin.reports.export', $report->id) }}" class="btn btn-export">
            <i class="fas fa-download"></i> Export CSV
        </a>
    </div>
</div>
@endsection