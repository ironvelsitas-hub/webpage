@extends('layouts.admin')

@section('content')
<style>
    .btn-generate {
        background: linear-gradient(135deg, #C49A6C, #A67C52);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 30px;
        font-weight: 600;
    }
    
    .period-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .period-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .period-card.selected {
        border-color: #C49A6C;
        background: #FFF8F0;
    }
    
    .period-icon {
        font-size: 40px;
        color: #C49A6C;
        margin-bottom: 15px;
    }
</style>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus-circle" style="color: #FF6B35;"></i> Buat Laporan Baru
    </h1>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ route('admin.reports.generate') }}">
    @csrf
    <div class="row mb-4">
        <div class="col-12">
            <div class="chart-container">
                <h5 class="mb-3"><i class="fas fa-calendar-alt"></i> Pilih Periode Laporan</h5>
                
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="period-card" onclick="selectPeriod('today')" id="card-today">
                            <div class="period-icon"><i class="fas fa-sun"></i></div>
                            <h6>Hari Ini</h6>
                            <small class="text-muted">{{ date('d/m/Y') }}</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="period-card" onclick="selectPeriod('week')" id="card-week">
                            <div class="period-icon"><i class="fas fa-calendar-week"></i></div>
                            <h6>Minggu Ini</h6>
                            <small class="text-muted">{{ now()->startOfWeek()->format('d/m/Y') }} - {{ now()->endOfWeek()->format('d/m/Y') }}</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="period-card" onclick="selectPeriod('month')" id="card-month">
                            <div class="period-icon"><i class="fas fa-calendar-alt"></i></div>
                            <h6>Bulan Ini</h6>
                            <small class="text-muted">{{ now()->format('F Y') }}</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="period-card" onclick="selectPeriod('year')" id="card-year">
                            <div class="period-icon"><i class="fas fa-calendar-year"></i></div>
                            <h6>Tahun Ini</h6>
                            <small class="text-muted">{{ now()->format('Y') }}</small>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3" id="customPeriodRow" style="display: none;">
                    <div class="col-md-5">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="form-control">
                    </div>
                    <div class="col-md-5">
                        <label>Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end_date" class="form-control">
                    </div>
                    <div class="col-md-2 mt-4">
                        <div class="period-card" onclick="selectPeriod('custom')" id="card-custom" style="padding: 12px;">
                            <small>Terapkan</small>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" name="period" id="selectedPeriod" value="today">
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 text-center">
            <button type="submit" class="btn-generate">
                <i class="fas fa-sync-alt"></i> Generate Laporan
            </button>
        </div>
    </div>
</form>

<script>
    function selectPeriod(period) {
        // Reset semua card
        document.querySelectorAll('.period-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Pilih card yang dipilih
        document.getElementById('card-' + period).classList.add('selected');
        document.getElementById('selectedPeriod').value = period;
        
        // Toggle custom period
        if (period === 'custom') {
            document.getElementById('customPeriodRow').style.display = 'flex';
        } else {
            document.getElementById('customPeriodRow').style.display = 'none';
        }
    }
    
    // Default select today
    selectPeriod('today');
</script>
@endsection