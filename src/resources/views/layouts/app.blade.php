<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ハピトレ') | Happy Trade</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Noto Sans JP', 'Inter', 'sans-serif'],
                    },
                    colors: {
                        ocean: {
                            50:  '#f0f9ff',
                            100: '#dff0fa',
                            200: '#b8dcf5',
                            400: '#38a3d4',
                            500: '#1a85bc',
                            600: '#0e6a9e',
                            700: '#0a5480',
                            800: '#083f60',
                            900: '#052d44',
                        },
                        sand: {
                            50:  '#fdf8f0',
                            100: '#f8edda',
                            200: '#f0d9b0',
                            400: '#d4a265',
                            500: '#b8854a',
                        },
                    },
                    keyframes: {
                        'slide-down': {
                            '0%':   { opacity: '0', transform: 'translateY(-6px) scale(0.97)' },
                            '100%': { opacity: '1', transform: 'translateY(0) scale(1)' },
                        },
                        'fade-in': {
                            '0%':   { opacity: '0', transform: 'translateY(-4px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                    },
                    animation: {
                        'slide-down': 'slide-down 0.18s ease-out',
                        'fade-in':    'fade-in 0.25s ease-out',
                    },
                }
            }
        }
    </script>
    <style>
        body { letter-spacing: 0.01em; }
        .glass-header {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        /* スクロールバー細め */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
    </style>
    @stack('head')
</head>
<body class="bg-[#f7f8fa] min-h-screen flex flex-col font-sans text-gray-900 antialiased">

{{-- ヘッダー --}}
<header class="glass-header border-b border-white/60 sticky top-0 z-50" style="box-shadow:0 1px 24px 0 rgba(10,84,128,0.06);">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">

        {{-- ロゴ --}}
        <a href="{{ route('top') }}" class="flex items-center gap-2 shrink-0 group">
            <span class="text-2xl leading-none select-none">🌊</span>
            <span class="font-bold text-[1.15rem] tracking-tight text-ocean-800 group-hover:text-ocean-600 transition-colors">
                ハピトレ
            </span>
        </a>

        {{-- ナビ --}}
        <nav class="flex items-center gap-2 sm:gap-3">
            <a href="{{ route('products.index') }}"
               class="hidden sm:inline-flex items-center gap-1 text-sm font-medium text-gray-500 hover:text-ocean-700 transition-colors px-2 py-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                一覧
            </a>

            @auth
                {{-- 出品ボタン --}}
                <a href="{{ route('products.create') }}"
                   class="inline-flex items-center gap-1.5 bg-ocean-700 hover:bg-ocean-800 text-white text-xs font-semibold px-3.5 py-2 rounded-full transition-all shadow-sm hover:shadow-md">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    出品する
                </a>

                {{-- ユーザーメニュー --}}
                <div class="relative" id="user-menu-wrapper">
                    <button onclick="toggleUserMenu()"
                            class="flex items-center gap-1.5 pl-1 pr-2 py-1 rounded-full hover:bg-ocean-50 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-ocean-400 to-ocean-700 flex items-center justify-center overflow-hidden ring-2 ring-white shadow-sm">
                            @if(Auth::user()->avatar)
                                <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="" class="w-full h-full object-cover">
                            @else
                                <span class="text-white text-sm font-bold leading-none">{{ mb_substr(Auth::user()->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <svg id="chevron-icon" class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div id="user-menu"
                         class="hidden absolute right-0 mt-2 w-52 origin-top-right">
                        <div class="bg-white rounded-2xl shadow-xl border border-gray-100/80 overflow-hidden animate-slide-down"
                             style="box-shadow:0 8px 32px rgba(10,84,128,0.13);">
                            {{-- ユーザー情報 --}}
                            <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-br from-ocean-50 to-sand-50">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            {{-- メニュー項目 --}}
                            <div class="py-1">
                                @foreach([
                                    ['route' => 'mypage',           'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => 'マイページ'],
                                    ['route' => 'mypage.selling',   'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',       'label' => '出品した商品'],
                                    ['route' => 'mypage.buying',    'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',                             'label' => '購入した商品'],
                                    ['route' => 'mypage.favorites', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'label' => 'お気に入り'],
                                ] as $item)
                                    <a href="{{ route($item['route']) }}"
                                       class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-ocean-50 hover:text-ocean-800 transition-colors">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $item['icon'] }}"/>
                                        </svg>
                                        {{ $item['label'] }}
                                    </a>
                                @endforeach
                            </div>
                            <div class="border-t border-gray-100 py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        ログアウト
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"
                   class="text-sm font-medium text-gray-500 hover:text-ocean-700 transition-colors px-2 py-1">
                    ログイン
                </a>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center bg-ocean-700 hover:bg-ocean-800 text-white text-xs font-semibold px-4 py-2 rounded-full transition-all shadow-sm hover:shadow-md">
                    会員登録
                </a>
            @endauth
        </nav>
    </div>
</header>

{{-- フラッシュメッセージ --}}
@if(session('success'))
    <div class="max-w-5xl mx-auto px-4 sm:px-6 mt-4 animate-fade-in">
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-2xl px-4 py-3 shadow-sm">
            <div class="w-6 h-6 rounded-full bg-emerald-500 flex items-center justify-center shrink-0">
                <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </div>
            <span class="font-medium">{{ session('success') }}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="max-w-5xl mx-auto px-4 sm:px-6 mt-4 animate-fade-in">
        <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-2xl px-4 py-3 shadow-sm">
            <div class="w-6 h-6 rounded-full bg-red-500 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <ul class="space-y-0.5 flex-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- メインコンテンツ --}}
<main class="flex-1 max-w-5xl w-full mx-auto px-4 sm:px-6 py-6 pb-24 sm:pb-6">
    @yield('content')
</main>

{{-- モバイル用ボトムナビ --}}
<nav class="sm:hidden fixed bottom-0 inset-x-0 z-40 glass-header border-t border-white/60"
     style="box-shadow:0 -1px 24px 0 rgba(10,84,128,0.07);">
    <div class="flex items-center justify-around h-16 px-2">
        <a href="{{ route('top') }}"
           class="flex flex-col items-center gap-0.5 px-4 py-2 rounded-xl {{ request()->routeIs('top') ? 'text-ocean-700' : 'text-gray-400' }} hover:text-ocean-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-[10px] font-medium">ホーム</span>
        </a>
        <a href="{{ route('products.index') }}"
           class="flex flex-col items-center gap-0.5 px-4 py-2 rounded-xl {{ request()->routeIs('products.index') ? 'text-ocean-700' : 'text-gray-400' }} hover:text-ocean-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <span class="text-[10px] font-medium">さがす</span>
        </a>
        @auth
            <a href="{{ route('products.create') }}"
               class="flex flex-col items-center gap-0.5 -mt-4">
                <div class="w-12 h-12 bg-ocean-700 rounded-full flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <span class="text-[10px] font-medium text-ocean-700">出品</span>
            </a>
            <a href="{{ route('mypage.favorites') }}"
               class="flex flex-col items-center gap-0.5 px-4 py-2 rounded-xl {{ request()->routeIs('mypage.favorites') ? 'text-ocean-700' : 'text-gray-400' }} hover:text-ocean-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <span class="text-[10px] font-medium">お気に入り</span>
            </a>
            <a href="{{ route('mypage') }}"
               class="flex flex-col items-center gap-0.5 px-4 py-2 rounded-xl {{ request()->routeIs('mypage') ? 'text-ocean-700' : 'text-gray-400' }} hover:text-ocean-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-[10px] font-medium">マイページ</span>
            </a>
        @else
            <a href="{{ route('login') }}"
               class="flex flex-col items-center gap-0.5 px-4 py-2 rounded-xl text-gray-400 hover:text-ocean-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-[10px] font-medium">ログイン</span>
            </a>
            <a href="{{ route('register') }}"
               class="flex flex-col items-center gap-0.5 px-4 py-2 rounded-xl text-gray-400 hover:text-ocean-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <span class="text-[10px] font-medium">登録</span>
            </a>
        @endauth
    </div>
</nav>

{{-- フッター --}}
<footer class="bg-ocean-900 text-ocean-200 mt-16 hidden sm:block">
    <div class="max-w-5xl mx-auto px-6 py-12">
        <div class="flex flex-col sm:flex-row justify-between gap-8 mb-10">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-2xl">🌊</span>
                    <span class="text-white font-bold text-lg tracking-tight">ハピトレ</span>
                </div>
                <p class="text-ocean-300 text-sm leading-relaxed max-w-xs">
                    海辺のライフスタイルを愛する人たちが<br>お気に入りをめぐらせるフリマアプリ。
                </p>
            </div>
            <div class="flex gap-12 text-sm">
                <div>
                    <p class="text-white font-semibold mb-3 text-xs tracking-widest uppercase">ショップ</p>
                    <ul class="space-y-2">
                        <li><a href="{{ route('products.index') }}" class="text-ocean-300 hover:text-white transition-colors">商品一覧</a></li>
                        @auth
                            <li><a href="{{ route('products.create') }}" class="text-ocean-300 hover:text-white transition-colors">出品する</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <p class="text-white font-semibold mb-3 text-xs tracking-widest uppercase">アカウント</p>
                    <ul class="space-y-2">
                        @auth
                            <li><a href="{{ route('mypage') }}" class="text-ocean-300 hover:text-white transition-colors">マイページ</a></li>
                            <li><a href="{{ route('mypage.buying') }}" class="text-ocean-300 hover:text-white transition-colors">購入履歴</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="text-ocean-300 hover:text-white transition-colors">ログイン</a></li>
                            <li><a href="{{ route('register') }}" class="text-ocean-300 hover:text-white transition-colors">会員登録</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
        <div class="border-t border-ocean-800 pt-6 text-xs text-ocean-500 flex justify-between items-center">
            <span>&copy; {{ date('Y') }} Happy Trade. All rights reserved.</span>
            <span class="text-ocean-600">海辺のライフスタイルを、もっと自由に。</span>
        </div>
    </div>
</footer>

@stack('scripts')
<script>
function toggleUserMenu() {
    const menu = document.getElementById('user-menu');
    const chevron = document.getElementById('chevron-icon');
    const isHidden = menu.classList.contains('hidden');
    menu.classList.toggle('hidden');
    chevron.style.transform = isHidden ? 'rotate(180deg)' : '';
}
document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('user-menu-wrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        document.getElementById('user-menu').classList.add('hidden');
        const chevron = document.getElementById('chevron-icon');
        if (chevron) chevron.style.transform = '';
    }
});
</script>
</body>
</html>
