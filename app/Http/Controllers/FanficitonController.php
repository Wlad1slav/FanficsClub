<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Chapter;
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
use Illuminate\Support\Facades\Cache;

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
                'fandoms_selected' => ['required', new FandomsExists()],
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

    public function view(string $ff_slug, ?string $chapter_slug = null)
    {   // FanficPage
        // Сторінка з переглядом певного фанфіка
        $fanfic = Cache::remember("fanfic_$ff_slug", 60*60, function () use ($ff_slug) {
            // Передбачається, що фанфік в кешу зберігається 1 годину,
            // але якщо користувач його оновить, то кеш автоматом очиститься
            return Fanfiction::where('slug', $ff_slug)->first();
        });

        $chapters = Cache::remember("chapters_ff_{$fanfic->id}", 60*60, function () use ($fanfic) {
            // При створенні нового розділу чи оновленні існуючого кеш повинен оновлюватися

            if ($fanfic->chapters_sequence !== null) {
                // Розділи сортируються згідно з заданим порядком в бд
                $sequence = json_decode($fanfic->chapters_sequence, true);
                $sequenceStr = implode(',', $sequence);
                return Chapter::whereIn('id', $sequence)
                    ->orderByRaw("FIELD(id, {$sequenceStr})")
                    ->get();
            } else {
                return null;
            }
        });

        if ($chapter_slug !== null) {
            // Якщо в посиланні заданий slug глави
            $chapter = Cache::remember("chapter_{$chapter_slug}", 60*60, function () use ($chapter_slug) {
                // При оновленні розділу, кеш повинен видалятися
                return Chapter::where('slug', $chapter_slug)->first();
            });
        }

        $data = [
            'title' => $fanfic->title,
            'metaDescription' => $fanfic->description,
            'navigation' => require_once 'navigation.php',
            'fanfic' => $fanfic,
            'chapters' => $chapters,
            'chapter' => $chapter ?? null
        ];

        return view('fanfic-view.view', $data);
    }

}
