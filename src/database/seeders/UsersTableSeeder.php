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
                'password' => bcrypt('password'),
                'first_login' => false,
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 12,
                'name' => 'ユーザー2',
                'email' => 'test2@example.com',
                'password' => bcrypt('password'),
                'first_login' => false,
                'remember_token' => Str::random(10),
            ],
            [
                'id' => 13,
                'name' => 'ユーザー3',
                'email' => 'test3@example.com',
                'password' => bcrypt('password'),
                'first_login' => false,
                'remember_token' => Str::random(10),
            ],
        ]);
    }
}
