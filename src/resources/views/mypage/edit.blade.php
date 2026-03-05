@extends('layouts.app')
@section('title', 'プロフィール編集')

@section('content')
<div class="max-w-xl mx-auto">
    <h1 class="text-xl font-bold text-gray-800 mb-6">プロフィール編集</h1>

    <form method="POST" action="{{ route('mypage.update') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf @method('PUT')

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-4">

            {{-- アイコン --}}
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-ocean-100 flex items-center justify-center overflow-hidden flex-shrink-0" id="avatar-preview-wrapper">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="" id="avatar-preview" class="w-full h-full object-cover">
                    @else
                        <span class="text-ocean-600 text-2xl font-bold" id="avatar-initial">{{ mb_substr($user->name, 0, 1) }}</span>
                    @endif
                </div>
                <label class="cursor-pointer text-sm text-ocean-600 hover:underline">
                    写真を変更
                    <input type="file" name="avatar" accept="image/*" class="hidden"
                           onchange="previewAvatar(this)">
                </label>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">ニックネーム <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required maxlength="50"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ocean-300 @error('name') border-red-400 @enderror">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">自己紹介</label>
                <textarea name="profile_text" rows="4" maxlength="500"
                          class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-ocean-300"
                          placeholder="好きなブランドや海のこと、ぜひ教えてください">{{ old('profile_text', $user->profile_text) }}</textarea>
            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('mypage') }}"
               class="flex-1 text-center border border-gray-300 text-gray-600 font-semibold py-3 rounded-2xl text-sm hover:bg-gray-50 transition">
                キャンセル
            </a>
            <button type="submit"
                    class="flex-1 bg-ocean-600 hover:bg-ocean-700 text-white font-bold py-3 rounded-2xl text-sm transition">
                保存する
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const wrapper = document.getElementById('avatar-preview-wrapper');
            wrapper.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
