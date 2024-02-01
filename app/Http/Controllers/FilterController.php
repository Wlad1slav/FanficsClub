<?php

namespace App\Http\Controllers;

use App\Models\AgeRating;
use App\Models\Category;
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
        ];

        return view('filter-page', $data);
    }

}
