@extends('layouts.owner')

@section('content')
<div class="dashboard-title">
    <h3><i class="fas fa-boxes"></i> Manajemen Stok</h3>
    <p>Pantau dan kelola stok produk</p>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="stats-card">
            <div class="stats-value">{{ $totalProducts }}</div>
            <div class="stats-label">Total Produk</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card">
            <div class="stats-value text-warning">{{ $lowStockProducts }}</div>
            <div class="stats-label">Stok Menipis</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card">
            <div class="stats-value text-danger">{{ $outOfStock }}</div>
            <div class="stats-label">Stok Habis</div>
        </div>
    </div>
</div>

<div class="chart-container">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Stok Saat Ini</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        @if($product->stock <= 0)
                            <span class="badge bg-danger">Habis</span>
                        @elseif($product->stock <= 10)
                            <span class="badge bg-warning">Menipis</span>
                        @else
                            <span class="badge bg-success">Aman</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection