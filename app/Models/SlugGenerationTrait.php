<?php

namespace App\Models;

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

    private function getSlug(string $str): string {
        return Str::slug(self::transliterationUkranianCharacters($str));
    }

}
