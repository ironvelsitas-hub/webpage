<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * Cek apakah user adalah admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    /**
     * Cek apakah user adalah customer
     */
    public function isCustomer()
    {
        return $this->role === 'customer';
    }
    
    /**
     * Relasi dengan chat messages
     */
    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }
    
    /**
     * Cek apakah user adalah owner
     */
    public function isOwner()
    {
        return $this->role === 'owner';
    }
    
    /**
     * Cek apakah user adalah staff
     */
    public function isStaff()
    {
        return in_array($this->role, ['staff', 'kasir', 'barista', 'admin']);
    }
    
    /**
     * Get all available roles
     */
    public static function getRoles()
    {
        return [
            'customer' => 'Customer',
            'admin' => 'Admin',
            'staff' => 'Staff',
            'kasir' => 'Kasir',
            'barista' => 'Barista',
            'owner' => 'Owner'
        ];
    }
}