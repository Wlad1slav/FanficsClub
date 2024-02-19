<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{

    public function forgotPasswordPage()
    {   // ForgotPasswordPage
        // Сторінка з відновленням поролю

        $data = [
            'title' => 'Забули пароль?',
            'metaDescription' => null,
            'navigation' => require_once 'navigation.php',
        ];

        return view('auth.forgot-password', $data);
    }

    public function forgotPassword(Request $request)
    {   // ForgotPasswordAction

        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT)
            return back()->with('status', trans($status));

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => trans($status)]);
    }

    public function create(Request $request)
    {

        $data = [
            'title' => 'Змінити пароль',
            'metaDescription' => null,
            'navigation' => require_once 'navigation.php',
            'request' => $request
        ];

        return view('auth.reset-password', $data);
    }

    public function store(Request $request)
    {

        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET)
            return redirect()->route('LoginPage')->with('status', trans($status));

        return back();

    }
}
