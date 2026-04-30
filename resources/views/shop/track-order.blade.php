@extends('layouts.app')

@section('content')
<style>
    .timeline {
        position: relative;
        padding: 20px 0;
    }
    .timeline-item {
        position: relative;
        padding-left: 40px;
        margin-bottom: 30px;
    }
    .timeline-icon {
        position: absolute;
        left: 0;
        top: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
    }
    .timeline-icon.completed {
        background: #28A745;
    }
    .timeline-icon.active {
        background: #FF6B35;
        animation: pulse 1.5s infinite;
    }
    .timeline-icon.pending {
        background: #FFC107;
    }
    .timeline-content {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    .btn-confirm {
        background: linear-gradient(135deg, #28A745, #1E7E34);
        border: none;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40,167,69,0.3);
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header" style="background: #2C1810; color: white;">
                    <h4 class="mb-0"><i class="fas fa-search"></i> Lacak Pesanan</h4>
                </div>
                <div class="card-body">
                    @if(!isset($order))
                    <form method="GET" action="{{ route('order.track') }}">
                        <div class="mb-3">
                            <label class="form-label">Masukkan Nomor Pesanan</label>
                            <input type="text" name="order_number" class="form-control" placeholder="Contoh: ORD-20260413-XXXXXX" required>
                        </div>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-search"></i> Lacak Pesanan
                        </button>
                    </form>
                    @else
                    <div class="text-center mb-4">
                        <h4>Status Pesanan: 
                            <span id="trackStatus">
                                @if($order->status == 'pending')
                                    <span class="badge bg-warning">🟡 Pending</span>
                                @elseif($order->status == 'processing')
                                    <span class="badge bg-info">🔵 Processing</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge bg-success">✅ Completed</span>
                                @else
                                    <span class="badge bg-danger">❌ Cancelled</span>
                                @endif
                            </span>
                        </h4>
                        <p class="text-muted">Nomor Pesanan: <strong>{{ $order->order_number }}</strong></p>
                    </div>
                    
                    <!-- Timeline Status -->
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-icon completed">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Pesanan Dibuat</h6>
                                <small>{{ $order->created_at->format('d/m/Y H:i:s') }}</small>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-icon {{ $order->payment_status == 'paid' ? 'completed' : ($order->payment_status == 'pending' ? 'active' : 'pending') }}">
                                <i class="fas {{ $order->payment_status == 'paid' ? 'fa-check' : ($order->payment_status == 'pending' ? 'fa-spinner fa-pulse' : 'fa-clock') }}"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Pembayaran</h6>
                                <small>
                                    @if($order->payment_status == 'paid')
                                        ✅ Pembayaran telah dikonfirmasi
                                    @else
                                        ⏳ Menunggu konfirmasi pembayaran
                                    @endif
                                </small>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-icon {{ $order->status == 'processing' ? 'active' : ($order->status == 'completed' ? 'completed' : 'pending') }}">
                                <i class="fas {{ $order->status == 'processing' ? 'fa-spinner fa-pulse' : ($order->status == 'completed' ? 'fa-check' : 'fa-clock') }}"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Pesanan Diproses</h6>
                                <small>
                                    @if($order->status == 'completed')
                                        ✅ Pesanan telah selesai diproses
                                    @elseif($order->status == 'processing')
                                        🔄 Pesanan sedang diproses oleh barista
                                    @else
                                        ⏳ Menunggu diproses
                                    @endif
                                </small>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-icon {{ $order->delivery_status == 'shipped' ? 'active' : ($order->delivery_status == 'delivered' ? 'completed' : 'pending') }}">
                                <i class="fas {{ $order->delivery_status == 'shipped' ? 'fa-truck fa-pulse' : ($order->delivery_status == 'delivered' ? 'fa-check' : 'fa-clock') }}"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Pengiriman</h6>
                                <small>
                                    @if($order->delivery_status == 'delivered')
                                        ✅ Pesanan telah sampai
                                    @elseif($order->delivery_status == 'shipped')
                                        🚚 Pesanan sedang dalam perjalanan
                                    @else
                                        ⏳ Menunggu pengiriman
                                    @endif
                                </small>
                            </div>
                        </div>
                        
                        <div class="timeline-item" id="confirmationTimeline">
                            <div class="timeline-icon {{ $order->confirmed_at ? 'completed' : 'pending' }}">
                                <i class="fas {{ $order->confirmed_at ? 'fa-check' : 'fa-clock' }}"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Konfirmasi Penerimaan</h6>
                                <small id="confirmationMessage">
                                    @if($order->confirmed_at)
                                        ✅ Pesanan telah dikonfirmasi diterima pada {{ \Carbon\Carbon::parse($order->confirmed_at)->format('d/m/Y H:i:s') }}
                                    @else
                                        ⏳ Menunggu konfirmasi pesanan telah diterima
                                    @endif
                                </small>
                                @if(!$order->confirmed_at && $order->delivery_status == 'delivered')
                                <div class="mt-3">
                                    <button class="btn btn-confirm" onclick="confirmOrderReceived({{ $order->id }})">
                                        <i class="fas fa-check-circle"></i> Konfirmasi Pesanan Diterima
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="border rounded p-3 mb-3">
                                <strong>Status Pembayaran:</strong><br>
                                <span id="trackPayment">
                                    @if($order->payment_status == 'paid')
                                        <span class="badge bg-success">✅ Lunas</span>
                                    @else
                                        <span class="badge bg-danger">⏳ Belum Dibayar</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 mb-3">
                                <strong>Metode Pembayaran:</strong><br>
                                {{ ucfirst($order->payment_method) }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="border rounded p-3 mb-3">
                        <strong>Detail Pesanan:</strong>
                        <table class="table table-sm mt-2">
                            @php $items = is_string($order->items) ? json_decode($order->items, true) : $order->items; @endphp
                            @foreach($items as $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td class="text-center">x{{ $item['quantity'] }}</td>
                                <td class="text-end">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr class="table-light">
                                <td colspan="2" class="text-end"><strong>Total:</strong></td>
                                <td class="text-end"><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                            </tr>
                        </table>
                    </div>
                    
                    <a href="{{ route('shop.index') }}" class="btn btn-outline-custom">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($order))
<script>
    let orderId = {{ $order->id }};
    
    function confirmOrderReceived(orderId) {
        if (confirm('Apakah Anda sudah menerima pesanan ini? Konfirmasi ini tidak dapat dibatalkan.')) {
            fetch('{{ route("order.confirm") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order_id: orderId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Terima kasih! Pesanan telah dikonfirmasi diterima.');
                    location.reload();
                } else {
                    alert('Gagal mengkonfirmasi pesanan: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
        }
    }
    
    function updateTrackingStatus() {
        fetch('{{ route("order.check-status") }}?order_id=' + orderId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateTimeline(data);
                }
            })
            .catch(error => console.error('Error:', error));
    }
    
    function updateTimeline(data) {
        // Update status badge
        let statusHtml = '';
        switch(data.status) {
            case 'pending':
                statusHtml = '<span class="badge bg-warning">🟡 Pending</span>';
                break;
            case 'processing':
                statusHtml = '<span class="badge bg-info">🔵 Processing</span>';
                break;
            case 'completed':
                statusHtml = '<span class="badge bg-success">✅ Completed</span>';
                break;
            default:
                statusHtml = '<span class="badge bg-danger">❌ Cancelled</span>';
        }
        document.getElementById('trackStatus').innerHTML = statusHtml;
        
        // Update payment status
        if (data.payment_status === 'paid') {
            document.getElementById('trackPayment').innerHTML = '<span class="badge bg-success">✅ Lunas</span>';
        }
        
        // Update confirmation message
        if (data.confirmed_at) {
            document.getElementById('confirmationMessage').innerHTML = '✅ Pesanan telah dikonfirmasi diterima pada ' + new Date(data.confirmed_at).toLocaleString('id-ID');
            const confirmTimeline = document.getElementById('confirmationTimeline');
            confirmTimeline.querySelector('.timeline-icon').classList.remove('pending', 'active');
            confirmTimeline.querySelector('.timeline-icon').classList.add('completed');
            confirmTimeline.querySelector('.timeline-icon i').className = 'fas fa-check';
        }
    }
    
    // Update setiap 10 detik
    setInterval(updateTrackingStatus, 10000);
    updateTrackingStatus();
</script>
@endif
@endsection