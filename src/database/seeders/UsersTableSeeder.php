<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(10)->create();

        DB::table('users')->insert([
            [
                'id' => 11,
                'name' => 'ユーザー1',
                'email' => 'test1@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'post_code' => '123-456',
                'address' => '東京都渋谷区宇田川町8-9',
                'building' => null,
                'image_path' => 'storage/images/くじら.jpg',
                'first_login' => false,
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 12,
                'name' => 'ユーザー2',
                'email' => 'test2@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'post_code' => '321-654',
                'address' => '神奈川県横浜市西区6-1',
                'building' => '葉山ハイツ203',
                'image_path' => 'storage/images/いちご.jpg',
                'first_login' => false,
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 13,
                'name' => 'ユーザー3',
                'email' => 'test3@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'post_code' => '789-012',
                'address' => '兵庫県神戸市長田区13-2',
                'building' => '岩村コーポ305',
                'image_path' => 'storage/images/ポニー.jpg',
                'first_login' => false,
                'remember_token' => Str::random(10),
            ],
        ]);
    }
}
