@extends('layouts.main')

@section('title')
    {{ $title }} :: Фанфіки українською
@endsection

@section('content')

    <style>
        h2 {
            margin-bottom: 0;
        }
    </style>

    <h1>Завантажити фанфік {{ $fanfic->title }}</h1>

    <h2>PDF</h2>
    <a href="{{ route('DownloadFanficAction', ['ff_slug' => $fanfic->slug, 'format' => 'pdf']) }}">
        Завантажити
    </a>

    <h2>FB2</h2>
    <a href="{{ route('DownloadFanficAction', ['ff_slug' => $fanfic->slug, 'format' => 'fb2']) }}">
        Завантажити
    </a>

{{--    <h2>TXT</h2>--}}
{{--    <a href="#">Завантажити</a>--}}

    @include('widgets.button', [
        'title' => 'На сторінку фанфіку',
        'url' => route('FanficPage', $fanfic->slug)
    ])


@endsection
