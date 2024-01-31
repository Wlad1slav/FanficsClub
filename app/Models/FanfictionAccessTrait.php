<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

trait FanfictionAccessTrait
{
    public function fanfictions(): HasMany
    {   // Отримати усі фанфікі, що мають рядок певной моделі
        return $this->hasMany(Fanfiction::class);
    }
}
