@extends('layouts.app')
@section('title', '商品一覧')

@section('content')

{{-- ヒーロー（トップページのみ） --}}
@if(request()->routeIs('top'))
<div class="bg-gradient-to-r from-ocean-700 to-ocean-500 rounded-2xl px-6 py-10 mb-8 text-white text-center">
    <p class="text-ocean-100 text-sm mb-1">海辺のライフスタイルを、もっと自由に</p>
    <h1 class="text-3xl font-bold mb-3">🌊 ハピトレ</h1>
    <p class="text-ocean-100 text-sm mb-6">海系アパレル専門フリマアプリ</p>
    <a href="{{ route('products.index') }}"
       class="inline-block bg-white text-ocean-700 font-semibold px-6 py-2.5 rounded-full text-sm hover:bg-ocean-50 transition">
        商品をさがす
    </a>
</div>
@endif

{{-- 検索・絞り込み --}}
<form method="GET" action="{{ route('products.index') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <div class="flex gap-2 mb-3">
        <input type="text" name="keyword" value="{{ request('keyword') }}"
               placeholder="ブランド名・商品名で検索"
               class="flex-1 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ocean-300">
        <button type="submit"
                class="bg-ocean-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-ocean-700 transition">
            検索
        </button>
    </div>
    <div class="flex flex-wrap gap-2">
        <select name="category"
                class="border border-gray-200 rounded-xl px-3 py-2 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-ocean-300 bg-white">
            <option value="">すべてのカテゴリ</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
        <input type="number" name="min_price" value="{{ request('min_price') }}"
               placeholder="¥ 下限" min="0"
               class="w-28 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ocean-300">
        <input type="number" name="max_price" value="{{ request('max_price') }}"
               placeholder="¥ 上限" min="0"
               class="w-28 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ocean-300">
        @if(request()->hasAny(['keyword','category','min_price','max_price']))
            <a href="{{ route('products.index') }}" class="text-sm text-gray-400 hover:text-gray-600 py-2 px-2">
                リセット
            </a>
        @endif
    </div>
</form>

{{-- 件数 --}}
<p class="text-sm text-gray-500 mb-4">
    {{ number_format($products->total()) }}件
</p>

{{-- 商品グリッド --}}
@if($products->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm">商品が見つかりませんでした</p>
    </div>
@else
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-6">
        @foreach($products as $product)
            @include('components.product-card', ['product' => $product])
        @endforeach
    </div>
    {{ $products->links() }}
@endif

@endsection
