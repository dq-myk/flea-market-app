<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConditionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contents = [
            "良好",
            "目立った傷や汚れ無し",
            "やや傷や汚れあり",
            "状態が悪い"
        ];

        foreach ($contents as $content) {
            DB::table('conditions')->insert([
                'content' => $content,
            ]);
        }
    }
}
