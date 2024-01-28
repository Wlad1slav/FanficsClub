<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Fandom;
use App\Models\FandomCategories;
use App\Models\Fanfiction;
use App\Models\Tag;
use App\Models\TagCategory;

use App\Models\User;
use Illuminate\Http\Request;

class FandomController extends Controller
{
    public function certainFandom(string $slug)
    { // CertainFandomPage

        $data = [
            'title' => Fandom::where('slug', $slug)->first()->name,
            'metaDescription' => '',
            'fanfics' => Fanfiction::where('fandom', $slug)->get()
        ];


        return view('fandom-certain', $data);
    }

    public function fandomsCategories()
    { // FandomsCategoriesPage
        $data = [
            'title' => 'Фандоми',
            'metaDescription' => '',
            // Усі категорії з 5-ю найпопулярнішими фандомами по ним
            'fandomsOrganisedByCategories' => Fandom::getFandomsOrderedByCategories(5),
            'fandoms' => Fandom::getFandomsOrderedByFfAmount(5) // Отримання 5 найпопулярніших фандомів
        ];


        return view('fandoms', $data);
    }
}
