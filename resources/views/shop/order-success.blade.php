@extends('layouts.app')

@section('content')
<style>
    .btn-back-custom {
        background: #6c757d;
        border: none;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    .btn-back-custom:hover {
        background: #5a6268;
        transform: translateY(-2px);
        color: white;
    }
    .btn-primary-custom {
        background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 100%);
        border: none;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255,107,53,0.3);
        color: white;
    }
    .btn-outline-custom {
        border: 2px solid #FF6B35;
        background: transparent;
        padding: 8px 18px;
        border-radius: 25px;
        font-weight: 600;
        color: #FF6B35;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    .btn-outline-custom:hover {
        background: #FF6B35;
        color: white;
        transform: translateY(-2px);
    }
    .action-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 20px;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>
                    
                    <h2 class="fw-bold mb-3" style="color: #2C1810;">Pembayaran Berhasi!</h2>
                    <p class="lead text-muted mb-4">Terima kasih telah berbelanja di Kopi Ancol</p>
                    
                    <div class="alert alert-info">
                        <strong>Nomor Pesanan:</strong> {{ $order->order_number }}
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="border rounded p-3 mb-3" id="statusCard">
                                <small class="text-muted">Status Pesanan</small>
                                <h5 class="mb-0" id="orderStatus">
                                    @if($order->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($order->status == 'processing')
                                        <span class="badge bg-info">Processing</span>
                                    @elseif($order->status == 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @else
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </h5>
                                <small id="statusMessage">
                                    @if($order->status == 'pending')
                                        Pesanan akan segera diproses
                                    @elseif($order->status == 'processing')
                                        Pesanan sedang diproses
                                    @elseif($order->status == 'completed')
                                        Pesanan selesai
                                    @else
                                        Pesanan dibatalkan
                                    @endif
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 mb-3" id="paymentCard">
                                <small class="text-muted">Metode Pembayaran</small>
                                <h5 class="mb-0">{{ ucfirst($order->payment_method) }}</h5>
                                <small id="paymentMessage">
                                    @if($order->payment_status == 'paid')
                                        <span class="text-success">✅ Pembayaran sudah dikonfirmasi</span>
                                    @else
                                        <span class="text-warning">⏳ Menunggu konfirmasi pembayaran</span>
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Progress Bar Status -->
                    <div class="mt-4">
                        <h6>Status Pesanan:</h6>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-warning" id="progressPending" style="width: 0%">Pending</div>
                            <div class="progress-bar bg-info" id="progressProcessing" style="width: 0%">Processing</div>
                            <div class="progress-bar bg-success" id="progressCompleted" style="width: 0%">Completed</div>
                        </div>
                    </div>
                    
                    <div class="alert alert-success mt-3" id="infoAlert">
                        <i class="fas fa-info-circle"></i> 
                        Pesanan Anda telah masuk ke sistem. Admin akan segera memproses pesanan Anda.
                    </div>
                    
                    <div class="action-buttons">
                        <a href="{{ route('checkout') }}" class="btn-back-custom">
                            <i class="fas fa-arrow-left"></i> Kembali ke Checkout
                        </a>
                        <a href="{{ route('shop.index') }}" class="btn-primary-custom">
                            <i class="fas fa-shopping-cart"></i> Lanjutkan Belanja
                        </a>
                        <a href="{{ route('order.track') }}?order_number={{ $order->order_number }}" class="btn-outline-custom">
                            <i class="fas fa-search"></i> Lacak Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let orderId = {{ $order->id }};
    let checkInterval;
    
    function updateOrderStatus() {
        fetch('{{ route("order.check-status") }}?order_id=' + orderId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update status badge
                    const statusBadge = document.getElementById('orderStatus');
                    const statusMessage = document.getElementById('statusMessage');
                    const paymentMessage = document.getElementById('paymentMessage');
                    
                    // Update status pesanan
                    let statusHtml = '';
                    let statusText = '';
                    let progressPending = 0;
                    let progressProcessing = 0;
                    let progressCompleted = 0;
                    
                    switch(data.status) {
                        case 'pending':
                            statusHtml = '<span class="badge bg-warning">Pending</span>';
                            statusText = 'Pesanan akan segera diproses';
                            progressPending = 100;
                            break;
                        case 'processing':
                            statusHtml = '<span class="badge bg-info">Processing</span>';
                            statusText = 'Pesanan sedang diproses';
                            progressProcessing = 100;
                            break;
                        case 'completed':
                            statusHtml = '<span class="badge bg-success">Completed</span>';
                            statusText = 'Pesanan selesai';
                            progressCompleted = 100;
                            break;
                        default:
                            statusHtml = '<span class="badge bg-danger">Cancelled</span>';
                            statusText = 'Pesanan dibatalkan';
                    }
                    
                    statusBadge.innerHTML = statusHtml;
                    statusMessage.innerHTML = statusText;
                    
                    // Update progress bar
                    document.getElementById('progressPending').style.width = progressPending + '%';
                    document.getElementById('progressProcessing').style.width = progressProcessing + '%';
                    document.getElementById('progressCompleted').style.width = progressCompleted + '%';
                    
                    // Update payment status
                    if (data.payment_status === 'paid') {
                        paymentMessage.innerHTML = '<span class="text-success">✅ Pembayaran sudah dikonfirmasi</span>';
                        document.getElementById('infoAlert').innerHTML = '<i class="fas fa-check-circle"></i> Pembayaran Anda telah dikonfirmasi! Pesanan akan segera diproses.';
                    }
                    
                    // Jika status completed, hentikan interval
                    if (data.status === 'completed') {
                        clearInterval(checkInterval);
                    }
                }
            })
            .catch(error => console.error('Error:', error));
    }
    
    // Cek status setiap 10 detik
    checkInterval = setInterval(updateOrderStatus, 10000);
    
    // Cek pertama kali
    updateOrderStatus();
</script>
@endsection