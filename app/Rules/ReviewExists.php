<?php

namespace App\Rules;

use App\Models\Fandom;
use App\Models\Review;
use Illuminate\Contracts\Validation\Rule;

class ReviewExists implements Rule
{
    /**
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {   // Перевіряє, чи існує певний фандоми

        if ($value === null) return true;

        return Review::where('id', $value)->exists();
    }

    /**
     * @return string
     */
    public function message()
    {
        return 'Коментаря, на який ви намагаєтеся поскаржитися вже не існує.';
    }
}
