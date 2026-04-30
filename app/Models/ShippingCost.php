<?php
// app/Models/ShippingCost.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingCost extends Model
{
    protected $fillable = ['courier', 'courier_name', 'destination_id', 'cost', 'service', 'estimated_days'];
    
    protected $casts = [
        'cost' => 'decimal:2'
    ];
}