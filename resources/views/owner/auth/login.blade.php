<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Owner Login - Kopi Ancol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        
        body {
            background: linear-gradient(145deg, #0f172a 0%, #1e293b 50%, #2d1a11 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Background pattern */
        body::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" opacity="0.05"><path fill="none" d="M20 20 L80 20 L80 80 L20 80 Z" stroke="white" stroke-width="0.5"/><circle cx="50" cy="50" r="15" stroke="white" stroke-width="0.5" fill="none"/></svg>');
            background-size: 30px 30px;
            pointer-events: none;
        }
        
        .login-container {
            max-width: 460px;
            width: 100%;
            margin: 20px;
            position: relative;
            z-index: 1;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 32px;
            padding: 44px 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .logo i {
            font-size: 64px;
            background: linear-gradient(135deg, #c9a87c 0%, #b8860b 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
        }
        
        .logo h2 {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #2d1a11 0%, #4a2c1a 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-top: 12px;
            letter-spacing: -0.5px;
        }
        
        .logo p {
            font-size: 14px;
            color: #64748b;
            margin-top: 6px;
            font-weight: 400;
        }
        
        .owner-badge {
            background: linear-gradient(135deg, #c9a87c, #a0825a);
            color: white;
            padding: 6px 14px;
            border-radius: 40px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            letter-spacing: 0.3px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            margin-top: 8px;
        }
        
        .owner-badge i {
            font-size: 11px;
            color: white !important;
            background: none !important;
            -webkit-background-clip: unset !important;
            background-clip: unset !important;
        }
        
        .form-label {
            font-weight: 600;
            font-size: 13px;
            color: #334155;
            margin-bottom: 8px;
            letter-spacing: 0.3px;
        }
        
        .form-control {
            border-radius: 14px;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            font-size: 14px;
            transition: all 0.2s ease;
            background: #ffffff;
        }
        
        .form-control:focus {
            border-color: #c9a87c;
            box-shadow: 0 0 0 4px rgba(201, 168, 124, 0.15);
            outline: none;
        }
        
        .input-group {
            border-radius: 14px;
            overflow: hidden;
        }
        
        .input-group-text {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-right: none;
            border-radius: 14px 0 0 14px;
            color: #94a3b8;
            padding: 0 16px;
        }
        
        .input-group .form-control {
            border-left: none;
            border-radius: 0 14px 14px 0;
        }
        
        .form-check {
            margin: 20px 0;
        }
        
        .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: 0;
            border: 1.5px solid #cbd5e1;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .form-check-input:checked {
            background-color: #c9a87c;
            border-color: #c9a87c;
        }
        
        .form-check-label {
            font-size: 13px;
            color: #475569;
            margin-left: 8px;
            cursor: pointer;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #c9a87c 0%, #a0825a 100%);
            border: none;
            padding: 14px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 15px;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(185, 140, 70, 0.25);
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(185, 140, 70, 0.35);
            background: linear-gradient(135deg, #d4b48c 0%, #b8926a 100%);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .back-link {
            text-align: center;
            margin-top: 24px;
        }
        
        .back-link a {
            color: #64748b;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: color 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .back-link a:hover {
            color: #c9a87c;
        }
        
        .alert {
            border-radius: 14px;
            font-size: 13px;
            padding: 14px 18px;
            border: none;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-danger {
            background: #fef2f2;
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }
        
        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border-left: 4px solid #16a34a;
        }
        
        .alert i {
            font-size: 16px;
        }
        
        .security-note {
            background: #f8fafc;
            border-radius: 14px;
            padding: 14px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            margin-top: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 1px solid #e2e8f0;
        }
        
        .security-note i {
            color: #22c55e;
            font-size: 13px;
        }
        
        @media (max-width: 576px) {
            .login-card {
                padding: 32px 24px;
            }
            
            .logo h2 {
                font-size: 24px;
            }
            
            .logo i {
                font-size: 52px;
            }
        }
        
        /* Animasi loading untuk button */
        .btn-login:disabled {
            opacity: 0.7;
            transform: none;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c9a87c;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo">
                <i class="fas fa-crown"></i>
                <h2>Owner Area</h2>
                <p>Panel khusus pemilik Kopi Ancol</p>
                <span class="owner-badge">
                    <i class="fas fa-lock"></i> Akses Terbatas
                </span>
            </div>
            
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif
            
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('owner.login.post') }}">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-semibold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" name="email" class="form-control" 
                               placeholder="owner@kopiancol.com" required autofocus>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="password" class="form-control" 
                               placeholder="••••••••" required>
                    </div>
                </div>
                
                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Ingat Saya</label>
                </div>
                
                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt"></i> Masuk ke Dashboard Owner
                </button>
            </form>
            
            <div class="back-link">
                <a href="{{ route('shop.index') }}">
                    <i class="fas fa-arrow-left"></i> Kembali ke Website
                </a>
            </div>
            
            <div class="security-note">
                <i class="fas fa-shield-alt"></i> Halaman ini hanya untuk akses pemilik Kopi Ancol
            </div>
        </div>
    </div>
</body>
</html>