<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline - Kopi Ancol</title>
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        body {
            background: linear-gradient(135deg, #2C1810 0%, #4A2C1A 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin: 0;
            padding: 20px;
        }
        .offline-container {
            text-align: center;
            max-width: 400px;
        }
        .coffee-icon {
            font-size: 80px;
            margin-bottom: 20px;
            color: #FF6B35;
        }
        h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        p {
            opacity: 0.8;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .retry-btn {
            background: #FF6B35;
            border: none;
            padding: 12px 30px;
            border-radius: 30px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .retry-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,107,53,0.3);
        }
    </style>
</head>
<body>
    <div class="offline-container">
        <div class="coffee-icon">
            <i class="fas fa-mug-hot"></i>
        </div>
        <h1>Anda Offline</h1>
        <p>Sepertinya Anda tidak terhubung ke internet. Silakan periksa koneksi Anda dan coba lagi.</p>
        <button class="retry-btn" onclick="location.reload()">
            <i class="fas fa-sync-alt"></i> Coba Lagi
        </button>
    </div>
</body>
</html>