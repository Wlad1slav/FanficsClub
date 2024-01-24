<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tag;
use App\Models\TagCategory;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        // /home

        /*$category = TagCategory::create([
            'name' => '18+',
        ]);

        $tag = Tag::create([
            'name' => 'dark',
            'category' => '18+',
            'description' => 'dark fics',
        ]);

        dd($tag);*/

        return view('index');
    }
}
