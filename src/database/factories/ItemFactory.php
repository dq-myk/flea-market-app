<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'brand' => $this->faker->company(),
            'detail' => $this->faker->sentence(),
            'image_path' => $this->faker->randomElement([
                'storage/images/Armani+Mens+Clock.jpg',
                'storage/images/HDD+Hard+Disk.jpg',
                'storage/images/iLoveIMG+d.jpg',
                'storage/images/Leather+Shoes+Product+Photo.jpg',
                'storage/images/Living+Room+Laptop.jpg',
                'storage/images/Music+Mic+4632231.jpg',
                'storage/images/Purse+fashion+pocket.jpg',
                'storage/images/Tumbler+souvenir.jpg',
                'storage/images/Waitress+with+Coffee+Grinder.jpg',
                'storage/images/外出メイクアップセット.jpg',
            ]),
            'price' => $this->faker->numberBetween(1000, 50000),
            'color' => $this->faker->safeColorName(),
            'condition' => $this->faker->randomElement(['良好', '目立った傷や汚れなし', 'やや傷や汚れあり', '状態が悪い']),
            'status' => $this->faker->randomElement(['新品', '中古品']),
            'status_comment' => $this->faker->sentence(),
        ];
    }
}