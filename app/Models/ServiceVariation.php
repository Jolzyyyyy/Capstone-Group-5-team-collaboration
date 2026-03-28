<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceVariation extends Model
{
    protected $fillable = [
        'service_id',
        'service_item_id',
        'printing_category',
        'color_mode',
        'product_size',
        'finish_type',
        'package_type',
        'retail_price',
        'bulk_price',
        'is_active',
    ];

    protected $casts = [
        'retail_price' => 'decimal:2',
        'bulk_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getVariationLabelAttribute(): string
    {
        return collect([
            $this->printing_category,
            $this->color_mode,
            $this->product_size,
            $this->finish_type,
            $this->package_type,
        ])->filter()->implode(' / ');
    }
}