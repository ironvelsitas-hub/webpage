@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-box" style="color: #FF6B35;"></i> Manajemen Produk
    </h1>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Produk
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th width="50">ID</th>
                        <th width="80">Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th width="180">Stok</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            @if($product->image && file_exists(public_path($product->image)))
                                <img src="{{ asset($product->image) }}" width="50" height="50" style="object-fit: cover; border-radius: 5px;">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-secondary rounded" style="width: 50px; height: 50px;">
                                    <i class="fas fa-mug-hot text-white"></i>
                                </div>
                            @endif
                        </td>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td>{{ $product->category }}</td>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold {{ $product->stock <= 0 ? 'text-danger' : ($product->stock <= 10 ? 'text-warning' : 'text-success') }}">
                                    {{ $product->stock }}
                                </span>
                                <form action="{{ route('admin.products.update-stock', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <div class="input-group input-group-sm" style="width: 100px;">
                                        <input type="number" name="stock" value="{{ $product->stock }}" class="form-control form-control-sm" min="0">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="fas fa-save"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </td>
                        <td>
                            @if($product->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-box-open fa-3x text-muted mb-2"></i>
                            <p class="mb-0">Belum ada produk</p>
                            <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-plus"></i> Tambah Produk Pertama
                            </a>
                         </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection