@extends('layouts.admin')

@section('content')
<style>
    .stats-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .btn-create {
        background: linear-gradient(135deg, #C49A6C, #A67C52);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 30px;
    }
    
    .status-draft { background: #ffc107; color: #856404; padding: 4px 12px; border-radius: 20px; font-size: 11px; }
    .status-submitted { background: #17a2b8; color: white; padding: 4px 12px; border-radius: 20px; font-size: 11px; }
    .status-approved { background: #28a745; color: white; padding: 4px 12px; border-radius: 20px; font-size: 11px; }
    
    .btn-approve {
        background: #28a745;
        color: white;
        border: none;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 12px;
    }
</style>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-file-alt" style="color: #FF6B35;"></i> Laporan Keuangan
    </h1>
    <div>
        <a href="{{ route('admin.reports.create') }}" class="btn btn-create">
            <i class="fas fa-plus"></i> Buat Laporan Baru
        </a>
    </div>
</div>

<div class="chart-container">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul Laporan</th>
                    <th>Periode</th>
                    <th>Total Pendapatan</th>
                    <th>Laba Bersih</th>
                    <th>Status</th>
                    <th>Dibuat Oleh</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                <tr>
                    <td>#{{ $report->id }}</td>
                    <td>{{ $report->title }}</td>
                    <td>{{ $report->period_start->format('d/m/Y') }} - {{ $report->period_end->format('d/m/Y') }}</td>
                    <td class="text-success">Rp {{ number_format($report->total_revenue, 0, ',', '.') }}</td>
                    <td class="text-primary">Rp {{ number_format($report->net_profit, 0, ',', '.') }}</td>
                    <td>
                        @if($report->status == 'draft')
                            <span class="status-draft">Draft</span>
                        @elseif($report->status == 'submitted')
                            <span class="status-submitted">Menunggu Approve</span>
                        @else
                            <span class="status-approved">Disetujui</span>
                        @endif
                    </td>
                    <td>{{ $report->creator->name }}</td>
                    <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.reports.export', $report->id) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-download"></i>
                        </a>
                        <form action="{{ route('admin.reports.destroy', $report->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus laporan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <p>Belum ada laporan</p>
                        <a href="{{ route('admin.reports.create') }}" class="btn btn-create">Buat Laporan Pertama</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $reports->links() }}
    </div>
</div>
@endsection