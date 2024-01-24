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

{{--    Друга лінія--}}
    <div id="navigation">
        <div>
            @foreach($navigation as $key => $arr)
                <p>{{ $key }}</p>
            @endforeach
        </div>
    </div>
</header>
