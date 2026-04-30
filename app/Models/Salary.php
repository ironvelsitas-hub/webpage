<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'month', 'year', 'base_salary', 'allowance', 
        'bonus', 'deduction', 'total_salary', 'status', 'payment_date', 'notes'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'base_salary' => 'decimal:2',
        'allowance' => 'decimal:2',
        'bonus' => 'decimal:2',
        'deduction' => 'decimal:2',
        'total_salary' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper untuk mendapatkan nama bulan
    public function getMonthNameAttribute()
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $months[$this->month];
    }
    
}