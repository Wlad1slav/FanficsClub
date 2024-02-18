@extends('layouts.main')

@section('title')
    {{ $title }} :: Фанфіки українською
@endsection

@section('meta-description')
@endsection


@section('content')

    <link rel="stylesheet" href="{{ asset('css/fandom/top.css') }}">

    <h1>Топ 50 фандомів сайту</h1>

    <a href="{{ route('FandomsCategoriesPage') }}">Усі фандоми</a>

    <div class="top">
        @foreach($fandoms_50 as $fandom)
            <p>
                <span>[{{ $fandom->fictions_amount }}]</span>
                <a href="{{ route('FilterPage', ['fandoms-selected' => $fandom->name]) }}">{{ $fandom->name }}</a>
            </p>
        @endforeach
    </div>

@endsection
