<?php

namespace App\Rules;

use App\Models\Fandom;
use Illuminate\Contracts\Validation\Rule;

class FandomsExists implements Rule
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
    {   // Перевіряє, чи існують усі задані користувачем фандоми

        // Перетворення заданих фандомів на масив
        $fandomsSelected = array_filter(preg_split('/,\s?/', $value));

        // Отримання з бази даних усіх фандомів по їх назві,
        // якщо вони існують
        $fandomsIds = Fandom::whereIn('name', $fandomsSelected)->pluck('id')->toArray();

        // Якщо якогось з фандомів, заданих користувачем, не існує,
        // то довжина масивів не буде збігатися
        if (count($fandomsSelected) !== count($fandomsIds))
            return false;

        return true;
    }

    /**
     * @return string
     */
    public function message()
    {
        return 'Переконайтеся, що усі з заданих вами фандомів існують.';
    }
}
