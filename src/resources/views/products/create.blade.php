@extends('layouts.app')
@section('title', '商品を出品する')

@section('content')
<div class="max-w-xl mx-auto">
    <h1 class="text-xl font-bold text-gray-800 mb-6">商品を出品する</h1>

    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- 商品画像 --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <label class="block text-sm font-semibold text-gray-700 mb-3">商品画像 <span class="text-red-400">*</span></label>
            <div class="grid grid-cols-3 gap-2">
                @for($i = 0; $i < 5; $i++)
                    <label class="aspect-square border-2 border-dashed border-gray-200 rounded-xl flex items-center justify-center cursor-pointer hover:border-ocean-300 transition relative overflow-hidden"
                           id="label-{{ $i }}">
                        <input type="file" name="images[]" accept="image/*" class="hidden"
                               onchange="previewImage(this, {{ $i }})">
                        <div id="placeholder-{{ $i }}" class="text-center text-gray-300">
                            <svg class="w-7 h-7 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            @if($i === 0)<p class="text-xs mt-1">メイン</p>@endif
                        </div>
                        <img id="preview-{{ $i }}" class="hidden w-full h-full object-cover absolute inset-0">
                    </label>
                @endfor
            </div>
            <p class="text-xs text-gray-400 mt-2">最大5枚・各5MB以内（JPEG/PNG/WebP）</p>
            @error('images')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- 基本情報 --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700">基本情報</h2>

            <div>
                <label class="block text-sm text-gray-600 mb-1">カテゴリ <span class="text-red-400">*</span></label>
                <select name="category_id" required
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-ocean-300 @error('category_id') border-red-400 @enderror">
                    <option value="">選択してください</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">商品名 <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required maxlength="100"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ocean-300 @error('name') border-red-400 @enderror"
                       placeholder="例：ロンハーマン サーフパーカー">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">ブランド</label>
                <input type="text" name="brand" value="{{ old('brand') }}" maxlength="100"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ocean-300"
                       placeholder="例：Ron Herman">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">サイズ</label>
                    <input type="text" name="size" value="{{ old('size') }}" maxlength="20"
                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ocean-300"
                           placeholder="例：M">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">カラー</label>
                    <input type="text" name="color" value="{{ old('color') }}" maxlength="30"
                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ocean-300"
                           placeholder="例：ネイビー">
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">商品の状態 <span class="text-red-400">*</span></label>
                <select name="condition" required
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-ocean-300 @error('condition') border-red-400 @enderror">
                    <option value="">選択してください</option>
                    @foreach($conditions as $key => $label)
                        <option value="{{ $key }}" @selected(old('condition') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('condition')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">商品説明 <span class="text-red-400">*</span></label>
                <textarea name="description" required rows="5"
                          class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-ocean-300 @error('description') border-red-400 @enderror"
                          placeholder="商品の詳細・注意点などを記入してください">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- 価格・出品設定 --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700">価格・出品設定</h2>

            <div>
                <label class="block text-sm text-gray-600 mb-1">販売価格 <span class="text-red-400">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">¥</span>
                    <input type="number" name="price" value="{{ old('price') }}" required min="300" max="9999999"
                           class="w-full border border-gray-200 rounded-xl pl-7 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ocean-300 @error('price') border-red-400 @enderror"
                           placeholder="300〜9,999,999">
                </div>
                <p class="text-xs text-gray-400 mt-1">販売手数料10%を差し引いた金額が振り込まれます</p>
                @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-2">出品方法</label>
                <div class="flex gap-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="on_sale"
                               @checked(old('status', 'on_sale') === 'on_sale') class="text-ocean-600">
                        <span class="text-sm text-gray-700">すぐに出品する</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="draft"
                               @checked(old('status') === 'draft') class="text-ocean-600">
                        <span class="text-sm text-gray-700">下書き保存</span>
                    </label>
                </div>
            </div>
        </div>

        <button type="submit"
                class="w-full bg-ocean-600 hover:bg-ocean-700 text-white font-bold py-4 rounded-2xl text-base transition">
            出品する
        </button>
    </form>
</div>

@push('scripts')
<script>
function previewImage(input, index) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('preview-' + index).src = e.target.result;
            document.getElementById('preview-' + index).classList.remove('hidden');
            document.getElementById('placeholder-' + index).classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
