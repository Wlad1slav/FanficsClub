<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Subscribe extends Model
{
    use HasFactory;

    protected $table = 'subscribes';
    protected $guarded = [];

    public function fanfiction(): BelongsTo
    {   // Зв'язок з моделю Fanfiction
        return $this->belongsTo(Fanfiction::class);
    }

    public static function clearUserCache(?User $user): void
    {   // Видалення кешу підписок користувача
        Cache::pull("subscribes_$user->id");
    }
}
