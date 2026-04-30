<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    protected $table = 'orders';
    
    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'items',
        'total_amount',
        'payment_method',
        'payment_status',
        'status',
        'delivery_status',
        'confirmed_at',
        'notes'
    ];

    protected $casts = [
        'items' => 'array',
        'total_amount' => 'decimal:2',
        'confirmed_at' => 'datetime'
    ];
    
    protected $attributes = [
        'payment_status' => 'pending',
        'status' => 'pending',
        'delivery_status' => 'pending'
    ];
    
    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">🟡 Pending</span>',
            'processing' => '<span class="badge bg-info">🔵 Processing</span>',
            'completed' => '<span class="badge bg-success">✅ Completed</span>',
            'cancelled' => '<span class="badge bg-danger">❌ Cancelled</span>'
        ];
        
        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
    
    // Accessor untuk payment badge
    public function getPaymentBadgeAttribute()
    {
        $badges = [
            'unpaid' => '<span class="badge bg-danger">💳 Unpaid</span>',
            'paid' => '<span class="badge bg-success">✅ Paid</span>',
            'pending' => '<span class="badge bg-warning">⏳ Pending</span>'
        ];
        
        return $badges[$this->payment_status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
    
    // Accessor untuk delivery badge
    public function getDeliveryBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-secondary">⏳ Pending</span>',
            'shipped' => '<span class="badge bg-info">🚚 Shipped</span>',
            'delivered' => '<span class="badge bg-success">✅ Delivered</span>'
        ];
        
        return $badges[$this->delivery_status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
    
    // Accessor untuk formatted date
    public function getFormattedDateAttribute()
    {
        return $this->created_at ? $this->created_at->format('d/m/Y H:i:s') : '-';
    }
    
    // Accessor untuk formatted confirmed date
    public function getFormattedConfirmedDateAttribute()
    {
        return $this->confirmed_at ? $this->confirmed_at->format('d/m/Y H:i:s') : '-';
    }
    
    // Scope untuk pesanan pending
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    // Scope untuk pesanan processing
    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }
    
    // Scope untuk pesanan completed
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    
    // Scope untuk pesanan yang sudah dikonfirmasi
    public function scopeConfirmed($query)
    {
        return $query->whereNotNull('confirmed_at');
    }
    
    // Scope untuk pesanan yang belum dikonfirmasi
    public function scopeUnconfirmed($query)
    {
        return $query->whereNull('confirmed_at');
    }
    
    // Cek apakah pesanan sudah dikonfirmasi
    public function isConfirmed()
    {
        return !is_null($this->confirmed_at);
    }
    
    // Cek apakah pesanan sudah dibayar
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }
    
    // Cek apakah pesanan sudah selesai
    public function isCompleted()
    {
        return $this->status === 'completed';
    }
    
}