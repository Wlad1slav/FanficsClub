<?php

namespace App\Http\Controllers;

use App\Models\AgeRating;
use App\Models\Category;
use App\Models\Character;
use App\Models\Fandom;
use App\Models\Fanfiction;
use App\Models\Subscribe;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class InformationController extends Controller
{

    public function aboutSite()
    {   // AboutSitePage

        $data = [
            'title' => 'Про сайт',
            'metaDescription' => '',
            'navigation' => require_once 'navigation.php',
        ];

        return view('information-pages.about-site', $data);
    }

    public function rules()
    {   // RulesPage

        $data = [
            'title' => 'Правила сайту',
            'metaDescription' => '',
            'navigation' => require_once 'navigation.php',
        ];

        return view('information-pages.rules', $data);
    }

}
