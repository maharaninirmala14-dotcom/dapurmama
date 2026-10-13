<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class BudgetOrder extends Model
{
    use HasFactory;

    protected $table = 'budget_orders';

    protected $fillable = [
        'order_number',
        'customer_name',
        'total_budget',
        'package_quantity',
        'average_budget_per_package',
        'total_price',
        'remaining_budget',
        'status',
        'notes',
    ];

    protected $casts = [
        'total_budget'               => 'decimal:2',
        'package_quantity'           => 'integer',
        'average_budget_per_package' => 'decimal:2',
        'total_price'                => 'decimal:2',
        'remaining_budget'           => 'decimal:2',
    ];

    /**
     * Relasi ke seluruh item menu pada pesanan.
     */
    public function items(): HasMany
    {
        return $this->hasMany(BudgetOrderItem::class, 'budget_order_id');
    }

    /**
     * Dapatkan item-item yang dikelompokkan per nomor paket.
     *
     * @return Collection<int, Collection<int, BudgetOrderItem>>
     */
    public function getPackagesAttribute(): Collection
    {
        return $this->items->groupBy('package_number');
    }

    /**
     * Generate nomor pesanan unik baru (contoh: ORD-BGT-20261012-0001).
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD-BGT-' . date('Ymd') . '-';
        $lastOrder = static::where('order_number', 'LIKE', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        $nextSeq = 1;
        if ($lastOrder) {
            $lastSeq = (int) substr($lastOrder->order_number, -4);
            $nextSeq = $lastSeq + 1;
        }

        return $prefix . str_pad((string) $nextSeq, 4, '0', STR_PAD_LEFT);
    }
}
