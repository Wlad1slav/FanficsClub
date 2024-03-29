<?php

namespace App\Http\Controllers;

use App\Models\AgeRating;
use App\Models\Category;
use App\Models\Character;
use App\Models\Fandom;
use App\Models\FandomCategories;
use App\Models\Fanfiction;
use App\Models\Subscribe;
use App\Models\Tag;
use App\Models\User;
use App\Rules\FandomCategoryExists;
use App\Rules\FandomExists;
use App\Traits\SlugGenerationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RequestsController extends Controller
{

    use SlugGenerationTrait;

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

        return back()->with('success', 'Запит відправлений і буде розглянутий в найближчий час.');
    }

    public function fanficTransferPage()
    {   // FanficTransferPage

        $data = [
            'title' => 'Запит перенесення фанфіку',
            'metaDescription' => '',
            'navigation' => require_once 'navigation.php',
        ];

        return view('request.ff-move', $data);
    }

    public function fanficTransfer(Request $request)
    {   // FanficTransferAction

        $request->validate([
            'link' => ['required', 'url'],
        ]);

        $table = DB::table('request_to_transfer_ff');

        $table->insert([
            'url' => $request->link,
            'user_id' => Auth::user()->id,
        ]);

        return back()->with('success', 'Запит відправлений і буде розглянутий в найближчий час.');
    }

    public function addFandomForm()
    {   // AddFandomPage

        $categories = Cache::remember("fandom_categories", 60*60*168, function () {
            return FandomCategories::all();
        });

        $mediaGiants = Cache::remember('media_giants', 60*60, function () {
            return Fandom::where('fandom_category_id', 1)->get();
        });

        $data = [
            'title' => 'Додати фандом',
            'metaDescription' => '',
            'navigation' => require_once 'navigation.php',
            'categories' => $categories,
            'media_giants' => $mediaGiants,
        ];

        return view('request.add-fandom', $data);
    }

    public function addFandom(Request $request)
    {   // AddFandomAction

        $request->validate([
            'fandom_name' => ['required', 'string', 'unique:fandoms,name'],
            'fandom_description' => ['max:500'],
            'fandom_category' => [new FandomCategoryExists()],
            'related_mediagiant' => [new FandomExists()],
            'fandom_image' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:1024'],
        ]);

        $slug = self::createOriginalSlug($request->fandom_name, new Fandom());

        // Збереження прев'ю для фандому
        if ($request->hasFile('fandom_image')) {
            // Розширення прев'ю
            $extension = $request->file('fandom_image')->getClientOriginalExtension();

            // Генерація назви для зображення
            $fileNameToStore= "storage/fandoms/$slug.$extension";
            $request->file('fandom_image')->storeAs('public/fandoms', "$slug.$extension");
        }

        Fandom::create([
            'slug' => $slug,
            'name' => $request->fandom_name,
            'image' => $fileNameToStore ?? null,
            'fandom_category_id' => $request->fandom_category ?? null,
            'description' => $request->fandom_description ?? null,
            'related_media_giant_fandom_id' => Fandom::where('name', $request->related_mediagiant)->first()?->id,
            'added_by_user' => Auth::user()->id,
        ]);

        Cache::pull('fandoms_all');

        return back()->with('success', "Фандом $request->fandom_name успішно доданий на сайт.");
    }

    public function addTagForm()
    {   // AddTagPage

        $data = [
            'title' => 'Додати теґ',
            'metaDescription' => '',
            'navigation' => require_once 'navigation.php',

            'fandoms' => Cache::remember("fandoms_all", 60*60*12, function () {
                return Fandom::orderBy('fictions_amount', 'desc')->get();
            }),
        ];

        return view('request.add-tag', $data);
    }

    public function addTag(Request $request)
    {   // AddTagAction

        $request->validate([
            'tag_name' => ['required', 'string', 'unique:tags,name'],
            'tag_description' => ['max:500'],
            'related_fandom' => [new FandomExists()],
        ]);

        Tag::create([
            'name' => $request->tag_name,
            'description' => $request->tag_description ?? null,
            'belonging_to_fandom_id' => Fandom::where('name', $request->related_fandom)->first()?->id,
            'added_by_user' => Auth::user()->id
        ]);

        Cache::pull('tags_all');

        return back()->with('success', "Теґ $request->tag_name успішно доданий на сайт.");
    }

    public function addCharacterForm()
    {   // AddCharacterPage

        $data = [
            'title' => 'Додати персонажа',
            'metaDescription' => '',
            'navigation' => require_once 'navigation.php',

            'fandoms' => Cache::remember("fandoms_all", 60*60*12, function () {
                return Fandom::orderBy('fictions_amount', 'desc')->get();
            }),
        ];

        return view('request.add-character', $data);
    }

    public function addCharacter(Request $request)
    {   // AddCharacterAction

        $request->validate([
            'character_name' => ['required', 'string', 'unique:characters,name'],
            'related_fandom' => ['required', new FandomExists()],
        ]);

        $fandom = Fandom::where('name', $request->related_fandom)->first();

        Character::create([
            'name' => $request->character_name,
            'belonging_to_fandom_id' => $fandom->id,
            'added_by_user' => Auth::user()->id
        ]);

        Cache::pull('characters_all');

        return back()->with('success', "Персонаж $request->character_name успішно доданий для фандому $fandom->name.");
    }

    public function reportForm()
    {   // ReportPage

        $data = [
            'title' => 'Повідомити про помилку',
            'metaDescription' => '',
            'navigation' => require_once 'navigation.php',
        ];

        return view('request.report-problem', $data);
    }

    public function report(Request $request)
    {   // ReportAction

        $request->validate([
            'report_theme' => ['required', 'string', 'max:255'],
            'report_message' => ['required'],
            'screenshot' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:1024'],
        ]);

        $user = Auth::user();

        // Збереження скріншоту в повідомленні
        if ($request->hasFile('screenshot')) {
            // Розширення скріншоту
            $extension = $request->file('screenshot')->getClientOriginalExtension();

            // Генерація назви для зображення
            $fileNameToStore= "storage/reports/{$user->id}_". date('Y-m-d_H-i-s') .".$extension";
            $request->file('screenshot')->storeAs('public/reports',
                "{$user->id}_". date('Y-m-d_H-i-s') .".$extension");
        }

        $table = DB::table('reports');

        $table->insert([
            'report_theme' => $request->report_theme,
            'report_message' => $request->report_message,
            'screenshot' => $fileNameToStore ?? null,
            'user_id' => $user->id,
        ]);

        return back()->with('success', "Повідомлення успішно відправлено!");
    }

}
