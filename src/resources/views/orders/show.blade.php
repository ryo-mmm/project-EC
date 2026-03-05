@extends('layouts.app')
@section('title', '取引詳細')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- ステータスバー --}}
    @php
        $statuses = \App\Models\Order::STATUSES;
        $flow = ['paid', 'shipped', 'delivered', 'completed'];
        $currentIndex = array_search($order->status, $flow);
    @endphp
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-4">
        <div class="flex items-center justify-between mb-2">
            <h1 class="font-bold text-gray-800">取引詳細</h1>
            <span class="text-xs bg-ocean-100 text-ocean-700 px-3 py-1 rounded-full font-medium">
                {{ $statuses[$order->status] ?? $order->status }}
            </span>
        </div>
        @if($order->status !== 'cancelled')
            <div class="flex items-center gap-1 mt-3">
                @foreach($flow as $i => $step)
                    <div class="flex-1 h-1.5 rounded-full {{ $currentIndex !== false && $i <= $currentIndex ? 'bg-ocean-500' : 'bg-gray-200' }}"></div>
                @endforeach
            </div>
            <div class="flex justify-between text-xs text-gray-400 mt-1">
                <span>支払い完了</span><span>発送済み</span><span>受取確認</span><span>取引完了</span>
            </div>
        @endif
    </div>

    {{-- 商品情報 --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4">
        <div class="flex gap-3">
            <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                @if($order->product->images->isNotEmpty())
                    <img src="{{ Storage::url($order->product->images->first()->image_path) }}" alt="" class="w-full h-full object-cover">
                @endif
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-gray-800">{{ $order->product->name }}</p>
                <p class="text-base font-bold text-ocean-700 mt-0.5">¥{{ number_format($order->price) }}</p>
            </div>
        </div>
    </div>

    {{-- アクションボタン --}}
    @if($order->status === 'paid' && Auth::id() === $order->seller_id)
        <form method="POST" action="{{ route('orders.ship', $order) }}" class="mb-4">
            @csrf @method('PATCH')
            <button type="submit"
                    class="w-full bg-ocean-600 hover:bg-ocean-700 text-white font-bold py-3.5 rounded-2xl text-sm transition">
                発送通知を送る
            </button>
        </form>
    @endif

    @if($order->status === 'shipped' && Auth::id() === $order->buyer_id)
        <form method="POST" action="{{ route('orders.complete', $order) }}" class="mb-4"
              onsubmit="return confirm('受取を確認しますか？取引が完了します。')">
            @csrf @method('PATCH')
            <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-2xl text-sm transition">
                受取完了にする
            </button>
        </form>
    @endif

    {{-- 配送先 --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4">
        <p class="text-xs text-gray-400 font-medium mb-2">配送先</p>
        <p class="text-sm font-medium text-gray-800">{{ $order->address->name }}</p>
        <p class="text-xs text-gray-500 mt-0.5">
            〒{{ $order->address->postal_code }}
            {{ $order->address->prefecture }}{{ $order->address->city }}{{ $order->address->street }}
            @if($order->address->building) {{ $order->address->building }}@endif
        </p>
        <p class="text-xs text-gray-400">{{ $order->address->phone }}</p>
    </div>

    {{-- 取引相手 --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4">
        @if(Auth::id() === $order->buyer_id)
            <p class="text-xs text-gray-400 font-medium mb-2">出品者</p>
            @php $partner = $order->seller @endphp
        @else
            <p class="text-xs text-gray-400 font-medium mb-2">購入者</p>
            @php $partner = $order->buyer @endphp
        @endif
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-ocean-100 flex items-center justify-center overflow-hidden">
                @if($partner->avatar)
                    <img src="{{ Storage::url($partner->avatar) }}" alt="" class="w-full h-full object-cover">
                @else
                    <span class="text-ocean-600 text-xs font-bold">{{ mb_substr($partner->name, 0, 1) }}</span>
                @endif
            </div>
            <p class="text-sm font-medium text-gray-800">{{ $partner->name }}</p>
        </div>
    </div>

    {{-- 取引メッセージ --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <h2 class="font-bold text-gray-700 mb-4 text-sm">取引メッセージ</h2>

        <div class="space-y-3 mb-4 max-h-80 overflow-y-auto">
            @forelse($order->messages as $msg)
                @php $isMine = $msg->user_id === Auth::id() @endphp
                <div class="flex gap-2 {{ $isMine ? 'flex-row-reverse' : '' }}">
                    <div class="w-7 h-7 rounded-full bg-ocean-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                        @if($msg->user->avatar)
                            <img src="{{ Storage::url($msg->user->avatar) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <span class="text-ocean-600 text-xs font-bold">{{ mb_substr($msg->user->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div class="max-w-xs">
                        <div class="rounded-2xl px-3 py-2 text-sm {{ $isMine ? 'bg-ocean-600 text-white rounded-tr-none' : 'bg-gray-100 text-gray-800 rounded-tl-none' }}">
                            {{ $msg->body }}
                        </div>
                        <p class="text-xs text-gray-400 mt-1 {{ $isMine ? 'text-right' : '' }}">{{ $msg->created_at->format('m/d H:i') }}</p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-4">まだメッセージはありません</p>
            @endforelse
        </div>

        @if(!$order->isCompleted() && !$order->isCancelled())
            <form method="POST" action="{{ route('trade-messages.store', $order) }}" class="border-t border-gray-100 pt-4">
                @csrf
                <div class="flex gap-2">
                    <textarea name="body" rows="2" maxlength="1000"
                              placeholder="メッセージを入力..."
                              class="flex-1 border border-gray-200 rounded-xl px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-ocean-300"></textarea>
                    <button type="submit"
                            class="bg-ocean-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-ocean-700 transition self-end">
                        送信
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection
