<link rel="stylesheet" href="{{ asset('css/header.css') }}">

<header>

    <!-- Перша лінія -->
    <div id="greeting-line">
        <a href="{{ route('HomePage') }}" class="logo">
            <img src="{{ asset('images/logo-header.webp') }}" alt="Лого сайту" class="logo no-select">
        </a>
        <div>
            <a href="#">Page</a>
            <a href="#">Page</a>
            <a href="#">Page</a>
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
