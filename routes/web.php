<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\FandomController;
use App\Http\Controllers\FanficitonController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\RequestsController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Головна сторінка
Route::get('/', [HomeController::class, 'index'] )->name('HomePage');

////////////////////////////////////
// МАРШРУТИ ПОВ'ЯЗАНІ З ФАНДОМАМИ //
////////////////////////////////////

// Усі категорії фандомів
Route::get('/fandoms', [FandomController::class, 'fandomCategories'] )->name('FandomsCategoriesPage');
Route::redirect('/fandom', '/fandoms');

// П'ядесят найпопулярніших фандомів
Route::get('/fandoms/top', [FandomController::class, 'top'] )->name('TopFandomsPage');

// Усі фандоми за певною категорією
Route::get('/fandoms/{category_slug}', [FandomController::class, 'certainCategory'] )->name('CertainCategoryPage');

////////////////////////////////////////////
// МАРШРУТИ ПОВ'ЯЗАНІ З ФІЛЬТРОМ ФАНФІКІВ //
////////////////////////////////////////////

// Певна колекція
Route::get('/filter', [FilterController::class, 'index'] )->name('FilterPage');

/////////////////////////////////////////////////////
// МАРШРУТИ ПОВ'ЯЗАНІ З РЕЄСТРАЦІЄЮ І АВТОРИЗАЦІЄЮ //
/////////////////////////////////////////////////////

// Сторінка з формою реєстрації
Route::get('/registration', [AuthController::class, 'registrationPage'] )->middleware('guest')->name('RegistrationPage');
Route::post('/registration', [AuthController::class, 'registration'] )->middleware('guest')->name('RegistrationAction');

// Сторінка з формою авторизації
Route::get('/auth', [AuthController::class, 'loginPage'] )->middleware('guest')->name('LoginPage');
Route::post('/auth', [AuthController::class, 'login'] )->middleware('guest')->name('LoginAction');

// Виход з профіля
Route::get('/logout', [AuthController::class, 'logout'] )->middleware('auth')->name('LogoutAction');

// Відновлення паролю
Route::get('/forgot-password', [ResetPasswordController::class, 'forgotPasswordPage'] )->middleware('guest')->name('ForgotPasswordPage');
Route::post('/forgot-password', [ResetPasswordController::class, 'forgotPassword'] )->middleware('guest')->name('ForgotPasswordAction');

Route::get('/reset-password', [ResetPasswordController::class, 'create'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'store'])->middleware('guest')->name('password.update');

//Auth::routes(['verify' => true]);

// Сторінка з повідомленням про те, що треба підтвердити пошту
Route::get('/email/verify', [AuthController::class, 'verifyNote'])->middleware('auth')->name('verification.notice');

// Підтверження пошти
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->middleware(['auth', 'signed'])->name('verification.verify');


///////////////////////////////////
// МАРШРУТИ ПОВ'ЯЗАНІ З ПРОФІЛЕМ //
///////////////////////////////////

// Сторінка з інформацією про профіль
Route::get('/profile/info', [UserProfileController::class, 'profileInfo'] )->middleware(['auth', 'verified'])->name('MyProfilePage');

// Зміна аватарки профілю
Route::post('/profile/info/avatar', [UserProfileController::class, 'avatarUpload'] )->middleware(['auth', 'verified'])->name('AvatarUploadAction');

// Сторінка з підписками користувача
Route::get('/profile/subscribes', [UserProfileController::class, 'subscribes'] )->middleware(['auth', 'verified'])->name('SubscribesListPage');

// Сторінка з усіма фанфіками, до яких користувач має доступ
Route::get('/profile/access', [UserProfileController::class, 'access'] )->middleware(['auth', 'verified'])->name('AccessFanficsListPage');

////////////////////////////////////
// МАРШРУТИ ПОВ'ЯЗАНІ З ФАНФІКАМИ //
////////////////////////////////////

// Сторінка з формою створення фанфіка
Route::get('/profile/fanfic/create', [UserProfileController::class, 'fanficCreate'] )->middleware(['auth', 'verified'])->name('FanficCreatePage');
Route::post('/profile/fanfic/create', [FanficitonController::class, 'create'] )->middleware(['auth', 'verified'])->name('FanficCreateAction');

// Форма редагування фанфіка
Route::get('/profile/fanfic/{ff_slug}/edit', [FanficitonController::class, 'editPage'] )->middleware(['auth', 'verified'])->name('FanficEditPage');
Route::post('/profile/fanfic/{ff_slug}/edit', [FanficitonController::class, 'edit'] )->middleware(['auth', 'verified'])->name('FanficEditAction');

// Перегляд фанфіка
Route::get('/fanfic/{ff_slug}/chapter/{chapter_slug?}', [FanficitonController::class, 'view'] )->name('FanficPage');

// Сторінка з переліком усіх фанфіків
Route::get('/profile/fanfics', [UserProfileController::class, 'fanficsList'] )->middleware(['auth', 'verified'])->name('FanficListPage');

// Сторінка з таблицею користувачів, які мають доступ до фанфіка
Route::get('/fanfic-edit/access/{ff_slug}/', [FanficitonController::class, 'usersAccess'] )->middleware(['auth', 'verified'])->name('UsersAccessPage');

// Додати соавтора чи редактора в фанфік
Route::post('/fanfic-edit/access/{ff_slug}/add-{right}', [FanficitonController::class, 'giveAccessToFanfic'] )->middleware(['auth', 'verified'])->name('GiveAccessAction');

// Прибрати доступ у певного користувача
Route::get('/fanfic-edit/access/{ff_slug}/put-{userId}', [FanficitonController::class, 'putUserAccess'] )->middleware(['auth', 'verified'])->name('PutUserAccessAction');

// Сторінка зі статистокою по фанфіку
Route::get('/fanfic-edit/statistic/{ff_slug}', [FanficitonController::class, 'statistic'] )->middleware(['auth', 'verified'])->name('StatisticFanficPage');

// Сторінка з варіантами для завантаження фанфіку
Route::get('/fanfic/{ff_slug}/download', [FanficitonController::class, 'downloadPage'] )->name('DownloadFanficPage');
Route::get('/fanfic/{ff_slug}/download/{format}', [FanficitonController::class, 'download'] )->middleware(['auth', 'verified'])->name('DownloadFanficAction');

////////////////////////////////////
// МАРШРУТИ ПОВ'ЯЗАНІ З РОЗДІЛАМИ //
////////////////////////////////////

// Форма для створення нового розділа
Route::get('/fanfic-edit/{ff_slug}/chapter/create/', [ChapterController::class, 'createForm'] )->middleware(['auth', 'verified'])->name('ChapterCreatePage');
Route::post('/fanfic-edit/{ff_slug}/chapter/create/', [ChapterController::class, 'create'] )->middleware(['auth', 'verified'])->name('ChapterCreateAction');

// Форма для редагування розділа
Route::get('/fanfic-edit/{ff_slug}/chapter/{chapter_slug}/', [ChapterController::class, 'editForm'] )->middleware(['auth', 'verified'])->name('ChapterEditPage');
Route::post('/fanfic-edit/{ff_slug}/chapter/{chapter_slug}/', [ChapterController::class, 'edit'] )->middleware(['auth', 'verified'])->name('ChapterEditAction');

// Видалення розділу
Route::get('/fanfic-edit/{ff_slug}/chapter/delete/{chapter_slug}/', [ChapterController::class, 'delete'] )->middleware(['auth', 'verified'])->name('ChapterDeleteAction');

// Перехід на певний розділ
Route::post('/fanfic/{ff_slug}/', [ChapterController::class, 'select'] )->name('ChapterSelectAction');

// Сторінка з усіма розділами фанфіка
Route::get('/fanfic-edit/{ff_slug}/chapters', [ChapterController::class, 'chaptersList'] )->middleware(['auth', 'verified'])->name('ChapterListPage');

// Зміна послідовності розділів фанфіка
Route::post('/fanfic-edit/{ff_slug}/chapters', [ChapterController::class, 'changeSequence'] )->middleware(['auth', 'verified'])->name('ChapterSequenceChange');

// Залишити відгук під розділом
Route::get('/fanfic/{ff_slug}/chapter/{chapter_slug}/review', [ChapterController::class, 'review'] )->middleware(['auth', 'verified'])->name('ReviewAction');

// Поскаржитися на коментар під розділом
Route::get('/fanfic/{ff_slug}/chapter/{chapter_slug}/review/complain-{review_id}', [ChapterController::class, 'complain'] )->middleware(['auth', 'verified'])->name('ReviewComplainAction');

/////////////////////////////////////////////
// МАРШРУТИ ПОВ'ЯЗАНІ З РЕЙТИНГОМ ФАНФІКІВ //
/////////////////////////////////////////////

// Подобайка фанфіку
Route::get('/fanfic/{ff_slug}/like', [FanficitonController::class, 'giveLike'] )->middleware(['auth', 'verified'])->name('GiveLikeAction');

// Дісподобайка фанфіку
Route::get('/fanfic/{ff_slug}/dislike', [FanficitonController::class, 'giveDislike'] )->middleware(['auth', 'verified'])->name('GiveDislikeAction');

// Підписатися на фанфік
Route::get('/fanfic/{ff_slug}/subscribe', [FanficitonController::class, 'subscribe'] )->middleware(['auth', 'verified'])->name('SubscribeAction');

////////////////////////////////////////////////
// МАРШРУТИ ПОВ'ЯЗАНІ З ЗАПИТАМИ КОРИСТУВАЧІВ //
////////////////////////////////////////////////

// Форма з відправкою запиту на переклад з іншої мови
Route::get('/user-request/fanfic-robot-translate', [RequestsController::class, 'fanficTranslatePage'] )->name('FanficTranslatePage');
Route::post('/user-request/fanfic-robot-translate', [RequestsController::class, 'fanficTranslate'] )->middleware(['auth', 'verified'])->name('FanficTranslateAction');

// Форма з відправкою запиту на перенесення фанфіку з іншого сайту
Route::get('/user-request/fanfic-transfer', [RequestsController::class, 'fanficTransferPage'] )->name('FanficTransferPage');
Route::post('/user-request/fanfic-transfer', [RequestsController::class, 'fanficTransfer'] )->middleware(['auth', 'verified'])->name('FanficTransferAction');

// Форма для додавання фандому
Route::get('/user-request/add/fandom', [RequestsController::class, 'addFandomForm'] )->name('AddFandomPage');
Route::post('/user-request/add/fandom', [RequestsController::class, 'addFandom'] )->middleware(['auth', 'verified'])->name('AddFandomAction');

// Форма для додавання теґу
Route::get('/user-request/add/tag', [RequestsController::class, 'addTagForm'] )->name('AddTagPage');
Route::post('/user-request/add/tag', [RequestsController::class, 'addTag'] )->middleware(['auth', 'verified'])->name('AddTagAction');

// Форма для додавання персонажа
Route::get('/user-request/add/character', [RequestsController::class, 'addCharacterForm'] )->name('AddCharacterPage');
Route::post('/user-request/add/character', [RequestsController::class, 'addCharacter'] )->middleware(['auth', 'verified'])->name('AddCharacterAction');

// Форма для додавання персонажа
Route::get('/user-request/report', [RequestsController::class, 'reportForm'] )->name('ReportPage');
Route::post('/user-request/report', [RequestsController::class, 'report'] )->middleware(['auth', 'verified'])->name('ReportAction');

////////////////////////////////////////////////////
// МАРШРУТИ ПОВ'ЯЗАНІ З ІНФОРМАЦІЙНИМИ СТОРІНКАМИ //
////////////////////////////////////////////////////

// Про сайт (Про нас / Контакти / Мапа сайту)
Route::get('/about', [InformationController::class, 'aboutSite'] )->name('AboutSitePage');

// Правила (Викладення творів / Вміст творів / Коментарі під розділами)
Route::get('/rules', [InformationController::class, 'rules'] )->name('RulesPage');

// Користувацька угода
Route::get('/user-agreement', [InformationController::class, 'userAgreement'] )->name('UserAgreementPage');

// Політика конфіденційності
Route::get('/privacy-policy', [InformationController::class, 'privacyPolicy'] )->name('PrivacyPolicyPage');

//Route::get('/test-mail', [MailSendController::class, 'welcome'] )->name('TestMailSend');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
