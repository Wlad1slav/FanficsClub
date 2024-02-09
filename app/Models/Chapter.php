<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Chapter extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'chapters';
    protected $guarded = [];

    public function fanfiction(): BelongsTo
    {   // Зв'язок з моделю Fanfiction
        return $this->belongsTo(Fanfiction::class);
    }

    public static function getCached(Fanfiction $fanfic)
    {
        return Cache::remember("chapters_ff_{$fanfic->id}", 60*60, function () use ($fanfic) {
            // При створенні нового розділу чи оновленні існуючого кеш повинен оновлюватися

            if ($fanfic->chapters_sequence !== null) {
                // Розділи сортируються згідно з заданим порядком в бд
                $sequence = json_decode($fanfic->chapters_sequence, true);
                $sequenceStr = implode(',', $sequence);
                return Chapter::where('fanfiction_id', $fanfic->id)
                    ->whereIn('id', $sequence)
                    ->orderByRaw("FIELD(id, {$sequenceStr})")
                    ->get();
            } else {
                return null;
            }
        });
    }

    public static function firstCached(string $slug)
    {   // Повертає певний розділ по slug
        return Cache::remember("chapter_{$slug}", 60*60, function () use ($slug) {
            // При оновленні розділу, кеш повинен видалятися
            return self::where('slug', $slug)->first();
        });
    }

    // Очищає кеш певного розділа
    public function clearCache(): void
    {
        Cache::pull("chapter_$this->slug");
    }
}
