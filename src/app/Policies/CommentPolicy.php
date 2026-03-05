<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function delete(User $user, Comment $comment): bool
    {
        // コメント投稿者 または 商品の出品者が削除可能
        return $user->id === $comment->user_id
            || $user->id === $comment->product->user_id;
    }
}
