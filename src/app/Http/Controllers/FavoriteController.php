<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store(Product $product): RedirectResponse
    {
        Auth::user()->favorites()->firstOrCreate(['product_id' => $product->id]);

        return back()->with('success', 'お気に入りに追加しました。');
    }

    public function destroy(Product $product): RedirectResponse
    {
        Auth::user()->favorites()->where('product_id', $product->id)->delete();

        return back()->with('success', 'お気に入りを解除しました。');
    }
}
