@extends('fanfic-edit.layout')


@section('content')

    <link rel="stylesheet" href="{{ asset('css/chapter/list-edit.css') }}">

    <h1>Розділи твору</h1>

    @include('widgets.button', [
            'title' => 'Створити',
            'url' => route('ChapterCreateAction', ['ff_slug' => $fanfic->slug]),
        ])

    @if($chapters !== null)
        <form method="post">
            @csrf

            <input type="submit" value="Зберегти порядок розділів">

            <!-- Форма передає усі розділи з їх новим номером
             Ключем кожного розділу є ключ, елементом - номер -->
            @foreach($chapters as $chap_num => $chapter)
                <div class="chapter @if($chapter->is_draft) draft @endif">
                    <input type="number"
                           name="chapter_num[{{ $chapter->id }}]"
                           value="{{ $chap_num+1 }}"
                           min="1"
                           max="{{ $chapters->count() }}">
                    <a class="title"
                       href="{{ route('ChapterEditPage', ['ff_slug' => $fanfic->slug, 'chapter_slug' => $chapter->slug]) }}">{{ $chapter->title }}</a>
                    <a href="{{ route('FanficPage', ['ff_slug' => $fanfic->slug, 'chapter_slug' => $chapter->slug]) }}">Перейти</a>
                    <a href="{{ route('ChapterDeleteAction', ['ff_slug' => $fanfic->slug, 'chapter_slug' => $chapter->slug]) }}" class="delete">X</a>
                </div>
            @endforeach

        </form>

        @error('chapter_num')
            <p class="error">{{ $message }}</p>
        @enderror
    @endif

@endsection
