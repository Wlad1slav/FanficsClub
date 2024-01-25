<link rel="stylesheet" href="{{ asset('css/header.css') }}">

@php
global $navigation;
@endphp

<header>

    {{--        Перша лінія--}}
    <div id="greeting-line">
        <h1 class="no-select">Фанфіки українською</h1>
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
