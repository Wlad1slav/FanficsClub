<?php

namespace App\Http\Controllers;

use App\Models\AgeRating;
use App\Models\Category;
use App\Models\Character;
use App\Models\Fandom;
use App\Models\Fanfiction;
use App\Models\Subscribe;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserProfileController extends Controller
{

    public function profileInfo()
    {   // MyProfilePage
        // Сторінка з інформацією про користувача і можливістю її змінити

        $data = [
            'navigation' => require_once 'navigation.php',
        ];

        return view('profile.info', $data);
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
            'ageRatings' => Cache::remember("age_ratings_all", 60*60*168, function () {
                return AgeRating::all();
            }),

            // Усі категорії
            'categories' => Cache::remember("categories_all", 60*60*168, function () {
                return Category::all();
            }),

            // Усі фандоми, відсортировані по популярності
            'fandoms' => Cache::remember("fandoms_all", 60*60*12, function () {
                return Fandom::orderBy('fictions_amount', 'desc')->get();
            }),

            // Усі теґі
            'tags' => Cache::remember("tags_all", 60*60*24, function () {
                return Tag::all();
            }),

            // Усі користувачі, відсортировані по фандомам
            'characters' => Cache::remember("characters_all", 60*60*24, function () {
                return Character::orderBy('belonging_to_fandom_id')->get();
            }),
        ];

        return view('profile.fanfic-create', $data);

    }

    public function fanficsList()
    {   // FanficListPage
        // Сторінка з усіма фанфіками, що належать користувачу

        $data = [
            'navigation' => require_once 'navigation.php',
            'fanfics' => Fanfiction::where('author_id', Auth::user()->id)->get()
        ];

        return view('profile.fanfic-list', $data);
    }

    public function subscribes()
    {   // SubscribesListPage
        // Сторінка з усіма підписами користувча

        $user = Auth::user();

        // Усі підписки авторизованого користувача
        $subscribes = Cache::remember("subscribes_$user->id", 60*60*24, function () use ($user) {
            return Subscribe::where('user_id', $user->id)->get();
        });

        $data = [
            'navigation' => require_once 'navigation.php',
            'subscribes' => $subscribes
        ];

        return view('profile.subscribes', $data);
    }

    public function access()
    {   // AccessFanficsListPage
        // Сторінка з усіма фанфіками, до яких користувач має доступ

        $user = Auth::user();

        $editorAccess = Cache::remember("access_editor_$user->id", 60*60*24, function () use ($user) {
            return Fanfiction::whereJsonContains("users_with_access->$user->id", 'editor')->get();
        });

        $coauthorAccess = Cache::remember("access_coauthor_$user->id", 60*60*24, function () use ($user) {
            return Fanfiction::whereJsonContains("users_with_access->$user->id", 'coauthor')->get();
        });

        $data = [
            'navigation' => require_once 'navigation.php',
            'editorAccess' => $editorAccess,
            'coauthorAccess' => $coauthorAccess,
        ];

        return view('profile.fanfics-with-access', $data);
    }

}
