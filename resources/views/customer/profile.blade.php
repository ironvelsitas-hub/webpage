@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Menu Customer</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('customer.dashboard') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="{{ route('customer.orders') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-shopping-cart"></i> Pesanan Saya
                    </a>
                    <a href="{{ route('customer.profile') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-user-edit"></i> Profil Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="list-group-item list-group-item-action text-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Profil Saya</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    <form method="POST" action="{{ route('customer.profile.update') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" readonly disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" class="form-control" rows="3">{{ old('address', $user->address) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Profil</button>
                    </form>
                    
                    <hr>
                    
                    <h5>Ubah Password</h5>
                    <form method="POST" action="{{ route('customer.password.update') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Password Saat Ini</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning">Ubah Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection