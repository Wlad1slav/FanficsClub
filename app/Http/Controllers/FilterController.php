<?php

namespace App\Http\Controllers;

use App\Models\AgeRating;
use App\Models\Category;
use App\Models\Character;
use App\Models\Fandom;
use App\Models\Fanfiction;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class FilterController extends Controller
{

    public function index()
    {

        if (isset($_GET['fandoms-selected'])) {
            $ageRating = $_GET['age-rating'] ?? [1, 2, 3, 4, 5];
            $category = $_GET['category'] ?? [1, 2, 3, 4];
            $sortBy = $_GET['sort-by'] ?? 'updated_at';

            // Отримання тегів і фандомів
            $tagsSelected = array_filter(preg_split('/,\s?/', $_GET['tags-selected'] ?? ''));
            $fandomsSelected = array_filter(preg_split('/,\s?/', $_GET['fandoms-selected']));

            // Отримання персонажів і пейренгів
            $charactersSelected = array_filter(preg_split('/,\s?/', $_GET['characters'] ?? ''));
            $paringsSelected = [];
            foreach ($charactersSelected as $character)
                if (strpos($character, '/'))
                    $paringsSelected[] = explode('/', $character);

            $tagsIds = Tag::whereIn('name', $tagsSelected)->pluck('id')->toArray();
            $fandomsIds = Fandom::whereIn('name', $fandomsSelected)->pluck('id')->toArray();
            $charactersIds = Character::whereIn('name', $charactersSelected)->pluck('id')->toArray();

            $paringsIds = [];
            foreach ($paringsSelected as $paring)
                $paringsIds[] = Character::whereIn('name', $paring)->pluck('id')->toArray();

            $fanfics = Fanfiction::whereIn('age_rating_id', $ageRating)
                ->whereIn('category_id', $category)
                ->whereJsonContains('characters', $charactersIds)
                ->whereJsonContains('characters', $paringsIds)
                ->whereJsonContains('tags', $tagsIds)
                ->whereJsonContains('fandoms_id', $fandomsIds)
                ->orderBy($sortBy, 'desc')
                ->paginate(30);

            DB::table('filter_requests')->insert([
                'categories' => json_encode($category),
                'age_ratings' => json_encode($ageRating),
                'characters' => json_encode([$charactersIds, $paringsIds]),
                'tags' => json_encode($tagsIds),
                'fandoms_id' => json_encode($fandomsIds),
                'sort_by' => $sortBy,
            ]);
        }

        $data = [
            'title' => 'Фандоми',
            'metaDescription' => null,
            'ageRatings' => AgeRating::all(),
            'categories' => Category::all(),
            'fandoms' => Fandom::orderBy('fictions_amount')->get(),
            'tags' => Tag::all(),
            'characters' => Character::orderBy('belonging_to_fandom_id')->get(),
            'fanfics' => $fanfics ?? null
        ];

        return view('filter-page', $data);
    }

}
