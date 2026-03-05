<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'order_id',
        'seller_id',
        'amount',
        'fee',
        'status',
        'transferred_at',
    ];

    protected function casts(): array
    {
        return [
            'amount'         => 'integer',
            'fee'            => 'integer',
            'transferred_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
