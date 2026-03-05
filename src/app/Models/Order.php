<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'product_id',
        'buyer_id',
        'seller_id',
        'address_id',
        'price',
        'status',
        'payment_method',
        'payment_id',
        'shipped_at',
        'delivered_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'price'        => 'integer',
            'shipped_at'   => 'datetime',
            'delivered_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    const STATUSES = [
        'pending'   => '支払い待ち',
        'paid'      => '支払い完了',
        'shipped'   => '発送済み',
        'delivered' => '受取確認待ち',
        'completed' => '取引完了',
        'cancelled' => 'キャンセル',
    ];

    // リレーション
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TradeMessage::class)->oldest();
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    // ヘルパー
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}
