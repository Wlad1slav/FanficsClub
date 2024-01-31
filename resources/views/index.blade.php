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

    <div class="section">
        <!-- Строка з найпопулярнішими ФАНДОМАМИ -->
        @include('widgets.popular-fandoms-list', ['hasCta' => true, ''])
    </div>

    <!-- 5 останнє оновлених фанфіків -->
    <div class="section">
        <h2>Нещодавно оновлені</h2>
        @foreach($last_updated_fanfics as $fanfic)
            @include('widgets.fanfic-container', ['fanfic' => $fanfic])
        @endforeach
    </div>

    <!-- 5 останнє створенних фанфіків -->
    <div class="section">
        <h2>Нещодавно створені</h2>
        @foreach($last_created_fanfics as $fanfic)
            @include('widgets.fanfic-container', ['fanfic' => $fanfic])
        @endforeach
    </div>

@endsection
