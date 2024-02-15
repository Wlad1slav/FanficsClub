<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\FandomController;
use App\Http\Controllers\FanficitonController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\RequestsController;
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
    [FandomController::class, 'fandomCategories']
)->name('FandomsCategoriesPage');
Route::redirect('/fandom', '/fandoms');

// Усі фандоми за певною категорією
Route::get('/fandoms/{category_slug}',
    [FandomController::class, 'certainCategory']
)->name('CertainCategoryPage');

// Сторінки пов'язані з колекціями

// Певна колекція
//Route::get('/collection/{id}',
//    [CollectionController::class, 'view']
//)->name('CollectionPage');
//
//// Сторінка з усіма збереженними колекціями користувача і тими, шо створив він
//Route::get('/profile/collections',
//    [CollectionController::class, 'collectionList']
//)->name('CollectionListPage');

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

// Сторінка з підписками користувача
Route::get('/profile/subscribes',
    [UserProfileController::class, 'subscribes']
)->middleware('auth')->name('SubscribesListPage');

// Сторінка з усіма фанфіками, до яких користувач має доступ
Route::get('/profile/access',
    [UserProfileController::class, 'access']
)->middleware('auth')->name('AccessFanficsListPage');

// Сторінки пов'язані з фанфіками

// Сторінка з формою створення фанфіка
Route::get('/profile/fanfic/create',
    [UserProfileController::class, 'fanficCreate']
)->middleware('auth')->name('FanficCreatePage');

// Створення фанфіка
Route::post('/profile/fanfic/create',
    [FanficitonController::class, 'create']
)->middleware('auth')->name('FanficCreateAction');

// Форма редагування фанфіка
Route::get('/profile/fanfic/{ff_slug}/edit',
    [FanficitonController::class, 'editPage']
)->middleware('auth')->name('FanficEditPage');

// Редагування фанфіка
Route::post('/profile/fanfic/{ff_slug}/edit',
    [FanficitonController::class, 'edit']
)->middleware('auth')->name('FanficEditAction');

// Перегляд фанфіка
Route::get('/fanfic/{ff_slug}/chapter/{chapter_slug?}',
    [FanficitonController::class, 'view']
)->name('FanficPage');

// Сторінка з переліком усіх фанфіків
Route::get('/profile/fanfics',
    [UserProfileController::class, 'fanficsList']
)->middleware('auth')->name('FanficListPage');

// Сторінка з таблицею користувачів, які мають доступ до фанфіка
Route::get('/fanfic-edit/access/{ff_slug}/',
    [FanficitonController::class, 'usersAccess']
)->middleware('auth')->name('UsersAccessPage');

// Додати соавтора чи редактора в фанфік
Route::post('/fanfic-edit/access/{ff_slug}/add-{right}',
    [FanficitonController::class, 'giveAccessToFanfic']
)->name('GiveAccessAction');

// Прибрати доступ у певного користувача
Route::get('/fanfic-edit/access/{ff_slug}/put-{userId}',
    [FanficitonController::class, 'putUserAccess']
)->name('PutUserAccessAction');

// Сторінка зі статистокою по фанфіку
Route::get('/fanfic-edit/statistic/{ff_slug}',
    [FanficitonController::class, 'statistic']
)->name('StatisticFanficPage');

// Сторінки пов'язані з розділами

// Форма для створення нового розділа
Route::get('/fanfic-edit/{ff_slug}/chapter/create/',
    [ChapterController::class, 'createForm']
)->middleware('auth')->name('ChapterCreatePage');

// Створення нового розділа
Route::post('/fanfic-edit/{ff_slug}/chapter/create/',
    [ChapterController::class, 'create']
)->middleware('auth')->name('ChapterCreateAction');

// Форма для редагування розділа
Route::get('/fanfic-edit/{ff_slug}/chapter/{chapter_slug}/',
    [ChapterController::class, 'editForm']
)->middleware('auth')->name('ChapterEditPage');

// Редагування певного розділу
Route::post('/fanfic-edit/{ff_slug}/chapter/{chapter_slug}/',
    [ChapterController::class, 'edit']
)->middleware('auth')->name('ChapterEditAction');

// Видалення розділу
Route::get('/fanfic-edit/{ff_slug}/chapter/delete/{chapter_slug}/',
    [ChapterController::class, 'delete']
)->middleware('auth')->name('ChapterDeleteAction');

// Перехід на певний розділ
Route::post('/fanfic/{ff_slug}/',
    [ChapterController::class, 'select']
)->name('ChapterSelectAction');

// Сторінка з усіма розділами фанфіка
Route::get('/fanfic-edit/{ff_slug}/chapters',
    [ChapterController::class, 'chaptersList']
)->middleware('auth')->name('ChapterListPage');

// Зміна послідовності розділів фанфіка
Route::post('/fanfic-edit/{ff_slug}/chapters',
    [ChapterController::class, 'changeSequence']
)->middleware('auth')->name('ChapterSequenceChange');

// Залишити відгук під розділом
Route::get('/fanfic/{ff_slug}/chapter/{chapter_slug}/review',
    [ChapterController::class, 'review']
)->middleware('auth')->name('ReviewAction');

// Сторінки пов'язані з рейтингом фанфіків

// Подобайка фанфіку
Route::get('/fanfic/{ff_slug}/like',
    [FanficitonController::class, 'giveLike']
)->middleware('auth')->name('GiveLikeAction');

// Дісподобайка фанфіку
Route::get('/fanfic/{ff_slug}/dislike',
    [FanficitonController::class, 'giveDislike']
)->middleware('auth')->name('GiveDislikeAction');

// Підписатися на фанфік
Route::get('/fanfic/{ff_slug}/subscribe',
    [FanficitonController::class, 'subscribe']
)->middleware('auth')->name('SubscribeAction');

// Сторінки пов'язані з запитами користувачів

// Форма з відправкою запиту на переклад з іншої мови
Route::get('/user-request/fanfic-robot-translate',
    [RequestsController::class, 'fanficTranslatePage']
)->name('FanficTranslatePage');

// Відправка запиту на переклад з іншої мови
Route::post('/user-request/fanfic-robot-translate',
    [RequestsController::class, 'fanficTranslate']
)->name('FanficTranslateAction');


