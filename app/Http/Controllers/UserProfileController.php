<?php

namespace App\Http\Controllers;

use App\Models\AgeRating;
use App\Models\Category;
use App\Models\Character;
use App\Models\Fandom;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{

    public function profileInfo()
    {   // MyProfilePage
        // Сторінка з інформацією про користувача і можливістю її змінити

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

    public function fanficCreate()
    {   // FanficCreatePage
        // Сторінка з формую для створення фанфіка

        $data = [
            'navigation' => require_once 'navigation.php',

            // Усі вікові рейтинги
            'ageRatings' => AgeRating::all(),

            // Усі категорії
            'categories' => Category::all(),

            // Усі фандоми, відсортировані по популярності
            'fandoms' => Fandom::orderBy('fictions_amount', 'desc')->get(),

            // Усі теґі
            'tags' => Tag::all(),

            // Усі користувачі, відсортировані по фандомам
            'characters' => Character::orderBy('belonging_to_fandom_id')->get(),
        ];

        return view('profile.create-fanfic', $data);

    }

}
