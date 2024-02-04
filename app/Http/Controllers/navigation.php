<?php

use App\Models\Fandom;

function getFandomsForNavigation(): array
{
    $fandoms = [];
    foreach (Fandom::orderBy('fictions_amount', 'desc')->take(5)->get() as $fandom)
        $fandoms[$fandom->name] = route('FilterPage', ['fandoms-selected' => $fandom->name]);
    return $fandoms;
}

return [
    'Популярне' => [
        'Фанфіки' => '#',
        'Серії' => '#',
        'Автори' => '#',
    ],
    'Фандоми' => getFandomsForNavigation(),
    'Шукати' => [
        'Фандом' => route('FandomsCategoriesPage'),
        'Фанфік' => route('FilterPage'),
        'Серію' => '#',
        'Автора' => '#',
    ],
    'Немає фанфіка?' => [
        'Запросити переклад' => '#',
        'Запросити перенос' => '#',
    ],
];
