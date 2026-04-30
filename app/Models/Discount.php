<?php
// app/Models/Discount.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'type', 'value', 'min_purchase', 'max_discount',
        'usage_limit', 'used_count', 'per_user_limit', 'start_date', 
        'end_date', 'is_active', 'description'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2'
    ];

    // Cek apakah diskon masih valid
    public function isValid()
    {
        $now = Carbon::now();
        
        return $this->is_active &&
               $now >= $this->start_date &&
               $now <= $this->end_date &&
               ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }

    // Hitung diskon berdasarkan subtotal
    public function calculateDiscount($subtotal)
    {
        if (!$this->isValid() || $subtotal < $this->min_purchase) {
            return 0;
        }

        if ($this->type === 'percentage') {
            $discount = $subtotal * ($this->value / 100);
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
            return $discount;
        } else {
            return min($this->value, $subtotal);
        }
    }

    // Relasi ke orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}