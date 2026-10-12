<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageDetail extends Model
{
    protected $fillable = ['package_id', 'menu_id'];

    /**
     * Relationship: PackageDetail belongs to Package.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Relationship: PackageDetail belongs to Menu.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}
