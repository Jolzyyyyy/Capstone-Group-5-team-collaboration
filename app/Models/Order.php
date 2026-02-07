<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'customer_name',
        'customer_email',
        'service_type',   // can be kept for now (legacy / summary)
        'quantity',       // can be kept for now (legacy / summary)
        'status',
        'total_price',
        'file_path',
    ];

    /**
     * An order has many order items.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
