<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kopi Ancol - Owner Dashboard</title>
    
    <!-- Bootstrap & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #eddbee;
            min-height: 100vh;
        }
        
        /* Owner Navbar - Lebih Modern */
        .owner-navbar {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 0.8rem 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            backdrop-filter: blur(10px);
        }
        
        .owner-navbar .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: -0.3px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .owner-navbar .navbar-brand i {
            background: none;
            -webkit-text-fill-color: #f59e0b;
            color: #f59e0b;
            margin-right: 8px;
        }
        
        .owner-navbar .nav-link {
            color: #cbd5e1 !important;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .owner-navbar .nav-link:hover {
            color: #f59e0b !important;
            transform: translateY(-2px);
        }
        
        /* Sidebar Owner - Lebih Modern */
        .owner-sidebar {
            background: #eddbee;
            min-height: calc(100vh - 64px);
            box-shadow: 2px 0 20px rgba(0,0,0,0.05);
            border-right: 1px solid #eef2ff;
        }
        
        .owner-sidebar .nav-link {
            color: #334155;
            padding: 12px 18px;
            border-radius: 12px;
            margin: 4px 12px;
            transition: all 0.25s ease;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .owner-sidebar .nav-link:hover {
            background: #fef3c7;
            color: #d97706;
            transform: translateX(4px);
        }
        
        .owner-sidebar .nav-link.active {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }
        
        .owner-sidebar .nav-link i {
            width: 28px;
            margin-right: 12px;
            font-size: 1.1rem;
            text-align: center;
        }
        
        /* Profil Section di Sidebar */
        .owner-sidebar .profile-section {
            text-align: center;
            padding: 20px 0 15px;
            border-bottom: 1px solid #eef2ff;
            margin-bottom: 15px;
        }
        
        .owner-sidebar .avatar {
            width: 65px;
            height: 65px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.25);
        }
        
        .owner-sidebar .avatar i {
            font-size: 28px;
            color: white;
        }
        
        .owner-sidebar .profile-name {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 2px;
            font-size: 0.95rem;
        }
        
        .owner-sidebar .profile-role {
            font-size: 0.7rem;
            color: #f59e0b;
            background: #fef3c7;
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
        }
        
        /* Main Content */
        .owner-content {
            padding: 24px 28px;
            min-height: calc(100vh - 64px);
            background: #f0f2f5;
        }
        
        /* Footer */
        .owner-footer {
            background: #ffffff;
            color: #64748b;
            padding: 16px;
            text-align: center;
            font-size: 0.75rem;
            border-top: 1px solid #eef2ff;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.02);
        }
        
        /* Scrollbar Custom */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #e2e8f0;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #f59e0b;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .owner-navbar {
                padding: 0.6rem 1rem;
            }
            .owner-content {
                padding: 16px;
            }
            .owner-sidebar .nav-link {
                padding: 8px 12px;
                margin: 2px 8px;
                font-size: 0.8rem;
            }
        }
        
        /* Card Style Global untuk Owner Panel */
        .owner-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: all 0.25s ease;
        }
        
        .owner-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.05);
            transform: translateY(-2px);
        }
        
        /* Tombol Global */
        .btn-owner-primary {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border: none;
            color: white;
            font-weight: 600;
            padding: 8px 20px;
            border-radius: 12px;
            transition: all 0.25s ease;
        }
        
        .btn-owner-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(245, 158, 11, 0.35);
            color: white;
        }
        
        /* Badge Status */
        .badge-owner {
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        /* Tabel Modern */
        .table-owner {
            border-collapse: separate;
            border-spacing: 0 8px;
        }
        
        .table-owner thead th {
            background: transparent;
            color: #64748b;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            border: none;
        }
        
        .table-owner tbody tr {
            background: white;
            border-radius: 16px;
            transition: all 0.2s;
        }
        
        .table-owner tbody tr:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transform: scale(1.01);
        }
        
        .table-owner tbody td {
            padding: 14px 16px;
            border: none;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <!-- Owner Navbar -->
    <nav class="owner-navbar navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('owner.dashboard') }}">
                <i class="fas fa-crown"></i> Kopi Ancol | Owner Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#ownerNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="ownerNavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shop.index') }}" target="_blank">
                            <i class="fas fa-globe"></i> Lihat Website
                        </a>
                    </li>
                    <a class="nav-link {{ request()->routeIs('owner.reports*') ? 'active' : '' }}" href="{{ route('owner.reports') }}">
                        <i class="fas fa-file-alt"></i> Laporan Masuk
                        <span id="sidebarReportBadge" class="badge bg-danger float-end" style="display: none;">0</span>
                        </a>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('owner.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Owner -->
            <div class="col-md-2 p-0">
                <div class="owner-sidebar">
                    <div class="profile-section">
                        <div class="avatar">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="profile-name">{{ Auth::user()->name }}</div>
                        <div class="profile-role">Pemilik</div>
                    </div>
                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}" href="{{ route('owner.dashboard') }}">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('owner.orders') ? 'active' : '' }}" href="{{ route('owner.orders') }}">
                            <i class="fas fa-shopping-cart"></i> Semua Pesanan
                        </a>
                        <a class="nav-link {{ request()->routeIs('owner.products') ? 'active' : '' }}" href="{{ route('owner.products') }}">
                            <i class="fas fa-coffee"></i> Manajemen Produk
                        </a>
                        <a class="nav-link {{ request()->routeIs('owner.stock') ? 'active' : '' }}" href="{{ route('owner.stock') }}">
                            <i class="fas fa-boxes"></i> Manajemen Stok
                        </a>
                        <a class="nav-link {{ request()->routeIs('owner.staff') ? 'active' : '' }}" href="{{ route('owner.staff') }}">
                            <i class="fas fa-users"></i> Karyawan
                        </a>
                        <a class="nav-link {{ request()->routeIs('owner.promos') ? 'active' : '' }}" href="{{ route('owner.promos') }}">
                            <i class="fas fa-tags"></i> Promo & Voucher
                        </a>
                        <a class="nav-link {{ request()->routeIs('owner.financial') ? 'active' : '' }}" href="{{ route('owner.financial') }}">
                            <i class="fas fa-chart-line"></i> Laporan Keuangan
                        </a>
                        <a class="nav-link {{ request()->routeIs('owner.salaries*') ? 'active' : '' }}" href="{{ route('owner.salaries') }}">
                            <i class="fas fa-money-bill-wave"></i> Gaji Karyawan
                        </a>
                        <a class="nav-link {{ request()->routeIs('owner.raw-materials') ? 'active' : '' }}" href="{{ route('owner.raw-materials') }}">
                            <i class="fas fa-boxes"></i> Bahan Baku
                        </a>
                        <a class="nav-link {{ request()->routeIs('owner.purchase-orders') ? 'active' : '' }}" href="{{ route('owner.purchase-orders') }}">
                            <i class="fas fa-shopping-cart"></i> Pembelian
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 p-0">
                <div class="owner-content">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <footer class="owner-footer">
        <div class="container-fluid">
            &copy; {{ date('Y') }} Kopi Ancol - Owner Panel. All rights reserved.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>
</html>