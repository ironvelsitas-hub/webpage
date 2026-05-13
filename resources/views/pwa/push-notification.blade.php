@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-bell"></i> Notifikasi Push</h5>
                </div>
                <div class="card-body">
                    <p>Aktifkan notifikasi untuk mendapatkan informasi promo terbaru dan update pesanan.</p>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Notifikasi akan membantu Anda mengetahui:
                        <ul class="mt-2 mb-0">
                            <li>✅ Promo dan diskon terbaru</li>
                            <li>✅ Status pesanan Anda</li>
                            <li>✅ Update produk baru</li>
                        </ul>
                    </div>
                    
                    <button id="enableNotification" class="btn btn-primary">
                        <i class="fas fa-bell"></i> Aktifkan Notifikasi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('enableNotification')?.addEventListener('click', async () => {
        if ('Notification' in window && 'serviceWorker' in navigator) {
            const permission = await Notification.requestPermission();
            if (permission === 'granted') {
                alert('Notifikasi diaktifkan! Anda akan mendapat update dari Kopi Ancol.');
            } else {
                alert('Anda menolak notifikasi. Anda bisa mengaktifkannya di pengaturan browser.');
            }
        } else {
            alert('Browser Anda tidak mendukung notifikasi push.');
        }
    });
</script>
@endsection