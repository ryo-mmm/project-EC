@extends('layouts.app')
@section('title', '会員登録')

@section('content')
<div class="max-w-md mx-auto">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-ocean-700">会員登録</h1>
        <p class="text-sm text-gray-500 mt-1">ハピトレへようこそ</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ニックネーム</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ocean-300 @error('name') border-red-400 @enderror"
                       placeholder="海太郎">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">メールアドレス</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ocean-300 @error('email') border-red-400 @enderror"
                       placeholder="example@hapitrade.jp">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">パスワード</label>
                <input type="password" name="password" required
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ocean-300"
                       placeholder="8文字以上">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">パスワード（確認）</label>
                <input type="password" name="password_confirmation" required
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ocean-300">
            </div>

            <button type="submit"
                    class="w-full bg-ocean-600 hover:bg-ocean-700 text-white font-semibold py-3 rounded-xl transition text-sm mt-2">
                登録する
            </button>
        </form>
    </div>

    <p class="text-center text-sm text-gray-500 mt-4">
        すでにアカウントをお持ちの方は
        <a href="{{ route('login') }}" class="text-ocean-600 hover:underline font-medium">ログイン</a>
    </p>
</div>
@endsection
