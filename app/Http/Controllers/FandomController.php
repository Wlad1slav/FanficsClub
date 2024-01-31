<?php

namespace App\Http\Controllers;

use App\Models\Fandom;
use App\Models\FandomCategories;

class FandomController extends Controller
{
    public function certainFandom(string $slug)
    {   // CertainFandomPage
        // /fandom/{slug}
        // Сторінка з фанфіками, що належать певному фандому

        $fandom = Fandom::where('slug', $slug)->first(); // Фандом, до якого належать фанфіки

        $data = [
            'title' => $fandom->name,
            'metaDescription' => '',
            // Отримання усіх фанфіків, що належать фандому
            'fanfics' => $fandom->fanfictions
        ];

        return view('fandom-certain', $data);
    }

    public function certainCategory(string $category_slug)
    {   // CertainCategoryPage
        // /fandoms/{category_slug}
        // Сторінка з фандомами по певной категорії

        $data = [
            'title' => FandomCategories::where('slug', $category_slug)->first()->name,
            'metaDescription' => '',
            'fandoms' => Fandom::getFandomsOrderedByAlphabet($category_slug)
        ];

        return view('fandom-category-certain', $data);
    }

    public function fandomsCategories()
    {   // FandomsCategoriesPage
        // /fandoms

        $data = [
            'title' => 'Фандоми',
            'metaDescription' => '',
            // Усі категорії з 5-ю найпопулярнішими фандомами по ним
            'fandomsOrganisedByCategories' => Fandom::getFandomsOrderedByCategories(5),
            'fandoms' => Fandom::orderBy('fictions_amount')->take(5)->get() // Отримання 5 найпопулярніших фандомів
        ];

        return view('fandom-categories', $data);
    }
}
