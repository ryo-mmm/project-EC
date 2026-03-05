<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function create(Product $product): View
    {
        abort_if($product->status !== 'on_sale', 404);
        abort_if($product->user_id === Auth::id(), 403, '自分の商品は購入できません。');

        $addresses = Auth::user()->addresses()->get();

        return view('orders.create', compact('product', 'addresses'));
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_if($product->status !== 'on_sale', 422, 'この商品は現在購入できません。');
        abort_if($product->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'address_id'     => ['required', 'exists:addresses,id'],
            'payment_method' => ['required', 'string', 'max:50'],
        ]);

        DB::transaction(function () use ($product, $validated) {
            $order = Order::create([
                'product_id'     => $product->id,
                'buyer_id'       => Auth::id(),
                'seller_id'      => $product->user_id,
                'address_id'     => $validated['address_id'],
                'price'          => $product->price,
                'status'         => 'paid',
                'payment_method' => $validated['payment_method'],
            ]);

            $product->update([
                'status'  => 'sold_out',
                'sold_at' => now(),
            ]);

            $fee = (int) ($product->price * 0.1);
            $order->transaction()->create([
                'seller_id' => $product->user_id,
                'amount'    => $product->price - $fee,
                'fee'       => $fee,
                'status'    => 'pending',
            ]);
        });

        return redirect()->route('mypage.buying')->with('success', '購入が完了しました。');
    }

    public function show(Order $order): View
    {
        $this->authorize('view', $order);

        $order->load(['product.images', 'buyer', 'seller', 'address', 'messages.user']);

        return view('orders.show', compact('order'));
    }

    public function ship(Order $order): RedirectResponse
    {
        $this->authorize('sell', $order);

        abort_if($order->status !== 'paid', 422);

        $order->update([
            'status'    => 'shipped',
            'shipped_at' => now(),
        ]);

        return redirect()->route('orders.show', $order)->with('success', '発送通知を送りました。');
    }

    public function complete(Order $order): RedirectResponse
    {
        $this->authorize('buy', $order);

        abort_if($order->status !== 'shipped', 422);

        $order->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()->route('orders.show', $order)->with('success', '受取が完了しました。取引が終了しました。');
    }
}
