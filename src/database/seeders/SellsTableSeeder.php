<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Sell;
use App\Models\User;
use App\Models\Transaction;

class SellsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::find(11);
        $user2 = User::find(12);
        $user3 = User::find(13);

        $categoryIdsUser1 = [
            [1, 5],
            [2, 8],
            [10],
            [1, 5],
            [2],
        ];

        $imagePathsUser1 = [
            'storage/images/Armani+Mens+Clock.jpg',
            'storage/images/HDD+Hard+Disk.jpg',
            'storage/images/iLoveIMG+d.jpg',
            'storage/images/Leather+Shoes+Product+Photo.jpg',
            'storage/images/Living+Room+Laptop.jpg',
        ];

        $categoryIdsUser2 = [
            [2],
            [1, 4],
            [4, 5, 10],
            [2, 10],
            [4, 6],
        ];

        $imagePathsUser2 = [
            'storage/images/Music+Mic+4632231.jpg',
            'storage/images/Purse+fashion+pocket.jpg',
            'storage/images/Tumbler+souvenir.jpg',
            'storage/images/Waitress+with+Coffee+Grinder.jpg',
            'storage/images/外出メイクアップセット.jpg',
        ];

        $itemsUser1 = [
            [
                'name' => '腕時計',
                'brand' => 'Armani',
                'detail' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'color' => '文字盤黒',
                'condition' => '良好',
                'status' => '新品',
                'status_comment' => '商品の状態は良好です。傷もありません。',
            ],
            [
                'name' => 'HDD',
                'brand' => 'Buffalo',
                'detail' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'color' => '黒',
                'condition' => '目立った傷や汚れなし',
                'status' => '新品',
                'status_comment' => '商品の状態は良好です。目立った傷や汚れもありません。',
            ],
            [
                'name' => '玉ねぎ3束',
                'brand' => '宮崎県産',
                'detail' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
                'color' => '白',
                'condition' => 'やや傷や汚れあり',
                'status' => '新品',
                'status_comment' => '商品の状態はやや良好です。少しの傷や汚れがあります。',
            ],
            [
                'name' => '革靴',
                'brand' => 'AEON',
                'detail' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'color' => '黒',
                'condition' => '状態が悪い',
                'status' => '中古品',
                'status_comment' => '商品の状態は悪いです。傷や汚れがあります。',
            ],
            [
                'name' => 'ノートPC',
                'brand' => 'DELL',
                'detail' => '高性能なノートパソコン',
                'price' => 45000,
                'color' => 'シルバー',
                'condition' => '良好',
                'status' => '新品',
                'status_comment' => '商品の状態は良好です。傷もありません。',
            ],
        ];


        $itemsUser2 =[
            [
                'name' => 'マイク',
                'brand' => 'MAXIM',
                'detail' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'color' => '黒',
                'condition' => '目立った傷や汚れなし',
                'status' => '新品',
                'status_comment' => '商品の状態は良好です。目立った傷や汚れもありません。',
            ],
            [
                'name' => 'ショルダーバッグ',
                'brand' => 'AEON',
                'detail' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'color' => '赤',
                'condition' => 'やや傷や汚れあり',
                'status' => '中古品',
                'status_comment' => '商品の状態はやや良好です。少しの傷や汚れがあります。',
            ],
            [
                'name' => 'タンブラー',
                'brand' => 'ニトリ',
                'detail' => '使いやすいタンブラー',
                'price' => 500,
                'color' => '黒',
                'condition' => '状態が悪い',
                'status' => '中古品',
                'status_comment' => '商品の状態は悪いです。傷や汚れがあります。',
            ],
            [
                'name' => 'コーヒーミル',
                'brand' => 'カリタ',
                'detail' => '手動のコーヒーミル',
                'price' => 4000,
                'color' => 'ブラウン',
                'condition' => '良好',
                'status' => '新品',
                'status_comment' => '商品の状態は良好です。傷もありません。',
            ],
            [
                'name' => 'メイクセット',
                'brand' => 'DHC',
                'detail' => '便利なメイクアップセット',
                'price' => 2500,
                'color' => 'ブラウン',
                'condition' => '目立った傷や汚れなし',
                'status' => '新品',
                'status_comment' => '商品の状態は良好です。目立った傷や汚れもありません。',
            ],
        ];

        for ($i = 0; $i < 5; $i++) {
            $item = Item::create(array_merge($itemsUser1[$i], [
                'image_path' => $imagePathsUser1[$i]
            ]));

            $item->categories()->sync($categoryIdsUser1[$i]);

            $sell = Sell::create([
                'item_id' => $item->id,
                'user_id' => $user1->id,
                'price' => $item->price,
            ]);
        }

        for ($i = 0; $i < 5; $i++) {
            $item = Item::create(array_merge($itemsUser2[$i], [
                'image_path' => $imagePathsUser2[$i]
            ]));

            $item->categories()->sync($categoryIdsUser2[$i]);

            $sell = Sell::create([
                'item_id' => $item->id,
                'user_id' => $user2->id,
                'price' => $item->price,
            ]);
        }
    }
}