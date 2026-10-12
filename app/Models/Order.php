<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_code',
        'order_date',
        'delivery_time',
        'total_price',
        'status',
        'notes',
        'recipient_name',
        'phone_number',
        'shipping_address',
        'payment_method',
        'payment_status',
        'latitude',
        'longitude',
        'is_read', // Ditambahkan agar dapat disimpan ke database
    ];

    protected $casts = [
        'order_date' => 'date',
        'total_price' => 'decimal:2',
        'is_read' => 'boolean',
    ];

    /**
     * Relationship: Order belongs to User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Order has many order details.
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function reviews()
{
    return $this->hasMany(Review::class, 'order_id');
}
}