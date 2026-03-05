<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:500'],
        ]);

        $product->comments()->create([
            'user_id' => Auth::id(),
            'body'    => $validated['body'],
        ]);

        return redirect()->route('products.show', $product)->with('success', 'コメントを投稿しました。');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);

        $product = $comment->product;
        $comment->delete();

        return redirect()->route('products.show', $product)->with('success', 'コメントを削除しました。');
    }
}
