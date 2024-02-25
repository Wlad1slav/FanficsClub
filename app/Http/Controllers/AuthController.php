<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function registrationPage()
    {   // RegistrationPage
        // Сторінка з формою реєстрації користувача

        $data = [
            'title' => 'Реєстрація',
            'metaDescription' => null,
            'navigation' => require_once 'navigation.php',
        ];

        return view('auth.registration', $data);
    }

    public function registration(Request $request)
    {   // RegistrationAction - post
        // Виконання реєстрації, збереження користувача в базі даних

        $request->validate([
            'name' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'ip' => $request->ip()
        ]);

        // event(new Registered($user));

        $user->sendEmailVerificationNotification();

        Auth::login($user);

        return redirect()->route('verification.notice');

    }

    public function loginPage()
    {   // LoginPage
        // Сторінка з формою авторизації користувача

        $data = [
            'title' => 'Авторизація',
            'metaDescription' => null,
            'navigation' => require_once 'navigation.php',
        ];

        return view('auth.login', $data);
    }

    public function login(Request $request)
    {   // LoginAction - post
        // Виконання авторизації користувача

        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            // Якщо не виходить авторизувати користувача,
            // то він повертається на сторінку авторизації
            return back()
                ->withInput()
                ->withErrors(['error' => 'Пароль чи пошта введені неверно.']);
        }

        $request->session()->regenerate(); // Оновлення сесії користувача

        return redirect(RouteServiceProvider::HOME);
    }

    public function logout()
    {   // LogoutAction - get
        // Виход користувача з акаунту

        Auth::logout();

        return redirect()->route('LoginPage');
    }

    public function verifyNote()
    {   // verification.notice
        // Сторінка, яка повідомляє про те,
        // що користувачу на пошту було вислане підтвердження для входу

        $data = [
            'title' => 'Будь ласка, підтвердіть пошту',
            'metaDescription' => null,
            'navigation' => require_once 'navigation.php',
            'user' => Auth::user()
        ];

        return view('auth.verify', $data);

    }

    public function verify(EmailVerificationRequest $request)
    {   // verification.verify
        // Логіка підтвердження пошти

        $request->fulfill();

        return redirect()->intended(RouteServiceProvider::HOME);

    }

    public function resend(Request $request)
    {   // Повторене надсилання листу в випадку, якщо користувач не підтвердив свою пошту
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home')->with('message', 'Ваша електронна адреса вже підтверджена.');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('resent', true)->with('message', 'Посилання для підтвердження пошти було надіслано.');
    }

}
