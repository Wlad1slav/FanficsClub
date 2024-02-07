<?php

use App\Models\Fandom;
use Illuminate\Support\Facades\Cache;

function getFandomsForNavigation(): array
{
    $fandoms = [];
    $all = Cache::remember("top_fandoms", 60*60*12, function () {
        return Fandom::orderBy('fictions_amount', 'desc')->take(5)->get();
    });

    foreach ($all as $fandom)
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
