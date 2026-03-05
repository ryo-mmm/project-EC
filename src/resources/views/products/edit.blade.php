@extends('layouts.app')
@section('title', '商品を編集する')

@section('content')
<div class="max-w-xl mx-auto">
    <h1 class="text-xl font-bold text-gray-800 mb-6">商品を編集する</h1>

    <form method="POST" action="{{ route('products.update', $product) }}" class="space-y-5">
        @csrf @method('PUT')

        {{-- 基本情報 --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-4">

            <div>
                <label class="block text-sm text-gray-600 mb-1">カテゴリ <span class="text-red-400">*</span></label>
                <select name="category_id" required
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-ocean-300">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">商品名 <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required maxlength="100"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ocean-300">
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">ブランド</label>
                <input type="text" name="brand" value="{{ old('brand', $product->brand) }}" maxlength="100"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ocean-300">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">サイズ</label>
                    <input type="text" name="size" value="{{ old('size', $product->size) }}" maxlength="20"
                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ocean-300">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">カラー</label>
                    <input type="text" name="color" value="{{ old('color', $product->color) }}" maxlength="30"
                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ocean-300">
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">商品の状態 <span class="text-red-400">*</span></label>
                <select name="condition" required
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-ocean-300">
                    @foreach($conditions as $key => $label)
                        <option value="{{ $key }}" @selected(old('condition', $product->condition) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">商品説明 <span class="text-red-400">*</span></label>
                <textarea name="description" required rows="5"
                          class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-ocean-300">{{ old('description', $product->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">販売価格 <span class="text-red-400">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">¥</span>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="300" max="9999999"
                           class="w-full border border-gray-200 rounded-xl pl-7 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ocean-300">
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-2">出品ステータス</label>
                <div class="flex gap-3">
                    @foreach(['on_sale' => '販売中', 'draft' => '下書き', 'suspended' => '出品停止'] as $val => $label)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="{{ $val }}"
                                   @checked(old('status', $product->status) === $val) class="text-ocean-600">
                            <span class="text-sm text-gray-700">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('products.show', $product) }}"
               class="flex-1 text-center border border-gray-300 text-gray-600 font-semibold py-3 rounded-2xl text-sm hover:bg-gray-50 transition">
                キャンセル
            </a>
            <button type="submit"
                    class="flex-1 bg-ocean-600 hover:bg-ocean-700 text-white font-bold py-3 rounded-2xl text-sm transition">
                更新する
            </button>
        </div>
    </form>
</div>
@endsection
