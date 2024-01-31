@extends('layouts.main')

@section('title')
    {{ $title }} :: Фанфіки українською
@endsection


@section('content')

    <h1>Колекція <span>{{ $collection->title }}</span></h1>

    <div class="section" style="display: flex; justify-content: space-between; align-items: flex-end;">
        @include('widgets.button', [
            'title' => 'Зберегти',
            'url' => '#',
        ])
        <p>Автор колекції <a href="#">{{ $collection->author->name }}</a></p>
    </div>

    @include('widgets.description', ['text' => $collection->description])

    @php $fanfics = $collection->getFandomsAttribute(1); @endphp

    @foreach($fanfics as $fanfic)
        @include('widgets.fanfic-container', ['fanfic' => $fanfic])
    @endforeach

    {{ $fanfics->links('widgets.pagination') }}

@endsection
