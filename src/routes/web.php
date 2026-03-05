<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TradeMessageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddressController;
use Illuminate\Support\Facades\Route;

// トップ
Route::get('/', [ProductController::class, 'index'])->name('top');

// 認証（ゲストのみ）
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

// 商品：create を {product} より先に定義
Route::get('/products/create', [ProductController::class, 'create'])->middleware('auth')->name('products.create');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// 認証必須
Route::middleware('auth')->group(function () {
    // 商品
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // お気に入り
    Route::post('/favorites/{product}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{product}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    // コメント
    Route::post('/products/{product}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // 購入・取引
    Route::get('/products/{product}/purchase', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/products/{product}/purchase', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/ship', [OrderController::class, 'ship'])->name('orders.ship');
    Route::patch('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');

    // 取引メッセージ
    Route::post('/orders/{order}/messages', [TradeMessageController::class, 'store'])->name('trade-messages.store');

    // マイページ
    Route::get('/mypage', [UserController::class, 'show'])->name('mypage');
    Route::get('/mypage/edit', [UserController::class, 'edit'])->name('mypage.edit');
    Route::put('/mypage', [UserController::class, 'update'])->name('mypage.update');
    Route::get('/mypage/selling', [UserController::class, 'selling'])->name('mypage.selling');
    Route::get('/mypage/buying', [UserController::class, 'buying'])->name('mypage.buying');
    Route::get('/mypage/favorites', [UserController::class, 'favorites'])->name('mypage.favorites');

    // 住所
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
});
