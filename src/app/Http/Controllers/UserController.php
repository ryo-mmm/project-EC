<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UserController extends Controller
{
    public function show(): View
    {
        $user = Auth::user();
        return view('mypage.show', compact('user'));
    }

    public function edit(): View
    {
        return view('mypage.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:50'],
            'profile_text' => ['nullable', 'string', 'max:500'],
            'avatar'       => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return redirect()->route('mypage')->with('success', 'プロフィールを更新しました。');
    }

    public function selling(): View
    {
        $products = Auth::user()
            ->products()
            ->with('mainImage')
            ->latest()
            ->paginate(20);

        return view('mypage.selling', compact('products'));
    }

    public function buying(): View
    {
        $orders = Auth::user()
            ->purchases()
            ->with(['product.mainImage', 'seller'])
            ->latest()
            ->paginate(20);

        return view('mypage.buying', compact('orders'));
    }

    public function favorites(): View
    {
        $favorites = Auth::user()
            ->favorites()
            ->with(['product.mainImage'])
            ->latest()
            ->paginate(20);

        return view('mypage.favorites', compact('favorites'));
    }
}
