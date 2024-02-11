<?php

namespace App\Rules;

use App\Models\AgeRating;
use App\Models\Category;
use App\Models\Fanfiction;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Cache;

class UserNotOwnedFanfic implements Rule
{
    /**
     * @return void
     */
    private Fanfiction $fanfiction;

    public function __construct(Fanfiction $fanfiction)
    {
        $this->fanfiction = $fanfiction;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {   // Перевіряє, чи не належить користувачу певний фанфік
        $user = User::where('email', $value)->first();

        return !($user->id == $this->fanfiction->author_id);
    }

    /**
     * @return string
     */
    public function message()
    {
        return 'Не можна додати самого себе.';
    }
}
