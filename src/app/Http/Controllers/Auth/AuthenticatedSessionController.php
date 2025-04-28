<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request)
{
    $user = User::where('email', $request->email)
                ->orWhere('name', $request->email)
                ->first();

    if (!$user || !\Hash::check($request->password, $user->password)) {
        return back()->withErrors([
            'login_error' => 'ログイン情報が登録されていません。',
        ]);
    }

    Auth::login($user);

    $request->session()->regenerate();

    $user = Auth::user();

    if ($user->first_login) {
        $user->update(['first_login' => false]);

        return redirect('/mypage/profile');
    }

    if (!$user->hasVerifiedEmail()) {
        return redirect('/email/verify');
    }

    return redirect()->intended('/');
}

}
