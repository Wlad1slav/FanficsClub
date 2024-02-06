<?php

namespace App\Traits;

trait ConvertStringAttributesTrait
{
    public static function convertStrAttrToArray(?string $str): array
    {   // Конвертація строки з атрибутами у масив з id рядків моделі

        if ($str === null) return [];

        // Конвертація строки у масив назв
        $names = array_filter(preg_split('/,\s?/', $str));

        // Знаходження по назві усіх рядків в моделі
        // і створення масивух їх айдішників
        return self::whereIn('name', $names)->pluck('id')->toArray();
    }
}
