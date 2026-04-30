@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Menu Customer</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('customer.dashboard') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="{{ route('customer.orders') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-shopping-cart"></i> Pesanan Saya
                    </a>
                    <a href="{{ route('customer.profile') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user-edit"></i> Profil Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="list-group-item list-group-item-action text-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Selamat Datang, {{ Auth::user()->name }}!</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card text-white bg-primary">
                                <div class="card-body">
                                    <h6 class="card-title">Total Pesanan</h6>
                                    <h2 class="mb-0">{{ $totalOrders }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-white bg-warning">
                                <div class="card-body">
                                    <h6 class="card-title">Menunggu Diproses</h6>
                                    <h2 class="mb-0">{{ $pendingOrders }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-white bg-success">
                                <div class="card-body">
                                    <h6 class="card-title">Pesanan Selesai</h6>
                                    <h2 class="mb-0">{{ $completedOrders }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h6 class="mt-4">Pesanan Terbaru</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        @if($order->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($order->status == 'processing')
                                            <span class="badge bg-info">Processing</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @else
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('order.track') }}?order_number={{ $order->order_number }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada pesanan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection