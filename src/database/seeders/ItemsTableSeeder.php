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
                'category_id' => 5,
                'condition_id' => 1,
                'name' => '腕時計',
                'brand' => 'Armani',
                'image_path' => 'storage/images/Armani+Mens+Clock.jpg',
                'detail' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
            ],
            [
                'category_id' => 2,
                'condition_id' => 2,
                'name' => 'HDD',
                'brand' => 'Buffalo',
                'image_path' => 'storage/images/HDD+Hard+Disk.jpg',
                'detail' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
            ],
            [
                'category_id' => 10,
                'condition_id' => 3,
                'name' => '玉ねぎ3束',
                'brand' => '宮崎県産',
                'image_path' => 'storage/images/iLoveIMG+d.jpg',
                'detail' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
            ],
            [
                'category_id' => 1,
                'condition_id' => 4,
                'name' => '革靴',
                'brand' => 'AEON',
                'image_path' => 'storage/images/Leather+Shoes+Product+Photo.jpg',
                'detail' => 'クラシックなデザインの革靴',
                'price' => 4000,
            ],
            [
                'category_id' => 2,
                'condition_id' => 1,
                'name' => 'ノートPC',
                'brand' => 'DELL',
                'image_path' => 'storage/images/Living+Room+Laptop.jpg',
                'detail' => '高性能なノートパソコン',
                'price' => 45000,
            ],
            [
                'category_id' => 2,
                'condition_id' => 2,
                'name' => 'マイク',
                'brand' => 'MAXIM',
                'image_path' => 'storage/images/Music+Mic+4632231.jpg',
                'detail' => '高音質のレコーディング用マイク',
                'price' => 8000,
            ],
            [
                'category_id' => 1,
                'condition_id' => 3,
                'name' => 'ショルダーバッグ',
                'brand' => 'AEON',
                'image_path' => 'storage/images/Purse+fashion+pocket.jpg',
                'detail' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
            ],
            [
                'category_id' => 10,
                'condition_id' => 4,
                'name' => 'タンブラー',
                'brand' => 'ニトリ',
                'image_path' => 'storage/images/Tumbler+souvenir.jpg',
                'detail' => '使いやすいタンブラー',
                'price' => 500,
            ],
            [
                'category_id' => 10,
                'condition_id' => 1,
                'name' => 'コーヒーミル',
                'brand' => 'カリタ',
                'image_path' => 'storage/images/Waitress+with+Coffee+Grinder.jpg',
                'detail' => '手動のコーヒーミル',
                'price' => 4000,
            ],
            [
                'category_id' => 6,
                'condition_id' => 2,
                'name' => 'メイクセット',
                'brand' => 'DHC',
                'image_path' => 'storage/images/外出メイクアップセット.jpg',
                'detail' => '便利なメイクアップセット',
                'price' => 2500,
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
