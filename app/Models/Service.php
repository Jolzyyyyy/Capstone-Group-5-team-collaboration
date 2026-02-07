<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name','category','retail_price','bulk_price','unit','description','image_path','is_active'
    ];
}