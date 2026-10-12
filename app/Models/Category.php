<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    // Tambahkan 'description' di sini agar diizinkan masuk ke database
    protected $fillable = [
        'name',
        'slug',
        'description', 
    ];

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class, 'category_id');
    }
}