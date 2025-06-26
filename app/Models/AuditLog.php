<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'transaction_id',
        'action',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}

