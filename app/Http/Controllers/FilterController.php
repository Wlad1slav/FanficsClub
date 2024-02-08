<?php

namespace App\Http\Controllers;

use App\Models\AgeRating;
use App\Models\Category;
use App\Models\Character;
use App\Models\Fandom;
use App\Models\Fanfiction;
use App\Models\Tag;
use App\Rules\AgeRatingExists;
use App\Rules\CategoryExists;
use App\Rules\FandomsExists;
use App\Rules\TagsExists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FilterController extends Controller
{

    public function index(Request $request)
    {

        $request->validate([
            'fandoms_selected' => [new FandomsExists()],
            'age_rating' => [new AgeRatingExists()],
            'category' => [new CategoryExists()],
            'tags_selected' => [new TagsExists()],
        ]);

        // Отримання Laravel колекції з id категорій і вікових рейтингів
        // Колекції підтягуються з кешу, що зберігається тиждень
        $standardCategories = Cache::remember("categories_all_id", 60*60*168, function () {
            $categories = Cache::remember("categories_all", 60*60*188, function () {
                return Category::all();
            });
            return $categories->pluck('id');
        });

        $standardAgeRatings = Cache::remember("age_ratings_all_id", 60*60*168, function () {
            $ageRatings = Cache::remember("age_ratings_all", 60*60*168, function () {
                return AgeRating::all();
            });
            return $ageRatings->pluck('id');
        });

        $characters = Character::convertCharactersStrToArray($request->characters ?? null);

        $fanfics = Fanfiction::whereIn('age_rating_id', $request->age_rating ?? $standardAgeRatings)
            ->whereIn('category_id', $request->category ?? $standardCategories)
            ->whereJsonContains('characters->characters', $characters['characters'] ?? [])
            ->whereJsonContains('characters->parings', $characters['parings'] ?? [])
            ->whereJsonContains('tags', Tag::convertStrAttrToArray($request->tags_selected ?? null))
            ->whereJsonContains('fandoms_id', Fandom::convertStrAttrToArray($request->fandoms_selected ?? null))
            ->orderBy($request->sort_by ?? 'updated_at', 'desc')
            ->paginate(30);

        DB::table('filter_requests')->insert([
            'made_request' => Auth::user()->id ?? null,
            'request' => json_encode($request->all()),
        ]);

        $data = [
            'title' => 'Знайти фанфік',
            'metaDescription' => null,
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

            // Усі знайдені по фільтру фанфіки,
            // якщо користувач здійснив пошук
            'fanfics' => $fanfics ?? null
        ];

        return view('fanfic.filter-page', $data);
    }

}
