<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::onSale()
            ->with(['mainImage', 'category'])
            ->latest();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('brand', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $products   = $query->paginate(20)->withQueryString();
        $categories = Category::orderBy('sort_order')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product): View
    {
        $product->load(['user', 'category', 'images', 'comments.user']);

        $isFavorited = Auth::check()
            ? $product->favorites()->where('user_id', Auth::id())->exists()
            : false;

        return view('products.show', compact('product', 'isFavorited'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('sort_order')->get();
        $conditions = Product::CONDITIONS;

        return view('products.create', compact('categories', 'conditions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'price'       => ['required', 'integer', 'min:300', 'max:9999999'],
            'category_id' => ['required', 'exists:categories,id'],
            'condition'   => ['required', 'in:' . implode(',', array_keys(Product::CONDITIONS))],
            'brand'       => ['nullable', 'string', 'max:100'],
            'size'        => ['nullable', 'string', 'max:20'],
            'color'       => ['nullable', 'string', 'max:30'],
            'images'      => ['required', 'array', 'min:1', 'max:5'],
            'images.*'    => ['image', 'mimes:jpeg,png,webp', 'max:5120'],
            'status'      => ['required', 'in:draft,on_sale'],
        ]);

        $product = Auth::user()->products()->create($validated);

        foreach ($request->file('images', []) as $index => $image) {
            $path = $image->store('products', 'public');
            $product->images()->create([
                'image_path' => $path,
                'sort_order' => $index,
            ]);
        }

        return redirect()->route('products.show', $product)->with('success', '商品を出品しました。');
    }

    public function edit(Product $product): View
    {
        $this->authorize('update', $product);

        $categories = Category::orderBy('sort_order')->get();
        $conditions = Product::CONDITIONS;

        return view('products.edit', compact('product', 'categories', 'conditions'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'price'       => ['required', 'integer', 'min:300', 'max:9999999'],
            'category_id' => ['required', 'exists:categories,id'],
            'condition'   => ['required', 'in:' . implode(',', array_keys(Product::CONDITIONS))],
            'brand'       => ['nullable', 'string', 'max:100'],
            'size'        => ['nullable', 'string', 'max:20'],
            'color'       => ['nullable', 'string', 'max:30'],
            'status'      => ['required', 'in:draft,on_sale,suspended'],
        ]);

        $product->update($validated);

        return redirect()->route('products.show', $product)->with('success', '商品情報を更新しました。');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);

        $product->update(['status' => 'suspended']);

        return redirect()->route('mypage.selling')->with('success', '商品を削除しました。');
    }
}
