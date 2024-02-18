<link rel="stylesheet" href="{{ asset('css/header.css') }}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<header>

    <!-- Перша лінія -->
    <div id="greeting-line">
        <a href="{{ route('HomePage') }}" class="logo">
            <img src="{{ asset('images/logo-header.webp') }}" alt="Лого сайту" class="logo no-select">
        </a>
        <div>
            <a href="{{ route('ReportPage') }}">Повідомити про помилку</a>
            @guest
                <a href="{{ route('RegistrationPage') }}">Реєстрація</a>
                <a href="{{ route('LoginPage') }}">Увійти</a>
            @endguest

            @auth
                <a href="{{ route('SubscribesListPage') }}">Підписки</a>
                <a href="{{ route('FanficListPage') }}">Мої твори</a>
                <a href="{{ route('MyProfilePage') }}">Мій профіль</a>
            @endauth
        </div>
    </div>

    <!-- Навігація по сайту -->

    <nav>

        <ul id="navigation">
            <!-- Основне меню -->
            @foreach($navigation as $key => $arr)
                <li><p>{{ $key }}</p>
                    <ul>
                        @foreach($arr as $el => $link)
                            <li><a href="{{ $link }}">{{ $el }}</a></li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>

    </nav>

</header>
