<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Kopi Ancol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        .sidebar {
            background: linear-gradient(135deg, rgb(128, 60, 21) 0%, #e2600f 100%);
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: relative;
        }
        
        .sidebar .nav-link {
            color: #D7CCC8;
            padding: 12px 20px;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: #FFCC80;
            padding-left: 25px;
        }
        
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            border-left-color: #FF6B35;
            color: #FFCC80;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .notification-badge {
            position: absolute;
            top: 8px;
            right: 15px;
            background: #FF6B35;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .sidebar-footer {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            padding: 15px;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 20px;
        }
        
        .sidebar-footer .nav-link {
            text-align: center;
            padding: 10px;
        }
        
        .sidebar-menu {
            flex: 1;
        }
        
        .sidebar-wrapper {
            display: flex;
            flex-direction: column;
            height: 100%;
            min-height: 100vh;
        }
        
        .sidebar-nav {
            flex: 1;
        }
        
        /* Logout button style */
        .logout-btn {
            color: #D7CCC8;
            padding: 12px 20px;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            width: 100%;
            text-align: left;
            background: transparent;
            border: none;
            cursor: pointer;
        }
        .logout-btn:hover {
            background: rgba(255,255,255,0.1);
            color: #FFCC80;
            padding-left: 25px;
        }
        .logout-btn i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-mug-hot"></i> Kopi Ancol Admin
            </a>
            <div class="d-flex">
                <a href="{{ route('shop.index') }}" class="btn btn-outline-light me-2">
                    <i class="fas fa-store"></i> View Store
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-md-block sidebar" style="min-height: 100vh;">
                <div class="sidebar-wrapper">
                    <div class="text-center pt-3 pb-2">
                        <h5 class="text-white">Kopi Ancol</h5>
                        <small class="text-muted">admin dashboard</small>
                        <hr class="bg-white opacity-25 mx-3">
                    </div>
                    
                    <ul class="nav flex-column sidebar-nav">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}" href="{{ route('admin.products') }}">
                                <i class="fas fa-box"></i> Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}" href="{{ route('admin.orders') }}">
                                <i class="fas fa-shopping-cart"></i> Orders
                            </a>
                        </li>
                        <li class="nav-item position-relative">
                            <a class="nav-link {{ request()->routeIs('admin.dinein*') ? 'active' : '' }}" href="{{ route('admin.dinein') }}">
                                <i class="fas fa-utensils"></i> Dine In Orders
                                @php
                                    $pendingDineIn = \App\Models\DineInOrder::where('status', 'pending')->count();
                                @endphp
                                @if($pendingDineIn > 0)
                                    <span class="notification-badge">{{ $pendingDineIn }}</span>
                                @endif
                            </a>
                        </li>
                        <!-- ==================== MENU CHAT CUSTOMER ==================== -->
                        <li class="nav-item position-relative">
                            <a class="nav-link {{ request()->routeIs('admin.chat*') ? 'active' : '' }}" href="{{ route('admin.chat.index') }}">
                                <i class="fas fa-comments"></i> Chat Customer
                                @php
                                    $unreadChatCount = \App\Models\ChatMessage::where('sender_type', 'customer')->where('is_read', false)->count();
                                @endphp
                                @if($unreadChatCount > 0)
                                    <span class="notification-badge">{{ $unreadChatCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">
                            <i class="fas fa-star"></i> Reviews
                            @php
                                $pendingReviews = \App\Models\Review::where('status', 'pending')->count();
                            @endphp
                            @if($pendingReviews > 0)
                                <span class="notification-badge">{{ $pendingReviews }}</span>
                            @endif
                        </a>
                <div class="nam-item">
                    <small>
                        <a href="{{ route('owner.login') }}" class="nav-link">
                            <i class="fas fa-star"></i> Area Pemilik
                        </a>
                    </small>
                </div>
                <a class="nav-link" href="{{ route('admin.reports.index') }}">
                    <i class="fas fa-file-alt"></i> Laporan
                </a>

                    </li>
                        <!-- ========================================================= -->
                    </ul>
                    
                    <!-- Sidebar Footer - View Store dan Logout -->
                    <div class="sidebar-footer">
                        <hr class="d-inline w-100">
                        <a href="{{ route('shop.index') }}" class="nav-item" target="_blank">
                            <i class="fas fa-store"></i> View Store
                        </a>
                        <!-- ==================== LOGOUT BUTTON ==================== -->
                        <form method="POST" action="{{ route('admin.logout') }}" class="d-inline w-100">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                        <!-- ======================================================= -->
                    </div>
                </div>
            </nav>

            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="pt-3 pb-2 mb-3 border-bottom">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto refresh notifikasi setiap 30 detik
        setInterval(function() {
            location.reload();
        }, 30000);
    </script>
    @stack('scripts')
</body>
</html>