<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Fanfiction;
use App\Models\Review;
use App\Rules\ChaptersBelongToFanfic;
use App\Traits\SlugGenerationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ChapterController extends Controller
{

    use SlugGenerationTrait;

    public function createForm(Request $request, string $ff_slug)
    {   // ChapterCreatePage
        // Форма створення розділу для певного фанфіка

        $fanfic = Cache::remember("fanfic_$ff_slug", 60*60, function () use ($ff_slug) {
            return Fanfiction::where('slug', $ff_slug)->first();
        });

        // Перевірка, чи користувач має доступ до фанфіка
        $this->authorize('isAuthor', $fanfic);

        $data = [
            'navigation' => require_once 'navigation.php',
            'fanfic' => $fanfic
        ];

        return view('fanfic-edit.chapter-create', $data);

    }

    public function create(Request $request, string $ff_slug)
    {   // ChapterCreateAction
        // Створення розділу для певного фанфіка

        $fanfic = Cache::remember("fanfic_$ff_slug", 60*60, function () use ($ff_slug) {
            return Fanfiction::where('slug', $ff_slug)->first();
        });

        // Перевірка, чи користувач має доступ до фанфіка
        $this->authorize('isAuthor', $fanfic);

        $request->validate([
            'chapter_title' => ['required', 'string'],
            'chapter_content' => ['required', 'string'],
        ]);

        $chapter = Chapter::create([
            'fanfiction_id' => $fanfic->id,
            'title' => $request->chapter_title,
            'slug' => self::createOriginalSlug($request->chapter_title, new Chapter()),
            'content' => $request->chapter_content,
            'additional_descriptions' => [
                'notify' => $request->notify ?? null,
                'notes' => $request->notes ?? null,
            ],

            // Якщо value is_draft рівен одному, то розділ зберігається, як чорнетка
            'is_draft' => ($request->is_draft ?? 0) == 1
        ]);

        // Додавання нового розділу в масив з послідовносттю розділів в фанфіку
        if ($fanfic->chapters_sequence === null)
            $fanfic->chapters_sequence = [$chapter->id];
        else {
            $sequence = $fanfic->chapters_sequence;
            $sequence[] = $chapter->id;
            $fanfic->chapters_sequence = $sequence;
        }

        $fanfic->clearCache(); // Видалення фанфіку з кешу
        $fanfic->save();

        return redirect(route('FanficPage', [
                'ff_slug' => $fanfic->slug,
                'chapter_slug' => $chapter->slug,
            ]) . '#chapter');

    }

    public function editForm(string $ff_slug, string $chapter_slug)
    {   // ChapterEditPage
        // Форма редагування розділу для певного фанфіка

        $fanfic = Cache::remember("fanfic_$ff_slug", 60*60, function () use ($ff_slug) {
            return Fanfiction::where('slug', $ff_slug)->first();
        });

        // Перевірка, чи користувач має доступ до фанфіка
        $this->authorize('fanficAccess', $fanfic ?? null);

        $chapter = Chapter::firstCached($chapter_slug);

        // Перевірка, чи користувач має доступ до фанфіка, якому належить розділ
        $this->authorize('fanficAccess', $chapter->fanfiction ?? null);

        // Перевірка, чи належить розділ до фанфіка
        $this->authorize('chapterBelongToFanfic', [$fanfic ?? null, $chapter ?? null]);

        $data = [
            'navigation' => require_once 'navigation.php',
            'fanfic' => $fanfic,
            'chapter' => $chapter
        ];

        return view('fanfic-edit.chapter-edit', $data);

    }

    public function edit(Request $request, string $ff_slug, string $chapter_slug)
    {   // ChapterEditAction
        // Редагування певного розділа

        $fanfic = Cache::remember("fanfic_$ff_slug", 60*60, function () use ($ff_slug) {
            return Fanfiction::where('slug', $ff_slug)->first();
        });

        // Перевірка, чи користувач має доступ до фанфіка
        $this->authorize('fanficAccess', $fanfic ?? null);

        $request->validate([
            'chapter_title' => ['required', 'string'],
            'chapter_content' => ['required', 'string'],
        ]);

        $chapter = Chapter::firstCached($chapter_slug);

        // Перевірка, чи користувач має доступ до фанфіка, якому належить розділ
        $this->authorize('fanficAccess', $chapter->fanfiction ?? null);

        // Перевірка, чи належить розділ до фанфіка
        $this->authorize('chapterBelongToFanfic', [$fanfic ?? null, $chapter ?? null]);

        if ($chapter->title !== $request->chapter_title)
            $slug = self::createOriginalSlug(
                $request->chapter_title ?? "Розділ " . ($fanfic->chapters->count() + 1) . "-$fanfic->id",
                new Chapter());
        else $slug = $chapter->slug;

        $fanfic->clearCache(); // Видалення фанфіку з кешу
        $chapter->clearCache(); // Видалення розділу з кешу

        $chapter->update([
            'title' => $request->chapter_title ?? "Розділ " . ($fanfic->chapters->count() + 1),
            'slug' => $slug,
            'content' => $request->chapter_content,
            'additional_descriptions' => [
                'notify' => $request->notify ?? null,
                'notes' => $request->notes ?? null,
            ],

            // Якщо value is_draft рівен одному, то розділ зберігається, як чорнетка
            'is_draft' => ($request->is_draft ?? 0) == 1
        ]);

        return back();

    }

    public function delete(string $ff_slug, string $chapter_slug)
    {   // ChapterDeleteAction
        // Видалення розділу

        $fanfic = Cache::remember("fanfic_$ff_slug", 60*60, function () use ($ff_slug) {
            return Fanfiction::where('slug', $ff_slug)->first();
        });

        // Перевірка, чи користувач має доступ до фанфіка
        $this->authorize('isAuthor', $fanfic);

        $chapter = Chapter::firstCached($chapter_slug);

        // Перевірка, чи користувач має доступ до фанфіка, якому належить розділ
        $this->authorize('isAuthor', $chapter->fanfiction ?? null);

        // Перевірка, чи належить розділ до фанфіка
        $this->authorize('chapterBelongToFanfic', [$fanfic ?? null, $chapter ?? null]);

        $chapter->clearCache();

        // Встановлює новий slug, що складається з id розділу і час, який пройшов з епохи Unix (в секундах).
        // Зроблено для того, щоб збавитися від помилки з дублюванням slug існуючух розділів і видаленних розділів.
        // Помилка виникає через м'яке видалення рядків.
        // Кеш видаляється перед зміною slug, бо в індифікатору кешу фігурує slug
        $fanfic->clearCache();
        $chapter->clearCache();
        $chapter->update(['slug' => "{$chapter->id}_".time()]);

        // Видаляє розділ
        $chapter->delete();

        return back();
    }

    public function chaptersList(string $ff_slug)
    {   // ChapterListPage
        // Перелік усіх розділів фанфіка

        $fanfic = Cache::remember("fanfic_$ff_slug", 60*60, function () use ($ff_slug) {
            return Fanfiction::where('slug', $ff_slug)->first();
        });

        // Перевірка, чи користувач має доступ до фанфіка
        $this->authorize('fanficAccess', $fanfic);

        $chapters = Chapter::getCached($fanfic);

        $data = [
            'navigation' => require_once 'navigation.php',
            'fanfic' => $fanfic,
            'chapters' => $chapters,
        ];

        return view('fanfic-edit.chapters', $data);
    }

    public function select(Request $request, string $ff_slug)
    {   // ChapterSelectAction
        // Post метод, що редіректить на вибраний користувачем розділ
        return redirect(route('FanficPage', [
            'ff_slug' => $ff_slug,
            'chapter_slug' => $request->chapter,
        ]) . '#chapter');
    }

    public function changeSequence(Request $request, string $ff_slug)
    {   // ChapterSequenceChange
        // Зміна послідовності розділів в фанфіку
        // Форма передає усі розділи з їх новим номером
        // Ключем кожного розділу є ключ, елементом - номер

        $fanfic = Cache::remember("fanfic_$ff_slug", 60*60, function () use ($ff_slug) {
            return Fanfiction::where('slug', $ff_slug)->first();
        });

        // Перевірка, чи користувач має доступ до фанфіка
        $this->authorize('isAuthor', $fanfic);

        $request->validate([
            'chapter_num' => ['required', 'array', new ChaptersBelongToFanfic($request->chapter_num, $fanfic)],
        ]);

        $sequence = [];
        foreach ($request->chapter_num ?? [] as $chapterId => $num) {
            $temp = $num;
            do { // вперше ця штука сталася корисною, піздець
                $temp++;
            } while (isset($sequence[$temp]));

            $sequence[$temp] = $chapterId;
        }

        ksort($sequence);

        $fanfic->clearCache();
        $fanfic->chapters_sequence = array_values($sequence);
        $fanfic->save();

        return back();
    }

    public function review(Request $request, string $ff_slug, string $chapter_slug)
    {   // ReviewAction
        // Залишити відгук під розділом

        $fanfic = Cache::remember("fanfic_$ff_slug", 60*60, function () use ($ff_slug) {
            return Fanfiction::where('slug', $ff_slug)->first();
        });

        $chapter = Chapter::firstCached($chapter_slug);

        // Перевірка, чи належить розділ до фанфіка
        $this->authorize('chapterBelongToFanfic', [$fanfic ?? null, $chapter ?? null]);

//        // Перевірка, чи не чорнетка фанфік
//        $this->authorize('fanficIsntDraft', $chapter ?? null);
//
//        // Перевірка, чи не чорнетка розділ
//        $this->authorize('chapterIsntDraft', $chapter ?? null);

        $request->validate([
            'comment' => ['required'],
//            'answer_to_review' => ['exists:App\Models\Review,id'],
//            'answer_to_user' => ['exists:App\Models\User,id'],
        ]);

        $user = Auth::user()->id;

        $newReview = Review::create([
            'user_id' => $user,
            'chapter_id' => $chapter->id,
            'answer_to_review' => $request->answer_to_review ?? null,
            'answer_to_user' => $request->answer_to_user ?? null,
            'content' => $request->comment
        ]);

        Review::clearChapterCache($chapter);

        $data = [
            'user' => $newReview->user,
            'content' => $newReview->content,
            'created_at' => $newReview->created_at->format('Y-m-d H:i:s'),
            'answer_to' => $request->answer_to_review ?? null,
        ];

        return response()->json($data);

    }

}
