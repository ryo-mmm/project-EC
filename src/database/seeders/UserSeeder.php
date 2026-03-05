<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 開発用テストユーザー
        $users = [
            [
                'name'         => '海太郎',
                'email'        => 'test@example.com',
                'password'     => Hash::make('password'),
                'profile_text' => '湘南在住。サーフィン歴10年。不要になった海系アパレルを出品します！',
                'rating'       => 4.8,
            ],
            [
                'name'         => '波子',
                'email'        => 'user2@example.com',
                'password'     => Hash::make('password'),
                'profile_text' => '海が大好きなファッション好きです。',
                'rating'       => 4.5,
            ],
        ];

        foreach ($users as $data) {
            User::firstOrCreate(['email' => $data['email']], $data);
        }
    }
}
