<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_logout()
    {
        // ログインするユーザーを作成
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        // ユーザーをログイン状態にする
        $this->actingAs($user);

        // ログアウト処理を実行
        $response = $this->post('/logout');

        // ログアウト後、ホーム画面にリダイレクトされることを確認
        $response->assertRedirect('/');

        // ユーザーが認証されていないことを確認
        $this->assertGuest();
    }
}
