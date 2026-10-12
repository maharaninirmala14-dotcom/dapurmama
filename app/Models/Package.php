<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    /**
     * Relationship: Package belongs to many Menus (pivot: package_details).
     */
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'package_details');
    }

    /**
     * Relationship: Package has many package details.
     */
    public function packageDetails(): HasMany
    {
        return $this->hasMany(PackageDetail::class);
    }

    /**
     * Relationship: Package has many order details.
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}
