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
use Illuminate\Support\Facades\DB;

class RequestsController extends Controller
{

    public function fanficTranslatePage()
    {   // FanficTranslatePage

        $data = [
            'title' => 'Запит ШІ-перекладу перекладу фанфіку',
            'metaDescription' => '',
            'navigation' => require_once 'navigation.php',
        ];

        return view('request.ff-translate', $data);
    }

    public function fanficTranslate(Request $request)
    {   // FanficTranslateAction

        $request->validate([
            'link' => ['required', 'url'],
        ]);

        $table = DB::table('request_to_translate_ff');

        $table->insert([
            'url' => $request->link,
            'user_id' => Auth::user()->id,
        ]);

        return back();
    }

}
