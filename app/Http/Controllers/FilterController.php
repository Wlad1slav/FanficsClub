<?php

namespace App\Http\Controllers;

use App\Models\AgeRating;
use App\Models\Category;
use App\Models\Character;
use App\Models\Fandom;
use App\Models\Fanfiction;
use App\Models\Tag;

class FilterController extends Controller
{

    public function index()
    {

        if (isset($_GET['sort-by'])) {
            $ageRating = $_GET['age-rating'] ?? [1,2,3,4,5];
            $category = $_GET['category'] ?? [1,2,3,4];
            $sortBy = $_GET['sort-by'];

            // Отримання тегів і фандомів
            $tagsSelected = array_filter(preg_split('/,\s?/', $_GET['tags-selected']));
            $fandomsSelected = array_filter(preg_split('/,\s?/', $_GET['fandoms-selected']));

            $tagsIds = Tag::whereIn('name', $tagsSelected)->pluck('id');
            $fandomsIds = Fandom::whereIn('name', $fandomsSelected)->pluck('id');

            $fanfics = Fanfiction::whereIn('age_rating_id', $ageRating)
                ->whereIn('category_id', $category)
                ->with(['tags' => function ($query) use ($tagsIds) {
                    $query->whereIn('id', $tagsIds);
                }, 'fandoms' => function ($query) use ($fandomsIds) {
                    $query->whereIn('id', $fandomsIds);
                }])
                ->orderBy($sortBy)
                ->get()
                ->filter(function ($fanfic) use ($tagsIds, $fandomsIds) {
                    // Перевірка на наявність всіх необхідних тегів і фандомів
                    $ficTagsIds = $fanfic->tags->pluck('id');
                    $ficFandomsIds = $fanfic->fandoms->pluck('id');
                    return $ficTagsIds->intersect($tagsIds)->count() == count($tagsIds) &&
                        $ficFandomsIds->intersect($fandomsIds)->count() == count($fandomsIds);
                });
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
