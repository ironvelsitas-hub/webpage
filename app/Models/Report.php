<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'type', 'period_start', 'period_end',
        'total_revenue', 'total_expense', 'net_profit', 'total_orders',
        'data', 'created_by', 'status'
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'data' => 'array',
        'total_revenue' => 'decimal:2',
        'total_expense' => 'decimal:2',
        'net_profit' => 'decimal:2'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}