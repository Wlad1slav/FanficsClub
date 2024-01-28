<!--

    Маршрут:
    uri - '/'; назва - 'HomePage'

    Контролер:
    class:HomeController->index()

    Передаються змінні:
    string $title; string $metaDescription
    Laravel колекція $fandoms - 5 найпопулярніших фандомів, фандоми в яких є найбільше фанфіків

    Головна сторінка сайту.

    Складається з:

    Віджету popular-fandoms-list, що виводить контейнери з інформацією про певні фандоми.
    Будуть виведені фандоми з колекції Laravel $fandoms

-->

@extends('layouts.main')

@section('title')
    {{ $title }} :: Фанфіки українською
@endsection

@section('meta-description')
@endsection


@section('content')

    <h1>Фанфіки Українською Мовою</h1>

    <!-- Строка з найпопулярнішими ФАНДОМАМИ -->
    @include('widgets.popular-fandoms-list', ['hasCta' => true, ''])

@endsection
