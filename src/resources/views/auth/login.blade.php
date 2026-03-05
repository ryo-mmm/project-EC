@extends('layouts.app')
@section('title', 'ログイン')

@section('content')
<div class="max-w-md mx-auto">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-ocean-700">ログイン</h1>
        <p class="text-sm text-gray-500 mt-1">おかえりなさい</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">メールアドレス</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ocean-300 @error('email') border-red-400 @enderror">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">パスワード</label>
                <input type="password" name="password" required
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ocean-300">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember" class="rounded text-ocean-600">
                <label for="remember" class="text-sm text-gray-600">ログイン状態を保持する</label>
            </div>

            <button type="submit"
                    class="w-full bg-ocean-600 hover:bg-ocean-700 text-white font-semibold py-3 rounded-xl transition text-sm">
                ログイン
            </button>
        </form>
    </div>

    <p class="text-center text-sm text-gray-500 mt-4">
        アカウントをお持ちでない方は
        <a href="{{ route('register') }}" class="text-ocean-600 hover:underline font-medium">会員登録</a>
    </p>
</div>
@endsection
