<?php

use App\Http\Controllers\CollectionController;
use App\Http\Controllers\FandomController;
use App\Http\Controllers\FilterController;
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
    'Немає фанфіка?' => [
        'Запросити переклад' => '#',
        'Запросити перенос' => '#',
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

// Сторінки пов'язані з колекціями

// Певна колекція
Route::get('/collection/{id}',
    [CollectionController::class, 'certainCollection']
)->name('CertainCollectionPage');

// Сторінки пов'язані з фільтром фанфіків

// Певна колекція
Route::get('/filter',
    [FilterController::class, 'index']
)->name('FilterPage');
