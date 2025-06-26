<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'sku',
        'name',
        'description',
    ];

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function pricingRules()
    {
        return $this->hasMany(PricingRule::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
