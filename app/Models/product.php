<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Relations\HasMany;

class product extends Model
{
    protected $fillable = [
        'name', 
        'description', 
        'price', 
        'stock', 
        'image', 
        'category', 
        'is_active'
    ];
    
    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];
    
    /**
     * Kurangi stok produk
     */
    public function reduceStock($quantity)
    {
        if ($this->stock >= $quantity) {
            $oldStock = $this->stock;
            $this->stock -= $quantity;
            $this->save();
            Log::info("Stock reduced: {$this->name} from {$oldStock} to {$this->stock}");
            return true;
        }
        return false;
    }
    
    /**
     * Tambah stok produk
     */
    public function addStock($quantity)
    {
        $oldStock = $this->stock;
        $this->stock += $quantity;
        $this->save();
        Log::info("Stock added: {$this->name} from {$oldStock} to {$this->stock}");
        return true;
    }
    
    /**
     * Cek apakah stok mencukupi
     */
    public function hasStock($quantity)
    {
        return $this->stock >= $quantity;
    }
    public function allReviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    
    /**
     * Hitung rata-rata rating
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
    
    /**
     * Hitung jumlah review
     */
    public function getRatingCountAttribute()
    {
        return $this->reviews()->count();
    }
}