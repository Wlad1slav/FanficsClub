@extends('layouts.main')

@section('title')
    Фандом {{ $title }} :: Фанфіки українською
@endsection

@section('meta-description')
@endsection


@section('content')

<h1>Українські фанфіки по фандому <span>{{ $title }}</span></h1>

    @foreach($fanfics as $fanfic)
        @include('widgets.fanfic-container', ['fanfic' => $fanfic])
    @endforeach

@endsection
