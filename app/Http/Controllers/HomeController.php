<?php

namespace App\Http\Controllers;

use App\Models\AgeRating;
use App\Models\Category;
use App\Models\Character;
use App\Models\Fandom;
use App\Models\FandomCategories;
use App\Models\Fanfiction;
use App\Models\Tag;

use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index() {

        $fc = new FandomCategories();
        $fandoms = new Fandom();
        $a = new AgeRating();
        $c = new Category();
        $t = new Tag();
        $cc = new Character();

        Fandom::calculateAllPopularity();

        $topFandoms = Cache::remember("top_fandoms", 60*60*12, function () {
            return Fandom::orderBy('fictions_amount', 'desc')->take(5)->get();
        });

        $lastUpdatedFanfics = Cache::remember("last_updated_ff", 60*10, function () {
            return Fanfiction::where('is_draft', false)->orderBy('updated_at', 'desc')->take(5)->get();
        });

        $lastCreatedFanfics = Cache::remember("last_created_ff", 60*10, function () {
            return Fanfiction::where('is_draft', false)->orderBy('created_at', 'desc')->take(5)->get();
        });

        $data = [
            'title' => 'Головна',
            'metaDescription' => '',
            'navigation' => require_once 'navigation.php',

            // Отримання 5 найпопулярніших фандомів
            'fandoms' => $topFandoms,

            // Отримання 5 фанфіків, що були оновлені останніми
            'last_updated_fanfics' => $lastUpdatedFanfics,

            // Отримання 5 фанфіків, що були створенні останніми
            'last_created_fanfics' => $lastCreatedFanfics,
        ];

        return view('index', $data);
    }
}
