<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Fanfiction;
use App\Traits\SlugGenerationTrait;
use Illuminate\Http\Request;
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
        $this->authorize('fanficAccess', $fanfic);

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
        $this->authorize('fanficAccess', $fanfic);

        $request->validate([
            'chapter_title' => ['required', 'string'],
            'chapter_content' => ['required', 'string'],
        ]);

        $chapter = Chapter::create([
            'fanfiction_id' => $fanfic->id,
            'title' => $request->chapter_title,
            'slug' => self::createOriginalSlug($request->chapter_title, new Chapter()),
            'content' => $request->chapter_content,
            'additional_descriptions' => json_encode([
                'notify' => $request->notify ?? null,
                'notes' => $request->notes ?? null,
            ]),

            // Якщо value is_draft рівен одному, то розділ зберігається, як чорнетка
            'is_draft' => ($request->is_draft ?? 0) == 1
        ]);

        // Додавання нового розділу в масив з послідовносттю розділів в фанфіку
        if ($fanfic->chapters_sequence === null)
            $fanfic->chapters_sequence = json_encode([$chapter->id]);
        else {
            $sequence = json_decode($fanfic->chapters_sequence, true);
            $sequence[] = $chapter->id;
            $fanfic->chapters_sequence = json_encode($sequence);
        }

        $fanfic->save();
        $fanfic->clearCache(); // Видалення фанфіку з кешу

        return redirect(route('FanficPage', [
                'ff_slug' => $fanfic->slug,
                'chapter_slug' => $chapter->slug,
            ]) . '#chapter');

    }

    public function editForm(Request $request, string $ff_slug, string $chapter_slug)
    {   // ChapterEditPage
        // Форма редагування розділу для певного фанфіка

        $fanfic = Cache::remember("fanfic_$ff_slug", 60*60, function () use ($ff_slug) {
            return Fanfiction::where('slug', $ff_slug)->first();
        });

        // Перевірка, чи користувач має доступ до фанфіка
        $this->authorize('fanficAccess', $fanfic);

        $chapter = Chapter::firstCached($chapter_slug);

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
        $this->authorize('fanficAccess', $fanfic);

        $request->validate([
            'chapter_title' => ['required', 'string'],
            'chapter_content' => ['required', 'string'],
        ]);

        $chapter = Chapter::firstCached($chapter_slug);

        if ($chapter->title !== $request->chapter_title)
            $slug = self::createOriginalSlug(
                $request->chapter_title ?? "Розділ " . ($fanfic->chapters->count() + 1) . "-$fanfic->id",
                new Chapter());
        else $slug = $chapter->slug;

        $chapter->update([
            'title' => $request->chapter_title ?? "Розділ " . ($fanfic->chapters->count() + 1),
            'slug' => $slug,
            'content' => $request->chapter_content,
            'additional_descriptions' => json_encode([
                'notify' => $request->notify ?? null,
                'notes' => $request->notes ?? null,
            ]),

            // Якщо value is_draft рівен одному, то розділ зберігається, як чорнетка
            'is_draft' => ($request->is_draft ?? 0) == 1
        ]);

        $fanfic->clearCache(); // Видалення фанфіку з кешу
        $chapter->clearCache(); // Видалення розділу з кешу

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
        $this->authorize('fanficAccess', $fanfic);

        $sequence = [];
        foreach ($request->chapter_num ?? [] as $chapterId => $num) {
            $temp = $num;
            do { // вперше ця штука сталася корисною, піздець
                $temp++;
            } while (isset($sequence[$temp]));

            $sequence[$temp] = $chapterId;
        }

        ksort($sequence);

        $fanfic->chapters_sequence = json_encode(array_values($sequence));
        $fanfic->save();
        $fanfic->clearCache();

        return back();

    }

}