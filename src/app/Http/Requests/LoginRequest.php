<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required | email',
            'password' => 'required | string | min:8',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => '',
            'password.required' => 'パスワードを入力してください',
            'password.min' => '',
        ];
    }

    // バリデーションに失敗した場合にカスタムメッセージを表示
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->fails()) {
                // メールアドレスまたはパスワード誤っている場合に、特定のメッセージを表示
                $validator->errors()->add('login_error', 'ログイン情報が登録されていません。');
            }
        });
    }
}
