@extends('layouts.app')
@section('title', 'お気に入り')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-xl font-bold text-gray-800 mb-5">お気に入り</h1>

    @if($favorites->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <p class="text-4xl mb-3">❤️</p>
            <p class="text-sm">お気に入りに追加した商品はありません</p>
            <a href="{{ route('products.index') }}" class="mt-3 inline-block text-ocean-600 text-sm hover:underline">
                商品をさがす
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-5">
            @foreach($favorites as $favorite)
                @if($favorite->product)
                    @include('components.product-card', ['product' => $favorite->product])
                @endif
            @endforeach
        </div>
        {{ $favorites->links() }}
    @endif
</div>
@endsection
