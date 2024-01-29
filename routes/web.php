<?php

use App\Http\Controllers\FandomController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

global $navigation;
$navigation = [
    'Популярне' => [
        'Фанфіки' => '#',
        'Серії' => '#',
        'Автори' => '#',
    ],
    'Фандоми' => [
        'Усі фандоми' => '#'
    ],
    'Шукати' => [
        'Фандом' => '#',
        'Фанфік' => '#',
        'Серію' => '#',
        'Автора' => '#',
    ],
];

Route::get('/doc', function () {
    return view('welcome');
});

// Головна сторінка
Route::get('/',
    [HomeController::class, 'index']
)->name('HomePage');


// Сторінки пов'язані з фандомами

// Усі категорії фандомів
Route::get('/fandoms',
    [FandomController::class, 'fandomsCategories']
)->name('FandomsCategoriesPage');
Route::redirect('/fandom', '/fandoms');

// Усі фандоми за певною категорією
Route::get('/fandoms/{category_slug}',
    [FandomController::class, 'certainCategory']
)->name('CertainCategoryPage');

// Певний фандом
Route::get('/fandom/{slug}',
    [FandomController::class, 'certainFandom']
)->name('CertainFandomPage');
