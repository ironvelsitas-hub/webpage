<?php
// app/Models/Province.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class province extends Model
{
    protected $fillable = ['name', 'code'];
    
    public function regencies()
    {
        return $this->hasMany(Regency::class);
    }
}