<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';
    protected $guarded = [];

    public function user(): BelongsTo
    {   // Зв'язок з моделю User
        return $this->belongsTo(User::class);
    }

    public function answer_to_review(): BelongsTo
    {   // Зв'язок з моделю Review
        return $this->belongsTo(self::class, 'answer_to_review');
    }

    public function answer_user(): BelongsTo
    {   // Зв'язок з моделю User
        return $this->belongsTo(User::class, 'answer_to_user');
    }

    public function answers(): HasMany
    {   // Повертає усі відповіді на комент
        return $this->hasMany(Review::class, 'answer_to_review');
    }

    public static function getCached(?Chapter $chapter)
    {
        if ($chapter === null) return null;

        return Cache::remember("reviews_chapter_{$chapter->id}", 60*60, function () use ($chapter) {

            return self::where('chapter_id', $chapter->id)
                ->where('answer_to_review', null)
                ->orderBy('created_at', 'desc')
                ->get();
        });
    }

    public static function clearChapterCache(Chapter $chapter): void
    {
        Cache::pull("reviews_chapter_{$chapter->id}");
    }
}
