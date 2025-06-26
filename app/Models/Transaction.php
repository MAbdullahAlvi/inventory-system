<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'type',
        'product_id',
        'quantity',
        'final_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }
}
