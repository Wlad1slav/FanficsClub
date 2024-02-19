<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
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
            'email' => 'required|string|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'ip' => $request->ip()
        ]);

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);

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

}
