<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number', 'supplier', 'total_amount', 'status', 
        'order_date', 'expected_date', 'received_date', 'notes'
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_date' => 'date',
        'received_date' => 'date',
        'total_amount' => 'decimal:2'
    ];

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id');
    }

    public static function generatePONumber()
    {
        $year = date('Y');
        $month = date('m');
        $lastPO = self::whereYear('order_date', $year)
            ->whereMonth('order_date', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastPO) {
            $lastNumber = intval(substr($lastPO->po_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return 'PO-' . $year . $month . '-' . $newNumber;
    }
}