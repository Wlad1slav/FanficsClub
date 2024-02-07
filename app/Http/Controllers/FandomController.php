<?php

namespace App\Http\Controllers;

use App\Models\Fandom;
use App\Models\FandomCategories;
use Illuminate\Support\Facades\Cache;

class FandomController extends Controller
{

    public function certainCategory(string $category_slug)
    {   // CertainCategoryPage
        // /fandoms/{category_slug}
        // Сторінка з фандомами по певной категорії

        $category = Cache::remember("fandom_category_$category_slug", 60*60*168, function () use ($category_slug) {
            return FandomCategories::where('slug', $category_slug)->first();
        });

        // П'ять найпопулярніших фандомів
        $fandoms = Cache::remember("fandoms_{$category_slug}_ordered_by_alphabet", 60*60*168, function () use ($category_slug) {
            return Fandom::getFandomsOrderedByAlphabet($category_slug);
        });

        $data = [
            'title' => $category->name,
            'metaDescription' => '',
            'navigation' => require_once 'navigation.php',
            'fandoms' => $fandoms
        ];

        return view('fandom-category-certain', $data);
    }

    public function fandomsCategories()
    {   // FandomsCategoriesPage
        // /fandoms

        $data = [
            'title' => 'Фандоми',
            'metaDescription' => '',
            'navigation' => require_once 'navigation.php',

            // Усі категорії з 5-ю найпопулярнішими фандомами по ним
            'fandomsOrganisedByCategories' => Fandom::getFandomsOrderedByCategories(5),

            // П'ять найпопулярніших фандомів
            'fandoms' => Cache::remember("top_fandoms", 60*60*12, function () {
                return Fandom::orderBy('fictions_amount', 'desc')->take(5)->get();
            })
        ];

        return view('fandom-categories', $data);
    }
}
