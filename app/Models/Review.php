<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews'; 

    protected $fillable = [
        'user_id',
        'order_id',
        'rating',
        'comment',
        'admin_reply', // Ditambahkan agar kolom balasan admin bisa disimpan
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}