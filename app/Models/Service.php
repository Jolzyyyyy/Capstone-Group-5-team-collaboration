<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'category',
        'retail_price',
        'bulk_price',
        'unit',
        'description',
        'image_path',
        'is_active',
    ];

    protected $casts = [
        'retail_price' => 'decimal:2',
        'bulk_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function variations()
    {
        return $this->hasMany(ServiceVariation::class);
    }

    public function activeVariations()
    {
        return $this->hasMany(ServiceVariation::class)
            ->where('is_active', true);
    }
}