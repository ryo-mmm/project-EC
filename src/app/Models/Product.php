<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'description',
        'price',
        'brand',
        'size',
        'color',
        'condition',
        'status',
        'sold_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'sold_at' => 'datetime',
        ];
    }

    // 定数
    const CONDITIONS = [
        'new'       => '新品・未使用',
        'like_new'  => '未使用に近い',
        'good'      => '目立った傷や汚れなし',
        'fair'      => 'やや傷や汚れあり',
        'poor'      => '傷や汚れあり',
    ];

    const STATUSES = [
        'draft'     => '下書き',
        'on_sale'   => '販売中',
        'sold_out'  => '売り切れ',
        'suspended' => '出品停止',
    ];

    // リレーション
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function mainImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->orderBy('sort_order');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }

    // スコープ
    public function scopeOnSale($query)
    {
        return $query->where('status', 'on_sale');
    }

    public function scopeLatestProducts($query, int $limit = 20)
    {
        return $query->onSale()->latest()->limit($limit);
    }
}
