@extends('layouts.owner')

@section('content')
<style>
    .dashboard-title {
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .badge-purple {
        background: #6f42c1;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
    }
    
    .discount-row {
        background-color: #e8f5e9;
    }
    
    .text-success {
        color: #28a745 !important;
    }
    
    .text-danger {
        color: #dc3545 !important;
    }
</style>

<div class="dashboard-title">
    <h3><i class="fas fa-receipt"></i> Detail Pesanan</h3>
    <p>Informasi lengkap pesanan #{{ $order->order_number }}</p>
</div>

<div class="chart-container">
    <div class="row">
        <div class="col-md-6">
            <h5><i class="fas fa-user"></i> Informasi Customer</h5>
            <table class="table table-sm">
                <tr>
                    <th width="30%">Nama</th>
                    <td>{{ $order->customer_name }}</td>
                </tr>
                <tr>
                    <th>Telepon</th>
                    <td>{{ $order->customer_phone }}</td>
                </tr>
                @if(isset($order->customer_address))
                <tr>
                    <th>Alamat</th>
                    <td>{{ $order->customer_address }}</td>
                </tr>
                @endif
                @if(isset($order->table_number))
                <tr>
                    <th>Nomor Meja</th>
                    <td>{{ $order->table_number }}</td>
                </tr>
                @endif
            </table>
        </div>
        
        <!-- Informasi Pesanan dengan Tampilan Diskon -->
        <div class="col-md-6">
            <h5><i class="fas fa-info-circle"></i> Informasi Pesanan</h5>
            <table class="table table-sm">
                <tr>
                    <th width="35%">Order Number</th>
                    <td>{{ $order->order_number }}</td>
                </tr>
                <tr>
                    <th>Tipe Pesanan</th>
                    <td>
                        @if($orderType == 'Delivery')
                            <span class="badge bg-info">🚚 Delivery</span>
                        @else
                            <span class="badge-purple">🍽️ Dine In</span>
                        @endif
                    </td>
                </tr>
                
                <!-- TAMPILAN DISKON -->
                @if(($order->discount_amount ?? 0) > 0)
                <tr style="background-color: #e8f5e9;">
                    <th><i class="fas fa-tag text-success"></i> Kode Diskon</th>
                    <td>
                        <strong class="text-success">{{ $order->discount_code ?? '-' }}</strong>
                    </td>
                </tr>
                <tr style="background-color: #e8f5e9;">
                    <th>Potongan Diskon</th>
                    <td class="text-danger">
                        <strong>- Rp {{ number_format($order->discount_amount ?? 0, 0, ',', '.') }}</strong>
                    </td>
                </tr>
                @endif
                
                <tr>
                    <th>Status</th>
                    <td>{{ ucfirst($order->status) }}</td>
                </tr>
                <tr>
                    <th>Pembayaran</th>
                    <td>{{ ucfirst($order->payment_status) }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            </table>
        </div>
    </div>
    
    <h5 class="mt-4"><i class="fas fa-box"></i> Item Pesanan</h5>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Produk</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Harga</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
                @endphp
                @foreach($items as $item)
                <tr>
                    <td>{{ $item['name'] ?? $item['product_name'] ?? '-' }}</td>
                    <td class="text-center">{{ $item['quantity'] }}x</td>
                    <td class="text-end">Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="table-light">
                <tr style="background-color: #fff3e0;">
                    <td colspan="3" class="text-end fw-bold">Subtotal</td>
                    <td class="text-end">
                        @php
                            $subtotal = 0;
                            foreach($items as $item) {
                                $subtotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
                            }
                        @endphp
                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                    </td>
                </tr>
                @if(($order->discount_amount ?? 0) > 0)
                <tr style="background-color: #6de677;">
                    <td colspan="3" class="text-end fw-bold text-success">Diskon ({{ $order->discount_code ?? '-' }})</td>
                    <td class="text-end text-success">- Rp {{ number_format($order->discount_amount ?? 0, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr style="background-color: #f8f9fa;">
                    <td colspan="3" class="text-end fw-bold">Pajak (10%)</td>
                    <td class="text-end">
                        @php
                            $tax = ($subtotal - ($order->discount_amount ?? 0)) * 0.1;
                        @endphp
                        Rp {{ number_format($tax, 0, ',', '.') }}
                    </td>
                </tr>
                <tr class="table-active">
                    <td colspan="3" class="text-end fw-bold fs-5">Total</td>
                    <td class="text-end fw-bold fs-5" style="color: #f40808;">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    @if($order->notes)
    <div class="alert alert-info mt-3">
        <strong><i class="fas fa-sticky-note"></i> Catatan:</strong><br>
        {{ $order->notes }}
    </div>
    @endif
    
    <div class="mt-3">
        <a href="{{ route('owner.orders') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Semua Pesanan
        </a>
    </div>
</div>
@endsection