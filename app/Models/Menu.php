<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'is_favorite'
    ];

    protected $casts = [
        'is_favorite' => 'boolean',
        'price' => 'decimal:2'
    ];

    /**
     * Relationship: Menu belongs to Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: Menu is in many package details.
     */
    public function packageDetails(): HasMany
    {
        return $this->hasMany(PackageDetail::class);
    }

    /**
     * Relationship: Menu has many order details.
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}
