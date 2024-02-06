<?php

namespace App\Rules;

use App\Models\Category;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Cache;

class CategoryExists implements Rule
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
    {   // Перевіряє, чи існує категорія.
        // Відбувається кешування запитів, для оптимізації перевірки.
        // Кеш зберігається 60*60*24 секунд, це одна доба.
        // Якщо в кеші не знайдено даних по певной категорії,
        // то відбувається анонімна функція.

        if (is_string($value))
            $exists = Cache::remember("category_exists_{$value}", 60*60*24, function () use ($value) {
                return Category::where('id', $value)->exists();
            });

        // Якщо передан масив категорій, то цикл проходить по усім, шукаючи неіснуючу
        // Неіснуюча категорія зупиняє цикл, в підсумку повертая false
        else
            foreach ($value as $v) { // $v - одна категорія
                $exists = Cache::remember("category_exists_{$v}", 60*60*48, function () use ($v) {
                    return Category::where('id', $v)->exists();
                });
                if (!$exists) break;
            }


        return $exists;
    }

    /**
     * @return string
     */
    public function message()
    {
        return 'Цієї категорії не існує.';
    }
}
