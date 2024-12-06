<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    //会員登録ページ表示テスト
    public function test_register_page_is_access()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    //名前の入力必須テスト
    public function test_name_is_required()
    {
        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'name' => ''
        ]);

        $response->assertSessionHasErrors(['name' => 'お名前を入力してください']);
    }

    //メールアドレスの入力必須テスト
    public function test_email_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'password' => 'password',
            'password_confirmation' => 'password',
            'email' => ''
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    //パスワードの入力必須テスト
    public function test_password_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    //パスワードを7文字以下で入力した場合
    public function test_password_min_length()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
    }

    //パスワードが一致しない場合
    public function test_password_confirmation()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'different_password',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードと一致しません']);
    }

    //会員登録が完了後、ログイン画面への遷移確認
    public function test_user_create_to_login()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue(Hash::check('password123', $user->password));

        $response->assertRedirect(route('login'));
    }
}
