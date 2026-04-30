<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Kopi Ancol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            margin: 20px;
        }
        .login-header {
            background: linear-gradient(135deg, #2C1810 0%, #4A2C1A 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }
        .login-header i {
            font-size: 60px;
            margin-bottom: 15px;
        }
        .login-header h3 {
            font-weight: 700;
            margin-bottom: 5px;
        }
        .login-header p {
            opacity: 0.9;
            font-size: 14px;
        }
        .login-body {
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
        .btn-login {
            background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 100%);
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,107,53,0.3);
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #6c757d;
            text-decoration: none;
            font-size: 14px;
        }
        .back-link a:hover {
            color: #FF6B35;
        }
        .alert-custom {
            border-radius: 10px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="fas fa-user-shield"></i>
            <h3>Admin Panel</h3>
            <p>Login untuk mengelola toko</p>
        </div>
        <div class="login-body">
            @if(session('error'))
                <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>
                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login as Admin
                </button>
            </form>
            
            <div class="back-link">
                <a href="{{ route('shop.index') }}"><i class="fas fa-arrow-left"></i> Kembali ke Toko</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>