<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'menu_id',
        'package_id',
        'qty',
        'price',
        'total'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    /**
     * Relationship: OrderDetail belongs to Order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationship: OrderDetail belongs to Menu.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class)->withTrashed();
    }

    /**
     * Relationship: OrderDetail belongs to Package.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
