<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{

    public function profileInfo()
    {   // LoginPage
        // Сторінка з формою авторизації користувача

        $data = [
            'navigation' => require_once 'navigation.php',
        ];

        return view('profile.profile-info', $data);
    }

    public function avatarUpload(Request $request)
    {

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:1024',
        ]);

        if ($request->hasFile('avatar')) {
            // Розширення аватарки
            $extension = $request->file('avatar')->getClientOriginalExtension();

            // Генерація назви для фалу автарки
            // в форматі дата_час_індефекатор.розширення (2024-02-05_08-39-09_1707122349)
            $fileNameToStore= date('Y-m-d_H-i-s').'_'.time().'.'.$extension;
            $request->file('avatar')->storeAs('public/avatars', $fileNameToStore);

            User::where('id', Auth::user()->id)->update(['image' => $fileNameToStore]);

            return redirect()->back();
        }

        return redirect()->back()->with('error', 'Помилка завантаження зображення.');
    }

}
