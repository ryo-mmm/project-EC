@extends('layouts.app')
@section('title', '購入した商品')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-xl font-bold text-gray-800 mb-5">購入した商品</h1>

    @if($orders->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <p class="text-4xl mb-3">🛍️</p>
            <p class="text-sm">購入した商品はありません</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($orders as $order)
                <a href="{{ route('orders.show', $order) }}"
                   class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex gap-3 hover:shadow-md transition block">
                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                        @if($order->product->mainImage)
                            <img src="{{ Storage::url($order->product->mainImage->image_path) }}" alt="" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $order->product->name }}</p>
                        <p class="text-base font-bold text-ocean-700">¥{{ number_format($order->price) }}</p>
                        <div class="flex items-center justify-between mt-1">
                            @php
                                $statusColors = ['paid' => 'bg-blue-100 text-blue-700', 'shipped' => 'bg-yellow-100 text-yellow-700', 'completed' => 'bg-green-100 text-green-700', 'cancelled' => 'bg-red-100 text-red-500'];
                            @endphp
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-500' }}">
                                {{ \App\Models\Order::STATUSES[$order->status] }}
                            </span>
                            <p class="text-xs text-gray-400">{{ $order->created_at->format('Y/m/d') }}</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 self-center flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @endforeach
        </div>
        <div class="mt-5">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
