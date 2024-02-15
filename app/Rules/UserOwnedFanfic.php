<?php

namespace App\Rules;

use App\Models\AgeRating;
use App\Models\Category;
use App\Models\Fanfiction;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserOwnedFanfic implements Rule
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
    {   // Перевіряє, чи належить фанфік користувачу

        $fanfic = Fanfiction::find($value);
        $user = Auth::user();

        if ($fanfic === null) return true; // Якщо фанфіка не існує, то повертається true

        return $user->id == $fanfic->author_id;
    }

    /**
     * @return string
     */
    public function message()
    {
        return 'Вам не належить цей фанфік.';
    }
}
