@extends('layouts.main')

@section('title')
    {{ $title }} :: Фанфіки українською
@endsection

@section('meta-description')
@endsection


@section('content')

    <h1>Фандоми з категорії <span>{{ $title }}</span></h1>

    <a href="{{ route('FandomsCategoriesPage') }}">Повернутися на сторінку з усіма категоріями</a>

    <!-- Абетка з посиланнями на контейнер з фандомами,
     що починаються на певну літеру -->
    <div class="alphabet" style="display: flex;">
        @foreach($fandoms as $letter => $fandom)
            @include('widgets.button', [
                    'title' => $letter,
                    'url' => "#$letter",
                    'styles' => 'mrg-left-0 mrg-right'
                ])
        @endforeach
    </div>

    <div class="containers">

        <!-- $fandoms_ - масив усіх фандомів, що починаються на певу літеру -->
        @foreach($fandoms as $letter => $fandoms_)
            <div id="{{ $letter }}" class="fandoms-container">
                <h2>{{ $letter }}</h2>
                @foreach($fandoms_ as $fandom)
                    <p><a class="fandom-link" href="{{ route('FilterPage', ['fandoms_selected' => $fandom->name, 'type_of_works' => 'fanfic']) }}">
                            {{ $fandom['name'] }}</a> ({{ $fandom['fictions_amount'] }})
                    </p>
                @endforeach
            </div>
        @endforeach

    </div>

@endsection
