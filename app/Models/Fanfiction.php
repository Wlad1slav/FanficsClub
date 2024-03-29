<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use TCPDF;

class Fanfiction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'fanfictions';
    protected $guarded = [];

    protected $casts = [
        'users_with_access' => 'array',
        'chapters_sequence' => 'array',
        'fandoms_id' => 'array',
        'tags' => 'array',
        'characters' => 'array',
    ];

    public function category(): BelongsTo
    {   // Зв'язок з моделю Category
        // для кожного екземпляра Fanfiction можна отримати категорію
        return $this->belongsTo(Category::class);
    }

    public function age_rating(): BelongsTo
    {   // Зв'язок з моделю AgeRating
        // для кожного екземпляра Fanfiction можна отримати віковий рейтинг
        return $this->belongsTo(AgeRating::class);
    }

    public function author(): BelongsTo
    {   // Зв'язок з моделю User
        return $this->belongsTo(User::class);
    }

    public function prequel(): BelongsTo
    {   // Повертає фанфік, який є приквелом до цього
        return $this->belongsTo(self::class);
    }

    public function sequel(): ?Fanfiction
    {   // Повертає фанфік, який є сиквелом фанфіку

        return Fanfiction::where('prequel_id', $this->id)
            ->where('is_draft', false)
            ->first();
    }

//    public function fandom(): BelongsTo
//    {   // Зв'язок з моделю Fandom
//        // для кожного екземпляра Fanfiction можна отримати фандом
//        return $this->belongsTo(Fandom::class);
//    }

    public function chapters(): HasMany
    {   // Отримати усі глави, що належать певному фанфіку
        return $this->hasMany(Chapter::class);
    }

    public function likes(): HasMany
    {   // Отримати усі лайки, що були поставлені фанфіку
        return $this->hasMany(Like::class, 'fanfiction');
    }

    public function dislikes(): HasMany
    {   // Отримати усі діслайки, що були поставлені фанфіку
        return $this->hasMany(Dislike::class, 'fanfiction');
    }

    public function views(): HasMany
    {   // Отримати усі перегляди фанфіку
        return $this->hasMany(View::class);
    }

    public function subscribes(): HasMany
    {   // Отримати усі підписки на фанфік
        return $this->hasMany(Subscribe::class);
    }

    public function getTagsAttribute(): Collection
    {   // Повертає масив Laravel колекцій тегів фанфіку
        // ->tags
        $tagIds = json_decode($this->attributes['tags'], true) ?? [];
        return Tag::findMany($tagIds);
    }

    public function getFandomsAttribute(): Collection
    {   // Повертає масив Laravel колекцій фандомів
        // ->fandoms
        $fandomsIds = json_decode($this->attributes['fandoms_id'], true) ?? [];
        return Fandom::findMany($fandomsIds);
    }

    // Відношення до Тегів
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'fanfiction_tag', 'fanfiction_id', 'tag_id');
    }

    // Відношення до Фандомів
    public function fandoms()
    {
        return $this->belongsToMany(Fandom::class, 'fanfiction_fandom', 'fanfiction_id', 'fandom_id');
    }

    public function usersWithAccess()
    {   // Усі користувачі, що мають доступ до фанфіку
        return Cache::remember("users_with_access_$this->slug", 60*60*7, function () {
            // Витягуються id усіх користувачів, записані в колонці users_with_access таблиці фанфіків.
            // Айді користувачів виступають у ролі ключів до рівня їх прав в колонці users_with_access.
            // Використовуються в якості масиву для пошуку усіх в таблиці users.
            $ids = array_keys($this->users_with_access ?? []);
            return User::whereIn('id', $ids)->get();
        });
    }

    // Очищає кеш певного фанфіка
    public function clearCache()
    {
        // Видалення фанфіка з кешу
        Cache::pull("fanfic_{$this->slug}");
        Cache::pull("last_updated_ff");

        // Видалення усіх користувачів, що мають доступ до фанфіку з кешу
        Cache::pull("users_with_access_{$this->slug}");

        // Видалення усіх розділів фанфіка з кешу
        Cache::pull("chapters_ff_{$this->id}");
        Cache::pull("chapters_ff_{$this->id}_all_ids_array");
    }

    // Метод для підрахування кількості слів в фанфіку
    public function countWordsAmount(): int
    {
        $amount = 0;

        $chapters = Chapter::getCached($this);

        foreach ($chapters as $chapter) {
            preg_match_all("/\b[\p{L}\p{N}]+\b/u", $chapter->content, $matches);
            $amount += count($matches[0]);
        }

        return $amount;
    }

    // Метод для оновлення довжини фанфіку (у словах)
    public function refreshWordsAmount(): void
    {
        $this->update([
            'words_amount' => $this->countWordsAmount()
        ]);
        $this->clearCache();
    }

    // Метод для оновлення сіквенції розділів фанфіку
    public function refreshSequence(): void
    {
        $this->update([
            'chapters_sequence' => $this->chapters->pluck('id')
        ]);
        $this->clearCache();
    }

    // Метод для збереження фанфіку в форматі pdf
    public function pdf()
    {
        // Створення нового PDF документа
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Встановлення інформації про документ
        $pdf->SetCreator('fanfics.com.ua');
        $pdf->SetAuthor($this->author);
        $pdf->SetTitle($this->title);

        // Встановлення шапки та підвалу документа
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // Встановлення маржі
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Встановлення автоматичного перенесення сторінок
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Опис фанфіка
        $pdf->AddPage();
        $pdf->SetFont('dejavusans', 'B', 20);
        $pdf->Write(0, $this->title, '', 0, 'C', true, 0, false, false, 0);
        $pdf->Ln(10);
        $pdf->SetFont('dejavusans', 'I', 16);
        $pdf->Write(0, $this->description, '', 0, 'L', true, 0, false, false, 0);
        $pdf->Ln(5);
        $pdf->Write(0, route('FanficPage', ['ff_slug' => $this->slug]), '', 0, 'L', true, 0, false, false, 0);

        $chapters = Chapter::getCached($this);

        // Додавання тексту
        foreach ($chapters as $chapter) {
            if ($chapter->is_draft) continue;

            // Додавання сторінки
            $pdf->AddPage();

            // Встановлення шрифту для заголовка: жирний і більший розмір
            $pdf->SetFont('dejavusans', 'B', 16);

            // Додавання назви розділу як заголовку
            $pdf->Write(
                0,
                $chapter->title,
                route('FanficPage', ['ff_slug' => $this->slug, 'chapter_slug' => $chapter->slug]),
                0, 'C', true, 0, false, false, 0);

            // Відступ після заголовку
            $pdf->Ln(10); // Відступ 10 мм

            // Встановлення шрифта тексту
            $pdf->SetFont('dejavusans', '', 12);

            // Розбивання вмісту розділу на абзаці
            $paragraphs = explode("\n", $chapter->content);

            foreach ($paragraphs as $paragraph) { // Додавання вмісту розділу
                // Додавання абзацу
                $pdf->Write(0, $paragraph, '', 0, 'L', true, 0, false, false, 0);
                // Додавання відступу після абзацу
                $pdf->Ln(5);
            }
        }

        $path = public_path("storage/fanfics/{$this->slug}.pdf");

        $pdf->Output($path, 'F'); // 'F' означає, що файл буде збережено на сервері
    }

    public function fb2()
    {
        $xml = new \SimpleXMLElement('<FictionBook xmlns="http://www.gribuser.ru/xml/fictionbook/2.0" encoding="UTF-8"></FictionBook>');

        // Додавання метаданих та іншої інформації
        $description = $xml->addChild('description');

        // Додавання title-info
        $titleInfo = $description->addChild('title-info');
        $bookTitle = $titleInfo->addChild('book-title', $this->title);

        $author = $titleInfo->addChild('author');
        $name = $author->addChild('first-name',
            $this->is_translate ? $this->original_author : $this->author->name);

        $genre = $titleInfo->addChild('genre', 'fanfic');

        // Додавання document-info
        $documentInfo = $description->addChild('document-info');
        $authorOfDoc = $documentInfo->addChild('author');
        $nickname = $authorOfDoc->addChild('nickname', $this->author->name);
        $date = $documentInfo->addChild('date', date('Y-m-d')); // Текущая дата

        $chapters = Chapter::getCached($this);

        // Додавання розділів і їхнього контенту
        foreach ($chapters as $chapter) {
            if ($chapter->is_draft) continue;

            $section = $xml->addChild('section');
            $section->addChild('title', $chapter->title);

            // Розбивання вмісту розділу на абзаці
            $paragraphs = explode("\n", $chapter->content);

            foreach ($paragraphs as $paragraph) { // Додавання вмісту розділу
                $p = $section->addChild('p');
                $p[0] = $paragraph;
            }
        }

        // Генерація XML
        $xmlContent = $xml->asXML();

        $path = public_path("storage/fanfics/{$this->slug}.fb2");

        // Віддача файлу
        $xml->asXML($path);
    }

    public static function getCached($ff_slug): self
    {
        return Cache::remember("fanfic_$ff_slug", 60 * 60, function () use ($ff_slug) {
            // Передбачається, що фанфік в кешу зберігається 1 годину,
            // але якщо користувач його оновить, то кеш автоматом очиститься
            return Fanfiction::where('slug', $ff_slug)
                ->with('author')
                ->with('category')
                ->with('age_rating')
                //->with('likes')
                //->with('dislikes')
                ->with('prequel')
                ->with('views')
                ->first();
        });
    }

}
