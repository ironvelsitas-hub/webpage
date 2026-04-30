<?php
// app/Models/Branch.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'address',
        'phone',
        'email',
        'latitude',
        'longitude',
        'image',
        'description',
        'open_time',
        'close_time',
        'is_active',
        'order_position'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_position' => 'integer'
    ];

    // Accessor untuk jam operasional
    public function getOperatingHoursAttribute()
    {
        return $this->open_time . ' - ' . $this->close_time . ' WIB';
    }

    // Accessor untuk status buka/tutup
    public function getIsOpenAttribute()
    {
        $now = now()->format('H:i');
        return $now >= $this->open_time && $now <= $this->close_time;
    }

    // Accessor untuk URL Google Maps
    public function getGoogleMapsUrlAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
        }
        return "https://www.google.com/maps/search/?api=1&query=" . urlencode($this->address);
    }

    // Accessor untuk embed Google Maps
    public function getGoogleMapsEmbedAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps/embed/v1/place?key=YOUR_GOOGLE_MAPS_API_KEY&q={$this->latitude},{$this->longitude}";
        }
        return null;
    }
}