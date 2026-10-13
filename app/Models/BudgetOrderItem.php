<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetOrderItem extends Model
{
    use HasFactory;

    protected $table = 'budget_order_items';

    protected $fillable = [
        'budget_order_id',
        'package_number',
        'menu_id',
        'menu_name',
        'menu_category',
        'menu_price',
    ];

    protected $casts = [
        'package_number' => 'integer',
        'menu_price'     => 'decimal:2',
    ];

    /**
     * Relasi ke pesanan induk (BudgetOrder).
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(BudgetOrder::class, 'budget_order_id');
    }

    /**
     * Relasi ke menu (opsional jika menu masih tersimpan di DB).
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
