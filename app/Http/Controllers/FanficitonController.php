<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Fandom;
use App\Models\Fanfiction;
use App\Models\Tag;
use App\Rules\AgeRatingExists;
use App\Rules\CategoryExists;
use App\Rules\FandomsExists;
use App\Rules\TagsExists;
use App\Traits\SlugGenerationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FanficitonController extends Controller
{

    use SlugGenerationTrait;

    public function create(Request $request)
    {   // FanficCreateAction
        // Створення фанфіка через форму


        $request->validate([
            'type_of_work' => 'required',
//            'anonymity' => 'required',
            'originality_of_work' => 'required',
            'ff_name' => ['required', 'string', 'min:1'],
//            'characters' => ['string'],
            'age_rating' => ['required', new AgeRatingExists()],
            'category' => ['required', new CategoryExists()],
            'tags_selected' => [new TagsExists()],
            'ff_description' => ['max:550'],
            'ff_notes' => ['max:550'],
        ]);

        // Якщо твір є фанфіком по певному фандому, то валідується
        // передана строка з фандомами, до яких належить фанфік
        if ($request->originality_of_work == '0')
            $request->validate([
                'fandoms_selected' => ['required', 'string', new FandomsExists()],
            ]);


        $fanfic = [
            'slug' => self::createOriginalSlug($request->ff_name, Fanfiction::class),
            'author_id' => Auth::user()->id,
            'fandoms_id' => json_encode(Fandom::convertStrAttrToArray($request->fandoms_selected ?? null)),
            'title' => $request->ff_name,
            'description' => $request->ff_description,
            'additional_descriptions' => $request->ff_notes,
            'tags' => json_encode(Tag::convertStrAttrToArray($request->tags_selected)),
            'characters' => json_encode(Character::convertCharactersStrToArray($request->characters)),
            'category_id' => $request->category,
            'age_rating_id' => $request->age_rating,
        ];

        // Якщо твір є перекладом, то валідується
        // переданий псевдоним автора і посилання на оригінальну роботу
        if ($request->type_of_work == '1') {
            $request->validate([
                'ff_original_author' => ['required', 'string', 'min:1'],
                'ff_original_link' => ['required', 'url']
            ]);

            $fanfic['original_author'] = $request->ff_original_author;
            $fanfic['original_url'] = $request->ff_original_link;
            $fanfic['is_translate'] = true;
        }

        Fanfiction::create($fanfic);
    }

}
