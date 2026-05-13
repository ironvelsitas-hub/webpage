<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline - Kopi Ancol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f2f2, #c3cfe2);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }
        
        .offline-container {
            text-align: center;
            padding: 40px 20px;
        }
        
        .offline-icon {
            font-size: 80px;
            color: #FF6B35;
            margin-bottom: 20px;
        }
        
        .offline-title {
            font-size: 28px;
            font-weight: 700;
            color: #2C1810;
            margin-bottom: 15px;
        }
        
        .offline-message {
            color: #6c757d;
            margin-bottom: 30px;
        }
        
        .btn-reload {
            background: linear-gradient(135deg, #FF6B35, #FF8C42);
            border: none;
            padding: 12px 30px;
            border-radius: 30px;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-reload:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,107,53,0.3);
        }
        
        .features-list {
            margin-top: 40px;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .feature-item {
            background: white;
            border-radius: 12px;
            padding: 15px;
            width: 150px;
            text-align: center;
        }
        
        .feature-item i {
            font-size: 30px;
            color: #C49A6C;
            margin-bottom: 10px;
        }
        
        .feature-item span {
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="offline-container">
        <div class="offline-icon">
            <i class="fas fa-wifi-slash"></i>
        </div>
        <h1 class="offline-title">Anda Offline</h1>
        <p class="offline-message">Koneksi internet Anda terputus. <br>Silakan periksa kembali koneksi Anda.</p>
        <button onclick="location.reload()" class="btn-reload">
            <i class="fas fa-sync-alt"></i> Coba Lagi
        </button>
        
        <div class="features-list">
            <div class="feature-item">
                <i class="fas fa-mug-hot"></i>
                <span>Menu Kopi</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-tag"></i>
                <span>Promo Terbatas</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-phone-alt"></i>
                <span>Hubungi CS</span>
            </div>
        </div>
    </div>
</body>
</html>