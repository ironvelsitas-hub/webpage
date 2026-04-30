@extends('layouts.owner')

@section('content')
<div class="dashboard-title">
    <h3><i class="fas fa-coffee"></i> Manajemen Produk</h3>
    <p>Kelola daftar produk kopi</p>
</div>

<div class="chart-container">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>#{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category ?? '-' }}</td>
                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>
                        @if($product->stock <= 0)
                            <span class="badge bg-danger">Habis</span>
                        @elseif($product->stock <= 10)
                            <span class="badge bg-warning">{{ $product->stock }}</span>
                        @else
                            <span class="badge bg-success">{{ $product->stock }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada produk</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $products->links() }}
</div>
@endsection