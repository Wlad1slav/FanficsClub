<?php

namespace App\Rules;

use App\Models\Fandom;
use Illuminate\Contracts\Validation\Rule;

class FandomExists implements Rule
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

        return Fandom::where('name', $value)->exists();
    }

    /**
     * @return string
     */
    public function message()
    {
        return 'Переконайтеся, що введений фандом інсує.';
    }
}
