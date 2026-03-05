<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'トップス',       'slug' => 'tops'],
            ['name' => 'ボトムス',       'slug' => 'bottoms'],
            ['name' => 'アウター',       'slug' => 'outer'],
            ['name' => '水着・ラッシュ', 'slug' => 'swimwear'],
            ['name' => 'シューズ',       'slug' => 'shoes'],
            ['name' => 'バッグ',         'slug' => 'bags'],
            ['name' => 'アクセサリー',   'slug' => 'accessories'],
            ['name' => 'サーフグッズ',   'slug' => 'surf'],
        ];

        foreach ($categories as $i => $cat) {
            Category::firstOrCreate(
                ['slug' => $cat['slug']],
                ['name' => $cat['name'], 'sort_order' => $i + 1]
            );
        }
    }
}
