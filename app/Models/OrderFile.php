<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderFile extends Model
{
    protected $fillable = [
        'order_id',
        'original_name',
        'path',
        'mime',
        'size',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
