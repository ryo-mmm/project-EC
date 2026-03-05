@extends('layouts.app')
@section('title', $product->name)

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- 画像ギャラリー --}}
    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 mb-4">
        @if($product->images->isNotEmpty())
            <div class="aspect-square bg-gray-50">
                <img src="{{ Storage::url($product->images->first()->image_path) }}"
                     alt="{{ $product->name }}"
                     id="main-image"
                     class="w-full h-full object-contain">
            </div>
            @if($product->images->count() > 1)
                <div class="flex gap-2 p-3 overflow-x-auto">
                    @foreach($product->images as $img)
                        <button onclick="document.getElementById('main-image').src='{{ Storage::url($img->image_path) }}'"
                                class="w-16 h-16 flex-shrink-0 rounded-lg overflow-hidden border-2 border-transparent hover:border-ocean-400">
                            <img src="{{ Storage::url($img->image_path) }}" alt="" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        @else
            <div class="aspect-square bg-gray-100 flex items-center justify-center text-gray-300">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif
    </div>

    {{-- 商品情報 --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
        <div class="flex items-start justify-between gap-3 mb-3">
            <div>
                <p class="text-xs text-gray-400">{{ $product->category->name ?? '' }}</p>
                <h1 class="text-lg font-bold text-gray-800 mt-0.5">{{ $product->name }}</h1>
                @if($product->brand)
                    <p class="text-sm text-gray-500 mt-0.5">{{ $product->brand }}</p>
                @endif
            </div>
            {{-- お気に入りボタン --}}
            @auth
                @if($isFavorited)
                    <form method="POST" action="{{ route('favorites.destroy', $product) }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="flex flex-col items-center text-rose-500 hover:text-rose-600">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                            <span class="text-xs">{{ $product->favorites->count() }}</span>
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('favorites.store', $product) }}">
                        @csrf
                        <button type="submit" class="flex flex-col items-center text-gray-300 hover:text-rose-400">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                            <span class="text-xs">{{ $product->favorites->count() }}</span>
                        </button>
                    </form>
                @endif
            @endauth
        </div>

        <p class="text-2xl font-bold text-ocean-700 mb-4">¥{{ number_format($product->price) }}</p>

        <div class="grid grid-cols-2 gap-2 text-sm mb-4">
            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 mb-0.5">商品の状態</p>
                <p class="font-medium text-gray-700">{{ \App\Models\Product::CONDITIONS[$product->condition] }}</p>
            </div>
            @if($product->size)
            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 mb-0.5">サイズ</p>
                <p class="font-medium text-gray-700">{{ $product->size }}</p>
            </div>
            @endif
            @if($product->color)
            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 mb-0.5">カラー</p>
                <p class="font-medium text-gray-700">{{ $product->color }}</p>
            </div>
            @endif
        </div>

        <div class="border-t border-gray-100 pt-4">
            <p class="text-sm text-gray-500 font-medium mb-2">商品説明</p>
            <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $product->description }}</p>
        </div>
    </div>

    {{-- 出品者情報 --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4">
        <p class="text-xs text-gray-400 mb-2">出品者</p>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-ocean-100 flex items-center justify-center overflow-hidden">
                @if($product->user->avatar)
                    <img src="{{ Storage::url($product->user->avatar) }}" alt="" class="w-full h-full object-cover">
                @else
                    <span class="text-ocean-600 font-bold">{{ mb_substr($product->user->name, 0, 1) }}</span>
                @endif
            </div>
            <div>
                <p class="text-sm font-medium text-gray-800">{{ $product->user->name }}</p>
                <p class="text-xs text-gray-400">評価 {{ number_format($product->user->rating, 1) }}</p>
            </div>
        </div>
    </div>

    {{-- 購入ボタン --}}
    @if($product->status === 'on_sale')
        @auth
            @if(Auth::id() !== $product->user_id)
                <a href="{{ route('orders.create', $product) }}"
                   class="block w-full text-center bg-ocean-600 hover:bg-ocean-700 text-white font-bold py-4 rounded-2xl text-base transition mb-4">
                    購入する
                </a>
            @else
                <div class="flex gap-2 mb-4">
                    <a href="{{ route('products.edit', $product) }}"
                       class="flex-1 text-center border border-ocean-600 text-ocean-600 font-semibold py-3 rounded-2xl text-sm hover:bg-ocean-50 transition">
                        編集する
                    </a>
                    <form method="POST" action="{{ route('products.destroy', $product) }}" class="flex-1"
                          onsubmit="return confirm('この商品を削除しますか？')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full border border-red-300 text-red-500 font-semibold py-3 rounded-2xl text-sm hover:bg-red-50 transition">
                            削除する
                        </button>
                    </form>
                </div>
            @endif
        @else
            <a href="{{ route('login') }}"
               class="block w-full text-center bg-ocean-600 hover:bg-ocean-700 text-white font-bold py-4 rounded-2xl text-base transition mb-4">
                ログインして購入する
            </a>
        @endauth
    @else
        <div class="w-full text-center bg-gray-200 text-gray-500 font-bold py-4 rounded-2xl text-base mb-4 cursor-not-allowed">
            売り切れ
        </div>
    @endif

    {{-- コメント欄 --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <h2 class="font-bold text-gray-700 mb-4">コメント ({{ $product->comments->count() }})</h2>

        @forelse($product->comments as $comment)
            <div class="flex gap-3 mb-4">
                <div class="w-8 h-8 rounded-full bg-ocean-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                    @if($comment->user->avatar)
                        <img src="{{ Storage::url($comment->user->avatar) }}" alt="" class="w-full h-full object-cover">
                    @else
                        <span class="text-ocean-600 text-xs font-bold">{{ mb_substr($comment->user->name, 0, 1) }}</span>
                    @endif
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-gray-700">{{ $comment->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                    </div>
                    <p class="text-sm text-gray-600 mt-1 leading-relaxed">{{ $comment->body }}</p>
                    @if(Auth::check() && (Auth::id() === $comment->user_id || Auth::id() === $product->user_id))
                        <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="mt-1">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-gray-300 hover:text-red-400">削除</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-400 mb-4">まだコメントはありません</p>
        @endforelse

        @auth
            @if($product->status === 'on_sale')
                <form method="POST" action="{{ route('comments.store', $product) }}" class="border-t border-gray-100 pt-4">
                    @csrf
                    <textarea name="body" rows="3" maxlength="500"
                              placeholder="コメントを入力してください（例：サイズ感はいかがですか？）"
                              class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-ocean-300 @error('body') border-red-400 @enderror"></textarea>
                    <div class="flex justify-end mt-2">
                        <button type="submit"
                                class="bg-ocean-600 text-white px-5 py-2 rounded-xl text-sm font-medium hover:bg-ocean-700 transition">
                            送信
                        </button>
                    </div>
                </form>
            @endif
        @else
            <p class="text-sm text-gray-400 border-t border-gray-100 pt-4">
                <a href="{{ route('login') }}" class="text-ocean-600 hover:underline">ログイン</a>してコメントする
            </p>
        @endauth
    </div>
</div>
@endsection
