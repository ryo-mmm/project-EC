<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ハピトレ') | Happy Trade</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ocean: {
                            50:  '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        },
                        sand: {
                            100: '#fef9f0',
                            200: '#fde8c8',
                            500: '#d4a265',
                        }
                    }
                }
            }
        }
    </script>
    @stack('head')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

{{-- ヘッダー --}}
<header class="bg-white border-b border-ocean-100 sticky top-0 z-50 shadow-sm">
    <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between">
        {{-- ロゴ --}}
        <a href="{{ route('top') }}" class="text-xl font-bold text-ocean-700 tracking-wide">
            🌊 ハピトレ
        </a>

        {{-- ナビ --}}
        <nav class="flex items-center gap-3 text-sm">
            <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-ocean-600">商品一覧</a>

            @auth
                <a href="{{ route('products.create') }}"
                   class="bg-ocean-600 text-white px-3 py-1.5 rounded-full text-xs font-semibold hover:bg-ocean-700 transition">
                    出品する
                </a>
                <div class="relative group">
                    <button class="flex items-center gap-1 text-gray-600 hover:text-ocean-600">
                        <div class="w-7 h-7 rounded-full bg-ocean-100 flex items-center justify-center overflow-hidden">
                            @if(Auth::user()->avatar)
                                <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="" class="w-full h-full object-cover">
                            @else
                                <span class="text-ocean-600 text-xs font-bold">{{ mb_substr(Auth::user()->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="absolute right-0 mt-1 w-40 bg-white rounded-xl shadow-lg border border-gray-100 py-1 hidden group-hover:block">
                        <a href="{{ route('mypage') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-ocean-50">マイページ</a>
                        <a href="{{ route('mypage.selling') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-ocean-50">出品履歴</a>
                        <a href="{{ route('mypage.buying') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-ocean-50">購入履歴</a>
                        <a href="{{ route('mypage.favorites') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-ocean-50">お気に入り</a>
                        <hr class="my-1 border-gray-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50">ログアウト</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-ocean-600">ログイン</a>
                <a href="{{ route('register') }}"
                   class="bg-ocean-600 text-white px-3 py-1.5 rounded-full text-xs font-semibold hover:bg-ocean-700 transition">
                    会員登録
                </a>
            @endauth
        </nav>
    </div>
</header>

{{-- フラッシュメッセージ --}}
@if(session('success'))
    <div class="max-w-5xl mx-auto px-4 mt-3">
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3 flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    </div>
@endif

@if($errors->any())
    <div class="max-w-5xl mx-auto px-4 mt-3">
        <div class="bg-red-50 border border-red-200 text-red-600 text-sm rounded-xl px-4 py-3">
            <ul class="space-y-1 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- メインコンテンツ --}}
<main class="flex-1 max-w-5xl w-full mx-auto px-4 py-6">
    @yield('content')
</main>

{{-- フッター --}}
<footer class="bg-ocean-700 text-ocean-100 text-sm py-8 mt-12">
    <div class="max-w-5xl mx-auto px-4 text-center space-y-2">
        <p class="text-white font-bold text-base">🌊 ハピトレ</p>
        <p class="text-ocean-200 text-xs">海辺のライフスタイルを、もっと自由に。</p>
        <p class="text-ocean-300 text-xs">&copy; {{ date('Y') }} Happy Trade. All rights reserved.</p>
    </div>
</footer>

@stack('scripts')
</body>
</html>
