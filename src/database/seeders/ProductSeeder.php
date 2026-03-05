<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = User::where('email', 'test@example.com')->first();
        $user2 = User::where('email', 'user2@example.com')->first();

        $tops       = Category::where('slug', 'tops')->first();
        $bottoms    = Category::where('slug', 'bottoms')->first();
        $outer      = Category::where('slug', 'outer')->first();
        $swimwear   = Category::where('slug', 'swimwear')->first();
        $accessories = Category::where('slug', 'accessories')->first();

        $products = [
            [
                'user_id'     => $user1->id,
                'category_id' => $tops->id,
                'name'        => 'ロンハーマン ボーダーカットソー',
                'description' => "湘南の海辺で着ていたお気に入りの一枚です。\n状態は良好で、目立った傷や汚れはありません。\nサイズ感はやや大きめです。",
                'price'       => 4800,
                'brand'       => 'Ron Herman',
                'size'        => 'M',
                'color'       => 'ネイビー×ホワイト',
                'condition'   => 'good',
                'status'      => 'on_sale',
            ],
            [
                'user_id'     => $user1->id,
                'category_id' => $bottoms->id,
                'name'        => 'ビラボン サーフショーツ',
                'description' => "2シーズン着用しました。ウエスト部分に小さなほつれがありますが、使用上は問題ありません。\nビーチ・プールOKのサーフショーツです。",
                'price'       => 2200,
                'brand'       => 'Billabong',
                'size'        => 'L',
                'color'       => 'ブルー',
                'condition'   => 'fair',
                'status'      => 'on_sale',
            ],
            [
                'user_id'     => $user2->id,
                'category_id' => $outer->id,
                'name'        => 'パタゴニア フリースジャケット',
                'description' => "1回着用のみのほぼ新品です。\n朝夕の海辺でちょうどよい厚みです。\n定価より大幅値下げで出品します！",
                'price'       => 12000,
                'brand'       => 'Patagonia',
                'size'        => 'S',
                'color'       => 'サンドベージュ',
                'condition'   => 'like_new',
                'status'      => 'on_sale',
            ],
            [
                'user_id'     => $user2->id,
                'category_id' => $swimwear->id,
                'name'        => 'クイックシルバー ラッシュガード 長袖',
                'description' => "UV対策にぴったりなラッシュガードです。\n着用回数少なく、まだまだ現役で使えます。",
                'price'       => 3500,
                'brand'       => 'Quiksilver',
                'size'        => 'M',
                'color'       => 'ホワイト',
                'condition'   => 'good',
                'status'      => 'on_sale',
            ],
            [
                'user_id'     => $user1->id,
                'category_id' => $accessories->id,
                'name'        => 'カスタム シェルネックレス ハンドメイド',
                'description' => "海で拾ったシェルで手作りしたネックレスです。\n世界に一つだけの一品です。\n新品・未使用です。",
                'price'       => 1500,
                'brand'       => null,
                'size'        => 'フリー',
                'color'       => 'ナチュラル',
                'condition'   => 'new',
                'status'      => 'on_sale',
            ],
            [
                'user_id'     => $user2->id,
                'category_id' => $tops->id,
                'name'        => 'サンシェード グラフィックTシャツ',
                'description' => "夏の海辺で活躍するTシャツです。\nプリントも綺麗な状態です。",
                'price'       => 2800,
                'brand'       => 'Sun Shade',
                'size'        => 'L',
                'color'       => 'ホワイト',
                'condition'   => 'like_new',
                'status'      => 'on_sale',
            ],
        ];

        foreach ($products as $data) {
            Product::firstOrCreate(
                ['name' => $data['name'], 'user_id' => $data['user_id']],
                $data
            );
        }
    }
}
