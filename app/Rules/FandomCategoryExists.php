<?php

namespace App\Rules;

use App\Models\FandomCategories;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Cache;

class FandomCategoryExists implements Rule
{
    /**
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {   // Перевіряє, чи існує категорія фандомів.

        return Cache::remember("fandom_category_exists_$value", 60*60*168, function () use ($value) {
            return FandomCategories::where('id', $value)->exists();
        });
    }

    /**
     * @return string
     */
    public function message()
    {
        return 'Цієї категорії не існує.';
    }
}
