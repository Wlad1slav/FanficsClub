<?php

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
