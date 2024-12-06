<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    //ログインページ表示テスト
    public function test_login_page_is_access()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    //メールアドレスの入力必須テスト
    public function test_email_is_required()
    {
        $response = $this->post('/login', [
            'password' => 'password',
            'email' => ''
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    //パスワードの入力必須テスト
    public function test_password_is_required()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    //ログイン情報が一致しない場合
    public function test_mismatch_of_login()
    {
        $response = $this->post('/login', [
            'email' => 'no-reply@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['login_error' => 'ログイン情報が登録されていません。']);
    }

    //ログイン処理テスト
    public function test_login()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // カスタムリダイレクト先を確認
        $response->assertRedirect('/mypage/profile');

        // 認証されていることを確認
        $this->assertAuthenticatedAs($user);
    }
}
