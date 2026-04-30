@extends('layouts.owner')

@section('content')
<div class="dashboard-title">
    <h3><i class="fas fa-file-alt"></i> Laporan dari Admin</h3>
    <p>Daftar laporan keuangan yang dibuat oleh admin</p>
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
                    <td>{{ $report->title }} @if(!$report->is_read) <span class="badge bg-danger">Baru</span> @endif</td>
                    <td>{{ $report->period_start->format('d/m/Y') }} - {{ $report->period_end->format('d/m/Y') }}</td>
                    <td class="text-success">Rp {{ number_format($report->total_revenue, 0, ',', '.') }}</td>
                    <td>
                        @if($report->status == 'approved')
                            <span class="badge bg-success">Disetujui</span>
                        @else
                            <span class="badge bg-warning">Menunggu</span>
                        @endif
                    </td>
                    <td>{{ $report->creator->name }}</td>
                    <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('owner.reports.show', $report->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <p>Belum ada laporan dari admin</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $reports->links() }}
</div>
@endsection