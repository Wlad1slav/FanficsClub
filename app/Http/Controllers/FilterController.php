<?php

namespace App\Http\Controllers;

use App\Models\AgeRating;
use App\Models\Category;
use App\Models\Character;
use App\Models\Fandom;
use App\Models\Tag;

class FilterController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Фандоми',
            'metaDescription' => null,
            'ageRatings' => AgeRating::all(),
            'categories' => Category::all(),
            'fandoms' => Fandom::orderBy('fictions_amount')->get(),
            'tags' => Tag::all(),
            'characters' => Character::orderBy('belonging_to_fandom_id')->get(),
        ];

        return view('filter-page', $data);
    }

}
