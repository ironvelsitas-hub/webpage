<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RawMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category', 'unit', 'stock', 'min_stock', 
        'unit_price', 'supplier', 'description', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'unit_price' => 'decimal:2'
    ];

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock <= 0) return ['class' => 'danger', 'text' => 'Habis'];
        if ($this->stock <= $this->min_stock) return ['class' => 'warning', 'text' => 'Menipis'];
        return ['class' => 'success', 'text' => 'Aman'];
    }
}