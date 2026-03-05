<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TradeMessageController extends Controller
{
    public function store(Request $request, Order $order): RedirectResponse
    {
        $this->authorize('view', $order);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $order->messages()->create([
            'user_id' => Auth::id(),
            'body'    => $validated['body'],
        ]);

        return redirect()->route('orders.show', $order);
    }
}
