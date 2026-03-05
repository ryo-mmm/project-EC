<a href="{{ route('products.show', $product) }}"
   class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition group border border-gray-100">
    {{-- 商品画像 --}}
    <div class="aspect-square bg-gray-100 overflow-hidden">
        @if($product->mainImage)
            <img src="{{ Storage::url($product->mainImage->image_path) }}"
                 alt="{{ $product->name }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-300">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif
    </div>
    {{-- 商品情報 --}}
    <div class="p-3">
        <p class="text-xs text-gray-400 mb-0.5">{{ $product->category->name ?? '' }}</p>
        <p class="text-sm font-medium text-gray-800 line-clamp-2 leading-snug">{{ $product->name }}</p>
        @if($product->brand)
            <p class="text-xs text-gray-400 mt-0.5">{{ $product->brand }}</p>
        @endif
        <p class="text-base font-bold text-ocean-700 mt-1">¥{{ number_format($product->price) }}</p>
    </div>
</a>
