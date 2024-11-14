<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Item;


class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'category_id' => [1, 5],
                'name' => '腕時計',
                'brand' => 'Armani',
                'detail' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_path' => 'storage/images/Armani+Mens+Clock.jpg',
                'price' => 15000,
                'color' => '文字盤黒',
                'condition' => '良好',
                'status' => '新品',
                'status_comment' => '商品の状態は良好です。傷もありません。',
            ],
            [
                'category_id' => [2, 8],
                'name' => 'HDD',
                'brand' => 'Buffalo',
                'detail' => '高速で信頼性の高いハードディスク',
                'image_path' => 'storage/images/HDD+Hard+Disk.jpg',
                'price' => 5000,
                'color' => '黒',
                'condition' => '目立った傷や汚れなし',
                'status' => '新品',
                'status_comment' => '商品の状態は良好です。目立った傷や汚れもありません。',
            ],
            [
                'category_id' => 10,
                'name' => '玉ねぎ3束',
                'brand' => '宮崎県産',
                'detail' => '新鮮な玉ねぎ3束のセット',
                'image_path' => 'storage/images/iLoveIMG+d.jpg',
                'price' => 300,
                'condition' => 'やや傷や汚れあり',
                'status' => '新品',
                'status_comment' => '商品の状態はやや良好です。少しの傷や汚れがあります。',
            ],
            [
                'category_id' => [1, 5],
                'name' => '革靴',
                'brand' => 'AEON',
                'detail' => 'クラシックなデザインの革靴',
                'image_path' => 'storage/images/Leather+Shoes+Product+Photo.jpg',
                'price' => 4000,
                'color' => '黒',
                'condition' => '状態が悪い',
                'status' => '中古品',
                'status_comment' => '商品の状態は悪いです。傷や汚れがあります。',
            ],
            [
                'category_id' => 2,
                'name' => 'ノートPC',
                'brand' => 'DELL',
                'detail' => '高性能なノートパソコン',
                'image_path' => 'storage/images/Living+Room+Laptop.jpg',
                'price' => 45000,
                'color' => 'シルバー',
                'condition' => '良好',
                'status' => '新品',
                'status_comment' => '商品の状態は良好です。傷もありません。',
            ],
            [
                'category_id' => 2,
                'name' => 'マイク',
                'brand' => 'MAXIM',
                'detail' => '高音質のレコーディング用マイク',
                'image_path' => 'storage/images/Music+Mic+4632231.jpg',
                'price' => 8000,
                'color' => '黒',
                'condition' => '目立った傷や汚れなし',
                'status' => '新品',
                'status_comment' => '商品の状態は良好です。目立った傷や汚れもありません。',
            ],
            [
                'category_id' => [1, 4],
                'name' => 'ショルダーバッグ',
                'brand' => 'AEON',
                'detail' => 'おしゃれなショルダーバッグ',
                'image_path' => 'storage/images/Purse+fashion+pocket.jpg',
                'price' => 3500,
                'color' => '赤',
                'condition' => 'やや傷や汚れあり',
                'status' => '中古品',
                'status_comment' => '商品の状態はやや良好です。少しの傷や汚れがあります。',
            ],
            [
                'category_id' => [4, 5, 10],
                'name' => 'タンブラー',
                'brand' => 'ニトリ',
                'detail' => '使いやすいタンブラー',
                'image_path' => 'storage/images/Tumbler+souvenir.jpg',
                'price' => 500,
                'color' => '黒',
                'condition' => '状態が悪い',
                'status' => '中古品',
                'status_comment' => '商品の状態は悪いです。傷や汚れがあります。',

            ],
            [
                'category_id' => [2, 10],
                'name' => 'コーヒーミル',
                'brand' => 'カリタ',
                'detail' => '手動のコーヒーミル',
                'image_path' => 'storage/images/Waitress+with+Coffee+Grinder.jpg',
                'price' => 4000,
                'color' => 'ブラウン',
                'condition' => '良好',
                'status' => '新品',
                'status_comment' => '商品の状態は良好です。傷もありません。',
            ],
            [
                'category_id' => [4, 6],
                'name' => 'メイクセット',
                'brand' => 'DHC',
                'detail' => '便利なメイクアップセット',
                'image_path' => 'storage/images/外出メイクアップセット.jpg',
                'price' => 2500,
                'condition' => '目立った傷や汚れなし',
                'status' => '新品',
                'status_comment' => '商品の状態は良好です。目立った傷や汚れもありません。',
            ],
        ];

        foreach ($items as $itemData) {
        // `category_id`を一時的に保存し、削除
        $categoryIds = $itemData['category_id'];
        unset($itemData['category_id']);

        // アイテムを作成
        $item = Item::create($itemData);

        // カテゴリを紐づけ
        $item->categories()->attach($categoryIds);
    }
    }
}
