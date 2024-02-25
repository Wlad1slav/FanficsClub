<?php

use App\Models\Fandom;
use Illuminate\Support\Facades\Cache;

function getFandomsForNavigation(): array
{
    $fandoms = ['Усі фандоми' => route('FandomsCategoriesPage')];
    $all = Cache::remember("top_fandoms", 60*60*12, function () {
        return Fandom::orderBy('fictions_amount', 'desc')->take(5)->get();
    });

    foreach ($all as $fandom)
        $fandoms[$fandom->name] = route('FilterPage', ['fandoms-selected' => $fandom->name]);
    return $fandoms;
}

return [
    'Популярне' => [
        'Фанфіки' => route('FilterPage', ['sort_by' => 'likes_count', 'type_of_works' => 'fanfic']),
        'Оригінальні твори' => route('FilterPage', ['sort_by' => 'likes_count', 'type_of_works' => 'original']),
        'Фандоми' => route('TopFandomsPage'),
//        'Серії' => '#',
        //'Автори' => '#',
    ],
    'Фандоми' => getFandomsForNavigation(),
    'Шукати' => [
        'Фандом' => route('FandomsCategoriesPage'),
        'Фанфік' => route('FilterPage'),
//        'Серію' => '#',
        //'Автора' => '#',
    ],
    'Немає фанфіка?' => [
        'Запросити ШІ-переклад' => route('FanficTranslatePage'),
        'Запросити перенос' => route('FanficTransferPage'),
    ],
    'Чогось не вистачає?' => [
        'Додати фандом' => route('AddFandomPage'),
        'Додати теґ' => route('AddTagPage'),
        'Додати персонажа' => route('AddCharacterPage'),
    ],
];
