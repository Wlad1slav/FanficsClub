<?php

namespace App\Http\Controllers;

use App\Models\AgeRating;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\Character;
use App\Models\Dislike;
use App\Models\Fandom;
use App\Models\Fanfiction;
use App\Models\Like;
use App\Models\Review;
use App\Models\Subscribe;
use App\Models\Tag;
use App\Models\User;
use App\Models\View;
use App\Rules\AgeRatingExists;
use App\Rules\CategoryExists;
use App\Rules\FandomsExists;
use App\Rules\TagsExists;
use App\Rules\UserNotOwnedFanfic;
use App\Rules\UserOwnedFanfic;
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
            'originality_of_work' => 'required',
            'ff_name' => ['required', 'string'],
            'age_rating' => ['required', new AgeRatingExists()],
            'category' => ['required', new CategoryExists()],
            'tags_selected' => [new TagsExists()],
            'ff_description' => ['max:550'],
            'ff_notes' => ['max:550'],
            'prequel' => [new UserOwnedFanfic()]
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
            'fandoms_id' => ($request->originality_of_work ?? 1) === 0 ? // Якщо оригінальність вибрана, як "Оригінальний твір"
                Fandom::convertStrAttrToArray($request->fandoms_selected ?? null) : null, // то встановлюється null
            'title' => $request->ff_name,
            'description' => $request->ff_description,
            'additional_descriptions' => $request->ff_notes,
            'tags' => Tag::convertStrAttrToArray($request->tags_selected),
            'characters' => ($request->originality_of_work ?? 1) === 0 ? // Якщо оригінальність вибрана, як "Оригінальний твір"
                Character::convertCharactersStrToArray($request->characters) :
                Character::convertOriginalCharactersToArray($request->characters_original), // то повертається просто масив строк імен оригінальних персонажів
            'category_id' => $request->category,
            'age_rating_id' => $request->age_rating,
            'is_anonymous' => ($request->anonymity ?? 0) == 1,
            'prequel_id' => ($request->prequel ?? '-1') !== '-1' ? ($request->prequel ?? null) : null,
        ];

        // Якщо твір є перекладом, то валідується
        // переданий псевдоним автора і посилання на оригінальну роботу
        if ($request->type_of_work == '1') {
            $request->validate([
                'ff_original_author' => ['required', 'string'],
                'ff_original_link' => ['required', 'url']
            ]);

            $fanfic['original_author'] = $request->ff_original_author;
            $fanfic['original_url'] = $request->ff_original_link;
            $fanfic['is_translate'] = true;
        }

        $fanfic = Fanfiction::create($fanfic);

        return redirect()->route('FanficPage', ['ff_slug' => $fanfic->slug]);
    }

    public function view(string $ff_slug, ?string $chapter_slug = null)
    {   // FanficPage
        // Сторінка з переглядом певного фанфіка
        $fanfic = Cache::remember("fanfic_$ff_slug", 60 * 60, function () use ($ff_slug) {
            // Передбачається, що фанфік в кешу зберігається 1 годину,
            // але якщо користувач його оновить, то кеш автоматом очиститься
            return Fanfiction::where('slug', $ff_slug)->first();
        });

        if ($fanfic->is_draft)
            $this->authorize('fanficAccess', $fanfic ?? null);

        $chapters = Chapter::getCached($fanfic);

        if ($chapter_slug !== null) {
            // Якщо в посиланні заданий slug глави
            $chapter = Chapter::firstCached($chapter_slug);

            if ($chapter->is_draft)
                $this->authorize('fanficAccess', $fanfic ?? null);
        }

        $reviews = Review::getCached($chapter ?? null);

        // Перегляд фанфіка зараховується
        View::firstOrCreate([
            'user_id' => Auth::user()->id ?? null,
            'fanfiction_id' => $fanfic->id,
            'ip' => request()->ip()
        ]);

        $data = [
            'title' => $fanfic->title,
            'metaDescription' => $fanfic->description,
            'navigation' => require_once 'navigation.php',

            // Фанфік, якому присвячена сторінка
            'fanfic' => $fanfic,

            // Масив імен усіх розділів фанфіку
            'chapters' => $chapters,

            // Конкретний розділ, на якому знаходиться користувач.
            // По стандарту - перший розділ.
            'chapter' => $chapter ?? null,

            'reviews' => $reviews ?? null,

            // Усі користувачі, що мають доступ до фанфіку
            'usersWithAccess' => $fanfic->usersWithAccess()
        ];

        return view('fanfic-view.view', $data);
    }

    public function editPage(string $ff_slug)
    {

        $fanfic = Cache::remember("fanfic_$ff_slug", 60 * 60, function () use ($ff_slug) {
            return Fanfiction::where('slug', $ff_slug)->first();
        });

        // Перевірка, чи користувач має доступ до фанфіка
        $this->authorize('isAuthor', $fanfic ?? null);

        $selectedCharacters = '';

        if ($fanfic->fandoms_id !== null)
            if (count($fanfic->characters['characters']) > 0 || count($fanfic->characters['parings']) > 0) {

                foreach ($fanfic->characters['parings'] as $paring) {
                    // Отримання усіх пейренгів
                    $paringNames = []; // Створення нового масиву для імен персонажів
                    foreach ($paring as $key => $character) {
                        $c = Character::find($character);
                        if ($c)  // Перевірка на існування персонажа
                            $paringNames[$key] = $c->name;
                    }
                    if (!empty($paringNames)) { // Перевірка, що масив не пустий перед об'єднанням
                        $paringStr = implode('/', $paringNames); // Об'єднання імен персонажів у рядок
                        $selectedCharacters .= "$paringStr, "; // Додавання рядка до результату
                    }
                }

                foreach ($fanfic->characters['characters'] as $character_id) {
                    $character = Character::find($character_id);
                    if ($character)
                        $selectedCharacters .= "$character->name, ";
                }

            }

        $data = [
            'navigation' => require_once 'navigation.php',

            // Редагуємий фанфік
            'fanfic' => $fanfic,

            // Усі вікові рейтинги
            'ageRatings' => Cache::remember("age_ratings_all", 60 * 60 * 168, function () {
                return AgeRating::all();
            }),

            // Усі категорії
            'categories' => Cache::remember("categories_all", 60 * 60 * 168, function () {
                return Category::all();
            }),

            // Усі фандоми, відсортировані по популярності
            'fandoms' => Cache::remember("fandoms_all", 60 * 60 * 12, function () {
                return Fandom::orderBy('fictions_amount', 'desc')->get();
            }),

            // Назви усіх фандомів, до яких вже належить фанфік
            'fandoms_selected' => $fanfic->fandoms->pluck('name')->toArray(),

            // Усі теґі
            'tags' => Cache::remember("tags_all", 60 * 60 * 24, function () {
                return Tag::all();
            }),

            // Назви усіх тегів, які вже є у фанфіку
            'tags_selected' => $fanfic->tags->pluck('name')->toArray(),

            // Усі користувачі, відсортировані по фандомам
            'characters' => Cache::remember("characters_all", 60 * 60 * 24, function () {
                return Character::orderBy('belonging_to_fandom_id')->get();
            }),

            // Строка з усіма персонажами, що є в фанфіку
            'characters_selected' => $selectedCharacters
        ];

        return view('fanfic-edit.ff-edit', $data);

    }

    public function edit(Request $request, string $ff_slug)
    {
        // FanficEditAction
        // Редагування фанфіка через форму

        $fanfic = Cache::remember("fanfic_$ff_slug", 60 * 60, function () use ($ff_slug) {
            return Fanfiction::where('slug', $ff_slug)->first();
        });

        // Перевірка, чи користувач має доступ до фанфіка
        $this->authorize('isAuthor', $fanfic ?? null);

        $request->validate([
            'type_of_work' => 'required',
            'ff_name' => ['required', 'string', 'max:255'],
            'age_rating' => ['required', new AgeRatingExists()],
            'category' => ['required', new CategoryExists()],
            'tags_selected' => [new TagsExists()],
            'ff_description' => ['max:550'],
            'ff_notes' => ['max:550'],
            'prequel' => [new UserOwnedFanfic()],

            'fandoms_selected' => [new FandomsExists()],
        ]);

        if ($fanfic->title !== $request->ff_name)
            $slug = self::createOriginalSlug(
                $request->ff_name,
                new Fanfiction());
        else $slug = $fanfic->slug;


        $newFanficInfo = [
            'slug' => $slug,
            'fandoms_id' => ($fanfic->fandoms_id ?? null) !== null ? // Якщо оригінальність вибрана, як "Оригінальний твір"
                Fandom::convertStrAttrToArray($request->fandoms_selected ?? null) : null, // то встановлюється null
            'title' => $request->ff_name,
            'description' => $request->ff_description,
            'additional_descriptions' => $request->ff_notes,
            'tags' => Tag::convertStrAttrToArray($request->tags_selected),
            'characters' => ($fanfic->fandoms_id ?? null) !== null ? // Якщо оригінальність вибрана, як "Оригінальний твір"
                Character::convertCharactersStrToArray($request->characters) :
                Character::convertOriginalCharactersToArray($request->characters_original), // то повертається просто масив строк імен оригінальних персонажів
            'category_id' => $request->category,
            'age_rating_id' => $request->age_rating,
            'is_anonymous' => ($request->anonymity ?? 0) == 1,
            'is_draft' => ($request->is_draft ?? 0) == 1,
            'prequel_id' => ($request->prequel ?? '-1') !== '-1' ? ($request->prequel ?? null) : null,
        ];

        // Якщо твір є перекладом, то валідується
        // переданий псевдоним автора і посилання на оригінальну роботу
        if ($request->type_of_work == '1') {
            $request->validate([
                'ff_original_author' => ['required', 'string'],
                'ff_original_link' => ['required', 'url']
            ]);

            $newFanficInfo['original_author'] = $request->ff_original_author;
            $newFanficInfo['original_url'] = $request->ff_original_link;
            $newFanficInfo['is_translate'] = true;
        }

        $fanfic->clearCache();
        $fanfic->update($newFanficInfo);

        return redirect()->route('FanficEditPage', ['ff_slug' => $fanfic->slug]);
    }

    public function usersAccess(string $ff_slug)
    {   // UsersAccessPage
        // Сторінка з усіма користувачами, які мають доступ до фанфіка
        // і формами для давання користувачам доступу

        $fanfic = Cache::remember("fanfic_$ff_slug", 60 * 60, function () use ($ff_slug) {
            return Fanfiction::where('slug', $ff_slug)->first();
        });

        // Перевірка, чи користувач має доступ до фанфіка
        $this->authorize('isAuthor', $fanfic ?? null);

        $data = [
            'navigation' => require_once 'navigation.php',

            // Редагуємий фанфік
            'fanfic' => $fanfic,

            // Користувачі, що мають доступ до фанфіку
            'users_with_access' => $fanfic->usersWithAccess(),

            // Масив з рівням прав кожного користувача, що має доступ до фанфіка
            'fanfic_access' => $fanfic->users_with_access
        ];

        return view('fanfic-edit.users-access', $data);
    }

    public function giveAccessToFanfic(Request $request, string $ff_slug, string $right)
    {   // GiveAccessAction
        // Додати соавтора чи редактора в фанфік

        $fanfic = Cache::remember("fanfic_$ff_slug", 60 * 60, function () use ($ff_slug) {
            return Fanfiction::where('slug', $ff_slug)->first();
        });

        // Перевірка, чи користувач має доступ до фанфіка
        $this->authorize('isAuthor', $fanfic ?? null);

        $request->validate([
            'email' => ['required', 'email', 'exists:App\Models\User,email', new UserNotOwnedFanfic($fanfic)],
        ]);

        $user = User::where('email', $request->email ?? null)->first();

        $fanfic->clearCache();

        // $rights - рівень прав користувача
        // coauthor
        // editor
        $fanfic->update(["users_with_access->$user->id" => $right]);

        return back();

    }

    public function putUserAccess(string $ff_slug, int $userId)
    {   // PutUserAccessAction
        // Прибрати доступ у певного користувача

        $fanfic = Cache::remember("fanfic_$ff_slug", 60 * 60, function () use ($ff_slug) {
            return Fanfiction::where('slug', $ff_slug)->first();
        });

        // Перевірка, чи користувач має доступ до фанфіка
        $this->authorize('isAuthor', $fanfic);

        // Прибириання користувача з масиву користувачів з доступом
        $users = $fanfic->users_with_access;
        unset($users[$userId]);
        $fanfic->update(['users_with_access' => $users]);

        $fanfic->clearCache();

        return back();
    }

    public function giveLike(string $ff_slug)
    {   // GiveLikeAction
        $fanfic = Cache::remember("fanfic_$ff_slug", 60 * 60, function () use ($ff_slug) {
            return Fanfiction::where('slug', $ff_slug)->first();
        });

        $dislike = Dislike::where('gave_dislike', Auth::user()->id)->where('fanfiction', $fanfic->id);
        if ($dislike) $dislike->delete();

        Like::firstOrCreate([
            'gave_like' => Auth::user()->id,
            'fanfiction' => $fanfic->id,
        ]);

//        $fanfic->clearCache();

        return response()->json([
            'likes' => $fanfic->likes->count(),
            'dislikes' => $fanfic->dislikes->count(),
        ]);
    }

    public function giveDislike(string $ff_slug)
    {   // GiveDislikeAction
        $fanfic = Cache::remember("fanfic_$ff_slug", 60 * 60, function () use ($ff_slug) {
            return Fanfiction::where('slug', $ff_slug)->first();
        });

        $like = Like::where('gave_like', Auth::user()->id)->where('fanfiction', $fanfic->id)->first();
        if ($like) $like->delete();

        Dislike::firstOrCreate([
            'gave_dislike' => Auth::user()->id,
            'fanfiction' => $fanfic->id,
        ]);

//        $fanfic->clearCache();

        return response()->json([
            'likes' => $fanfic->likes->count(),
            'dislikes' => $fanfic->dislikes->count(),
        ]);
    }

    public function subscribe(string $ff_slug)
    {   // SubscribeAction

        $fanfic = Cache::remember("fanfic_$ff_slug", 60 * 60, function () use ($ff_slug) {
            return Fanfiction::where('slug', $ff_slug)->first();
        });

        $user = Auth::user();

        $subscribe = Subscribe::where('user_id', $user->id)
            ->where('fanfiction_id', $fanfic->id);

        if (!$subscribe->exists()) {// Якщо користувач не підписаний
            Subscribe::firstOrCreate([
                'user_id' => $user->id,
                'fanfiction_id' => $fanfic->id,
            ]);
            Subscribe::clearUserCache($user);
            return response()->json(['btn_text' => 'Відписатися']);
        }
        else { // Якщо користувач вже підписаний, то він відписується
            $subscribe->delete();
            Subscribe::clearUserCache($user);
            return response()->json(['btn_text' => 'Підписатися']);
        }
    }


}
