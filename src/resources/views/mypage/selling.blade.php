@extends('layouts.app')
@section('title', '出品した商品')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-5">
        <h1 class="text-xl font-bold text-gray-800">出品した商品</h1>
        <a href="{{ route('products.create') }}"
           class="bg-ocean-600 text-white text-xs font-semibold px-4 py-2 rounded-full hover:bg-ocean-700 transition">
            + 出品する
        </a>
    </div>

    @if($products->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <p class="text-4xl mb-3">📦</p>
            <p class="text-sm">出品した商品はありません</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($products as $product)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex gap-3">
                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                        @if($product->mainImage)
                            <img src="{{ Storage::url($product->mainImage->image_path) }}" alt="" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $product->name }}</p>
                        <p class="text-base font-bold text-ocean-700">¥{{ number_format($product->price) }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            @php
                                $statusColors = ['on_sale' => 'bg-green-100 text-green-700', 'sold_out' => 'bg-gray-100 text-gray-500', 'draft' => 'bg-yellow-100 text-yellow-700', 'suspended' => 'bg-red-100 text-red-500'];
                            @endphp
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $statusColors[$product->status] ?? 'bg-gray-100 text-gray-500' }}">
                                {{ \App\Models\Product::STATUSES[$product->status] }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('products.show', $product) }}"
                       class="text-xs text-ocean-600 hover:underline self-center flex-shrink-0">詳細</a>
                </div>
            @endforeach
        </div>
        <div class="mt-5">{{ $products->links() }}</div>
    @endif
</div>
@endsection
