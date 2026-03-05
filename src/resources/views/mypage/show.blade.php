@extends('layouts.app')
@section('title', 'マイページ')

@section('content')
<div class="max-w-xl mx-auto">

    {{-- プロフィール --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-ocean-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="" class="w-full h-full object-cover">
                @else
                    <span class="text-ocean-600 text-2xl font-bold">{{ mb_substr($user->name, 0, 1) }}</span>
                @endif
            </div>
            <div class="flex-1">
                <p class="font-bold text-gray-800 text-lg">{{ $user->name }}</p>
                <p class="text-xs text-gray-400 mt-0.5">評価 {{ number_format($user->rating, 1) }} ⭐</p>
                @if($user->profile_text)
                    <p class="text-sm text-gray-600 mt-1 leading-relaxed">{{ $user->profile_text }}</p>
                @endif
            </div>
        </div>
        <a href="{{ route('mypage.edit') }}"
           class="mt-4 block text-center border border-gray-200 text-gray-600 text-sm font-medium py-2.5 rounded-xl hover:bg-gray-50 transition">
            プロフィールを編集
        </a>
    </div>

    {{-- メニュー --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm divide-y divide-gray-100">
        @php
            $menus = [
                ['route' => 'products.create',  'icon' => '📦', 'label' => '商品を出品する'],
                ['route' => 'mypage.selling',   'icon' => '🏷️', 'label' => '出品した商品'],
                ['route' => 'mypage.buying',    'icon' => '🛍️', 'label' => '購入した商品'],
                ['route' => 'mypage.favorites', 'icon' => '❤️', 'label' => 'お気に入り'],
            ];
        @endphp
        @foreach($menus as $menu)
            <a href="{{ route($menu['route']) }}"
               class="flex items-center gap-3 px-5 py-4 hover:bg-gray-50 transition">
                <span class="text-lg">{{ $menu['icon'] }}</span>
                <span class="text-sm font-medium text-gray-700">{{ $menu['label'] }}</span>
                <svg class="w-4 h-4 text-gray-300 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @endforeach
    </div>

    {{-- ログアウト --}}
    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit"
                class="w-full text-center text-sm text-red-400 hover:text-red-500 py-3">
            ログアウト
        </button>
    </form>
</div>
@endsection
