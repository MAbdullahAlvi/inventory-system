<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingRule extends Model
{
    protected $fillable = [
        'product_id',
        'type',
        'discount_percentage',
        'min_quantity',
        'start_time',
        'end_time',
        'days',
        'precedence',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

