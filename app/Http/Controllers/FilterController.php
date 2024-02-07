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

        $characters = Character::convertCharactersStrToArray($request->characters ?? null);

        $fanfics = Fanfiction::whereIn('age_rating_id', $request->age_rating ?? AgeRating::all()->pluck('id'))
            ->whereIn('category_id', $request->category ?? Category::all()->pluck('id'))
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
            'ageRatings' => AgeRating::all(),

            // Усі категорії
            'categories' => Category::all(),

            // Усі фандоми, відсортировані по популярності
            'fandoms' => Fandom::orderBy('fictions_amount', 'desc')->get(),

            // Усі теґі
            'tags' => Tag::all(),

            // Усі користувачі, відсортировані по фандомам
            'characters' => Character::orderBy('belonging_to_fandom_id')->get(),

            // Усі знайдені по фільтру фанфіки,
            // якщо користувач здійснив пошук
            'fanfics' => $fanfics ?? null
        ];

        return view('filter-page', $data);
    }

}
