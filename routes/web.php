<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\FandomController;
use App\Http\Controllers\FanficitonController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\UserProfileController;
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

// Сторінка з інформацією про профіль
Route::get('/profile/info',
    [UserProfileController::class, 'profileInfo']
)->middleware('auth')->name('MyProfilePage');

// Виконання зміни аватарки профілю
Route::post('/profile/info/avatar',
    [UserProfileController::class, 'avatarUpload']
)->middleware('auth')->name('AvatarUploadAction');

// Сторінка з формою створення фанфіка
Route::get('/profile/fanfic/create',
    [UserProfileController::class, 'fanficCreate']
)->middleware('auth')->name('FanficCreatePage');

// Сторінка з переліком усіх фанфіків
Route::get('/profile/fanfics',
    [UserProfileController::class, 'fanficsList']
)->middleware('auth')->name('FanficListPage');

// Сторінки пов'язані з фанфіками

// Створення фанфіка
Route::post('/profile/fanfic/create',
    [FanficitonController::class, 'create']
)->middleware('auth')->name('FanficCreateAction');

// Перегляд фанфіка
Route::get('/fanfic/{slug}',
    [FanficitonController::class, 'view']
)->name('FanficPage');
