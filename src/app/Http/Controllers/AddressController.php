<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:50'],
            'postal_code' => ['required', 'string', 'max:8'],
            'prefecture'  => ['required', 'string', 'max:10'],
            'city'        => ['required', 'string', 'max:50'],
            'street'      => ['required', 'string', 'max:100'],
            'building'    => ['nullable', 'string', 'max:100'],
            'phone'       => ['required', 'string', 'max:15'],
            'is_default'  => ['boolean'],
        ]);

        if (!empty($validated['is_default'])) {
            Auth::user()->addresses()->update(['is_default' => false]);
        }

        Auth::user()->addresses()->create($validated);

        return back()->with('success', '住所を追加しました。');
    }

    public function update(Request $request, Address $address): RedirectResponse
    {
        $this->authorize('update', $address);

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:50'],
            'postal_code' => ['required', 'string', 'max:8'],
            'prefecture'  => ['required', 'string', 'max:10'],
            'city'        => ['required', 'string', 'max:50'],
            'street'      => ['required', 'string', 'max:100'],
            'building'    => ['nullable', 'string', 'max:100'],
            'phone'       => ['required', 'string', 'max:15'],
            'is_default'  => ['boolean'],
        ]);

        if (!empty($validated['is_default'])) {
            Auth::user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($validated);

        return back()->with('success', '住所を更新しました。');
    }

    public function destroy(Address $address): RedirectResponse
    {
        $this->authorize('delete', $address);

        $address->delete();

        return back()->with('success', '住所を削除しました。');
    }
}
