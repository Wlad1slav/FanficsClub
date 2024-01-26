@extends('layouts.main')

@section('title')
    {{ $title }} :: Фанфіки українською
@endsection

@section('meta-description')
@endsection


@section('content')

    <!-- Строка з найпопулярнішими ФАНДОМАМИ -->
    @include('widgets.popular-fandoms-list')

@endsection
