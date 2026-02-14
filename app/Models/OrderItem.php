<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_id',
        'service_id',
        'service_name',
        'unit_price',
        'quantity',
        'subtotal',
    ];

    /**
     * Each order item belongs to one order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Each order item references one service.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
