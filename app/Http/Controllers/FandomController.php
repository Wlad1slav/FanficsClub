<?php

namespace App\Http\Controllers;

use App\Models\Fandom;
use App\Models\FandomCategories;

class FandomController extends Controller
{

    public function certainCategory(string $category_slug)
    {   // CertainCategoryPage
        // /fandoms/{category_slug}
        // Сторінка з фандомами по певной категорії

        $data = [
            'title' => FandomCategories::where('slug', $category_slug)->first()->name,
            'metaDescription' => '',
            'navigation' => require_once 'navigation.php',

            // П'ять найпопулярніших фандомів
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
            'navigation' => require_once 'navigation.php',

            // Усі категорії з 5-ю найпопулярнішими фандомами по ним
            'fandomsOrganisedByCategories' => Fandom::getFandomsOrderedByCategories(5),

            // П'ять найпопулярніших фандомів
            'fandoms' => Fandom::orderBy('fictions_amount', 'desc')->take(5)->get() // Отримання 5 найпопулярніших фандомів
        ];

        return view('fandom-categories', $data);
    }
}
