<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DineInOrder extends Model
{
    protected $table = 'dine_in_orders';
    
    protected $fillable = [
        'order_number',
        'customer_name',
        'table_number',
        'items',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'notes'
    ];

    protected $casts = [
        'items' => 'array',
        'total_amount' => 'decimal:2'
    ];
    
    protected $attributes = [
        'status' => 'pending',
        'payment_status' => 'pending'
    ];
}