<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
