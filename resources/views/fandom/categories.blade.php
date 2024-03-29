@extends('layouts.main')

@section('title')
    Фандоми :: Фанфіки українською
@endsection

@section('meta-description')
@endsection


@section('content')

    <link rel="stylesheet" href="{{ asset('css/fandom/categories.css') }}">

    <h1>Фандоми</h1>

    <!-- Строка з найпопулярнішими ФАНДОМАМИ -->
    @include('widgets.popular-fandoms-list', ['hasTitle' => false])

    <div class="fandom-categories-list">
        <!-- Цикл, що проходиться по усім категоріям і
        виводить контейнери з фандомами по цим категоріям -->
        @foreach($fandomsOrganisedByCategories as $categoryName => $categoryContent)

            <div class="fandom-category-container">
                <h2>{{ $categoryName }}</h2>
                @foreach($categoryContent['fandoms'] as $fandom)
                    <p>
                        <a class="fandom-link" href="{{ route('FilterPage', ['fandoms_selected' => $fandom->name, 'type_of_works' => 'fanfic']) }}">
                            {{ $fandom->name }}</a>
                        ({{ $fandom['fictions_amount'] }})
                    </p>
                @endforeach

                @include('widgets.button', [
                    'title' => 'Більше...',
                    'url' => route('CertainCategoryPage', ['category_slug' => $categoryContent['slug']]),
                    'styles' => 'a right bottom'
                ])

{{--                @include('widgets.button', [--}}
{{--                    'title' => 'Більше...',--}}
{{--                    'url' => route('CertainCategoryPage', ['category_slug' => $categoryContent['slug']]),--}}
{{--                    'styles' => 'mobile'--}}
{{--                ])--}}
            </div>

        @endforeach
    </div>

@endsection
