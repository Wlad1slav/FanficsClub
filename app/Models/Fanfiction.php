<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

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

        foreach ($this->chapters as $chapter) {
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

}
