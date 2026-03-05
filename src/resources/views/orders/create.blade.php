@extends('layouts.app')
@section('title', '購入手続き')

@section('content')
<div class="max-w-xl mx-auto">
    <h1 class="text-xl font-bold text-gray-800 mb-6">購入手続き</h1>

    {{-- 商品確認 --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5">
        <p class="text-xs text-gray-400 mb-3 font-medium">購入する商品</p>
        <div class="flex gap-3">
            <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                @if($product->mainImage)
                    <img src="{{ Storage::url($product->mainImage->image_path) }}" alt="" class="w-full h-full object-cover">
                @endif
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800">{{ $product->name }}</p>
                @if($product->brand)<p class="text-xs text-gray-400">{{ $product->brand }}</p>@endif
                <p class="text-lg font-bold text-ocean-700 mt-1">¥{{ number_format($product->price) }}</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('orders.store', $product) }}" class="space-y-5">
        @csrf

        {{-- 配送先 --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h2 class="text-sm font-semibold text-gray-700 mb-3">配送先を選択</h2>

            @if($addresses->isEmpty())
                <div class="text-center py-4 text-sm text-gray-400 border-2 border-dashed border-gray-200 rounded-xl mb-3">
                    <p>配送先住所が登録されていません</p>
                </div>
                <a href="{{ route('mypage') }}"
                   class="block text-center text-sm text-ocean-600 hover:underline">
                    マイページで住所を追加する
                </a>
            @else
                <div class="space-y-2">
                    @foreach($addresses as $addr)
                        <label class="flex items-start gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:border-ocean-300 has-[:checked]:border-ocean-500 has-[:checked]:bg-ocean-50 transition">
                            <input type="radio" name="address_id" value="{{ $addr->id }}"
                                   @checked($addr->is_default || $loop->first) required
                                   class="mt-0.5 text-ocean-600">
                            <div class="text-sm">
                                <p class="font-medium text-gray-800">{{ $addr->name }}</p>
                                <p class="text-gray-500 text-xs mt-0.5">
                                    〒{{ $addr->postal_code }} {{ $addr->prefecture }}{{ $addr->city }}{{ $addr->street }}
                                    @if($addr->building) {{ $addr->building }}@endif
                                </p>
                                <p class="text-gray-400 text-xs">{{ $addr->phone }}</p>
                            </div>
                            @if($addr->is_default)
                                <span class="ml-auto text-xs bg-ocean-100 text-ocean-700 px-2 py-0.5 rounded-full">デフォルト</span>
                            @endif
                        </label>
                    @endforeach
                </div>
            @endif
            @error('address_id')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- 支払い方法 --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h2 class="text-sm font-semibold text-gray-700 mb-3">支払い方法</h2>
            <div class="space-y-2">
                @foreach(['credit_card' => 'クレジットカード', 'convenience' => 'コンビニ払い', 'bank' => '銀行振込'] as $val => $label)
                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:border-ocean-300 has-[:checked]:border-ocean-500 has-[:checked]:bg-ocean-50 transition">
                        <input type="radio" name="payment_method" value="{{ $val }}"
                               @checked($val === 'credit_card') class="text-ocean-600">
                        <span class="text-sm text-gray-700">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            @error('payment_method')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- 金額サマリー --}}
        <div class="bg-ocean-50 rounded-2xl p-4 border border-ocean-100">
            <div class="flex justify-between text-sm mb-1">
                <span class="text-gray-600">商品代金</span>
                <span class="font-medium">¥{{ number_format($product->price) }}</span>
            </div>
            <div class="flex justify-between text-sm mb-3">
                <span class="text-gray-600">配送料</span>
                <span class="text-green-600 font-medium">無料</span>
            </div>
            <div class="flex justify-between font-bold text-ocean-700 text-lg border-t border-ocean-200 pt-3">
                <span>合計</span>
                <span>¥{{ number_format($product->price) }}</span>
            </div>
        </div>

        @if($addresses->isNotEmpty())
            <button type="submit"
                    class="w-full bg-ocean-600 hover:bg-ocean-700 text-white font-bold py-4 rounded-2xl text-base transition"
                    onclick="return confirm('購入を確定しますか？')">
                購入を確定する
            </button>
        @endif

        <a href="{{ route('products.show', $product) }}"
           class="block text-center text-sm text-gray-400 hover:text-gray-600">
            キャンセル
        </a>
    </form>
</div>
@endsection
