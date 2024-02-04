<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\FandomController;
use App\Http\Controllers\FilterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;


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

// Сторінки пов'язані з реєстрацією і автризацією

// Сторінка з формою реєстрації
Route::get('/registration',
    [AuthController::class, 'registrationPage']
)->middleware('guest')->name('RegistrationPage');

// Виконання реєстрації
Route::post('/registration',
    [AuthController::class, 'registration']
)->middleware('guest')->name('RegistrationAction');

// Сторінка з формою авторизації
Route::get('/login',
    [AuthController::class, 'loginPage']
)->middleware('guest')->name('LoginPage');

// Виконання авторизації
Route::post('/login',
    [AuthController::class, 'login']
)->middleware('guest')->name('LoginAction');

// Виход з профіля
Route::get('/logout',
    [AuthController::class, 'logout']
)->middleware('auth')->name('LogoutAction');

// Сторінки пов'язані з профілем
Route::get('/profile',
    [AuthController::class, 'logout']
)->middleware('auth')->name('MyProfilePage');


