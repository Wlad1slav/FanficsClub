<?php

namespace App\Rules;

use App\Models\Fandom;
use App\Models\Tag;
use Illuminate\Contracts\Validation\Rule;

class TagsExists implements Rule
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
    {   // Перевіряє, чи існують усі задані користувачем теґи

        // Перетворення заданих фандомів на масив
        $tagsSelected = array_filter(preg_split('/,\s?/', $value));

        // Отримання з бази даних усіх теґів по їх назві,
        // якщо вони існують
        $tagsIds = Tag::whereIn('name', $tagsSelected)->pluck('id')->toArray();

        // Якщо якогось з теґів, заданих користувачем, не існує,
        // то довжина масивів не буде збігатися
        if (count($tagsIds) !== count($tagsSelected))
            return false;

        return true;
    }

    /**
     * @return string
     */
    public function message()
    {
        return 'Переконайтеся, що усі зі заданих вами теґи існують.';
    }
}
