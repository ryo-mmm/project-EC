<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    // 購入者・出品者のどちらかが閲覧可能
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->buyer_id
            || $user->id === $order->seller_id;
    }

    // 出品者のみ発送操作可能
    public function sell(User $user, Order $order): bool
    {
        return $user->id === $order->seller_id;
    }

    // 購入者のみ受取完了操作可能
    public function buy(User $user, Order $order): bool
    {
        return $user->id === $order->buyer_id;
    }
}
