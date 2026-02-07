<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
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

    /**
     * A service can appear in many order items.
     * (Shopee / Amazon style)
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
