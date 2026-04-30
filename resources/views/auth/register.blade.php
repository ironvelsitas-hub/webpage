<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Kopi Ancol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            padding: 40px 0;
        }
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            width: 100%;
            max-width: 500px;
            margin: 20px;
        }
        .register-header {
            background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }
        .register-header i {
            font-size: 60px;
            margin-bottom: 15px;
        }
        .register-header h3 {
            font-weight: 700;
            margin-bottom: 5px;
        }
        .register-header p {
            opacity: 0.9;
            font-size: 14px;
        }
        .register-body {
            padding: 30px;
        }
        .form-control {
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #FF6B35;
            box-shadow: 0 0 0 0.2rem rgba(255,107,53,0.25);
        }
        .btn-register {
            background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 100%);
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,107,53,0.3);
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        .login-link a {
            color: #FF6B35;
            text-decoration: none;
            font-weight: 600;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .back-link {
            text-align: center;
            margin-top: 15px;
        }
        .back-link a {
            color: #6c757d;
            text-decoration: none;
            font-size: 14px;
        }
        .back-link a:hover {
            color: #FF6B35;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="register-header">
            <i class="fas fa-user-plus"></i>
            <h3>Daftar Akun</h3>
            <p>Bergabung dengan Kopi Ancol</p>
        </div>
        <div class="register-body">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nomor Telepon</label>
                    <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-register">
                    <i class="fas fa-user-plus"></i> Daftar
                </button>
            </form>
            
            <div class="login-link">
                <p>Sudah punya akun? <a href="{{ route('login') }}">Login Sekarang</a></p>
            </div>
            <div class="back-link">
                <a href="{{ route('shop.index') }}"><i class="fas fa-arrow-left"></i> Kembali ke Toko</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>