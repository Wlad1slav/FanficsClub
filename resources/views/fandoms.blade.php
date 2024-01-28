@extends('layouts.main')

@section('title')
    Фандоми :: Фанфіки українською
@endsection

@section('meta-description')
@endsection


@section('content')

    <h1>Фандоми</h1>

    <!-- Строка з найпопулярнішими ФАНДОМАМИ -->
    @include('widgets.popular-fandoms-list', ['hasTitle' => false])

    <div class="fandom-categories-list">
        <!-- Цикл, що проходиться по усім категоріям і
        виводить контейнери з фандомами по цим категоріям -->
        @foreach($fandomsOrganisedByCategories as $categoryName => $categoryContent)

            <div class="fandom-category-container">
                <h2>{{ $categoryName }}</h2>
                @foreach(json_decode($categoryContent['fandoms'], true) as $fandom)
                    <p>
                        <a href="{{ route('CertainFandomPage', ['slug' => $fandom['slug']]) }}">{{ $fandom['name'] }}</a>
                        ({{ $fandom['fictions_amount'] }})
                    </p>
                @endforeach

                @include('widgets.button', [
                    'title' => 'Більше...',
                    'url' => '#',
                    'styles' => 'a right bottom'
                ])
            </div>

        @endforeach
    </div>

@endsection
