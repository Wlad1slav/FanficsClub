@extends('layouts.main')

@section('title')
    {{ $title }} :: Фанфіки українською
@endsection

@section('meta-description')
@endsection


@section('content')

    <link rel="stylesheet" href="{{ asset('css/informational-pages.css') }}">

    <h1>Про сайт</h1>

    <div id="about-us" class="info-section large-text">
        <h2>Про нас</h2>

        <p>Ласкаво просимо на наш сайт — осередок творчості та фантазії, де кожен може знайти щось для себе у величезному світі фанфіків. Ми — команда ентузіастів, яка об'єдналася з однією метою: створити унікальний простір для поклонників фанфіків <span>українською мовою</span>.</p>

        <p><span>Fanfics.com.ua</span> створений як платформа, де автори можуть ділитися своїми творами, а читачі — насолоджуватися необмеженою кількістю історій із своїх улюблених всесвітів. Від класичних фандомів до оригінальних творів — ми підтримуємо творчість у всіх її проявах.</p>

        <p>Ми віримо, що кожен має право на творчість та самовираження. Тому наш сайт надає інструменти для <a
                href="{{ route('FanficCreatePage') }}">створення</a>, <a href="{{ route('FilterPage') }}">пошуку</a> та
            <a href="#">обговорення</a> фанфіків. Ми прагнемо забезпечити дружнє та підтримуюче середовище для усіх учасників нашої спільноти.</p>

        <p>Запрошуємо вас долучитися до нашої спільноти. Незалежно від того, чи ви автор, чи читач, на <span>Fanfics.com.ua</span> ви знайдете місце, де можна поділитися своєю пасією до фанфіків. Разом ми створюємо не лише колекцію творів, а й теплу атмосферу, де кожен може відчути себе частиною чогось великого.</p>

        <p>Дякуємо, що ви з нами. Разом ми робимо світ фанфіків і український культурний простір більш яскравим та багатогранним!</p>
    </div>

    <div id="contact-us" class="info-section">
        <h2>Зв'язок з нами</h2>
        <ul class="normal-list">
            <li>Email: <a href="mailto: support@fanfics.com.ua">support@fanfics.com.ua</a></li>
            <li>Telegram канал: <a href="https://t.me/fanfics_ua">@fanfics_ua</a></li>
            <li>Telegram зв'язок: <a href="https://t.me/vladyslav_fokin">@vladyslav_fokin</a></li>
            <li>Зв'язатися через сайт: <a href="{{ route('ReportPage') }}">{{ route('ReportPage') }}</a></li>
        </ul>
    </div>

    <div id="site-map" class="info-section">
        <h2>Мапа сайту</h2>

        <ul class="normal-list">
            <li>
                <h3>Фанфіки</h3>
                <ul>
                    <li>
                        <a href="{{ route('FilterPage') }}">Знайти</a>
                        <ul>
                            <li><a href="{{ route('FilterPage', ['type_of_works' => 'fanfic']) }}">Фанфік</a></li>
                            <li><a href="{{ route('FilterPage', ['type_of_works' => 'original']) }}">Оригінальний твір</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('FanficCreatePage') }}">Створити</a></li>
                    <li><a href="{{ route('FanficListPage') }}">Мої фанфіки</a></li>
                    <li><a href="{{ route('SubscribesListPage') }}">Мої підписки</a></li>
                    <li><a href="{{ route('AccessFanficsListPage') }}">Доступ до фанфіків</a></li>
                    <li>
                        Запросити
                        <ul>
                            <li><a href="{{ route('FanficTransferPage') }}">Перенос з іншого сайту</a></li>
                            <li><a href="{{ route('FanficTranslatePage') }}">ШІ-переклад з іншої мови</a></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li>
                <h3>Фандоми</h3>
                <ul>
                    <li><a href="{{ route('FandomsCategoriesPage') }}">Усі категорії фандомів</a></li>
                    <li><a href="{{ route('TopFandomsPage') }}">Найпопулярніші фандоми</a></li>
                    <li><a href="{{ route('AddFandomPage') }}">Додати фандом</a></li>
                </ul>
            </li>

            <li>
                <h3>Профіль</h3>
                <ul>
                    <li><a href="{{ route('MyProfilePage') }}">Змінити автарку</a></li>
                </ul>
            </li>

            <li>
                <h3>Обратний зв'язок</h3>
                <ul>
                    <li><a href="{{ route('ReportPage') }}">Повідомити про помилку</a></li>
                </ul>
            </li>

            <li>
                <h3>Теґи</h3>
                <ul>
                    <li><a href="{{ route('AddTagPage') }}">Додати теґ</a></li>
                </ul>
            </li>

            <li>
                <h3>Персонади</h3>
                <ul>
                    <li><a href="{{ route('AddCharacterPage') }}">Додати персонажа</a></li>
                </ul>
            </li>

            <li>
                <h3>Інформаційні сторінки</h3>
                <ul>

                    <li>
                        <a href="{{ route('AboutSitePage') }}">Про сайт</a>
                        <ul>
                            <li> <a href="{{ route('AboutSitePage') }}#about-us">Про нас</a></li>
                            <li> <a href="{{ route('AboutSitePage') }}#contact-us">Зв'язок з нами</a></li>
                            <li> <a href="{{ route('AboutSitePage') }}#site-map">Мапа сайту</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="#">Політика</a>
                        <ul>
                            <li> <a href="#">Користувача угода</a></li>
                            <li> <a href="#">Політика конфіденційності</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="#">Правила</a>
                        <ul>
                            <li> <a href="#">Викладення творів</a></li>
                            <li> <a href="#">Вміст творів</a></li>
                            <li> <a href="#">Коментарі під розділами</a></li>
                        </ul>
                    </li>


                </ul>
            </li>

            <li>
                <h3>Авторизація</h3>
                <ul>

                    <li><a href="{{ route('RegistrationPage') }}">Реєстрація</a></li>
                    <li><a href="{{ route('LoginPage') }}">Увійти в акаунт</a></li>

                </ul>
            </li>


        </ul>
    </div>

@endsection
