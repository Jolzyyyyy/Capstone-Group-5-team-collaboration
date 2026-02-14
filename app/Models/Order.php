<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields.
     */
    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'status',
        'total_price',
    ];

    /**
     * An order belongs to a user (customer account).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * An order has many items.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Recompute total_price based on items.
     * (Useful for admin recalculation if needed.)
     */
    public function recomputeTotal(): void
    {
        $total = $this->items()->sum('subtotal');
        $this->update(['total_price' => $total]);
    }
}
