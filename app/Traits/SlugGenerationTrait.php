<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait SlugGenerationTrait
{

//    const TRANSLITERATION = [
//        // Літери, правильну транслітерацію яких,
//        // метод Str::slug не підтримує
//        'г' => 'h', 'ґ' => 'g',
//        'е' => 'e', 'є' => 'ie',
//        'и' => 'y', 'і' => 'i',
//        'й' => 'j',
//    ];

    private static function transliterationUkranianCharacters(string $str): string {
        // Замінює усі українські літери,
        // що не підтримують методом Str::slug на коректні
        $transliteration = include "../config/custom.php";
        return str_replace(
            array_keys($transliteration['ukranian_transliteration']),
            array_values($transliteration['ukranian_transliteration']),
            mb_strtolower($str)
        );
    }

    private static function getSlug(string $str): string {
        return Str::slug(self::transliterationUkranianCharacters($str));
    }

    private static function createOriginalSlug(string $str, $model, string $column='slug'): string
    {   // Створення оригінального slug

        $slug = self::getSlug($str); // Генерація slug
        $duplicates = $model::where($column, $slug)->get()->count(); // Отримання кількості рядків з таким же slug

        if ($duplicates > 0) { // Якщо кількість slug більше нуля, то додається номер фанфіка з таким же slug
            while (true) {
                if ($model::where($column, "$slug-$duplicates")->get()->count() > 0)
                    $duplicates++;
                else return "$slug-$duplicates";
            }
        }

        return $slug;
    }

}
