<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'status',
        'total_price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function files()
    {
        return $this->hasMany(\App\Models\OrderFile::class);
    }

    public function recomputeTotal(): void
    {
        $total = $this->items()->sum('subtotal');
        $this->update(['total_price' => $total]);
    }
}
