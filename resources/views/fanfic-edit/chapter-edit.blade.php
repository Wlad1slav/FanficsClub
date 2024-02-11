@extends('fanfic-edit.layout')

<!--

Параметри:
    - chapter_title - назва розділу
    - chapter_content - контент розділу
    - notify - попередження перед розділом
    - notes - нотатки, після глави
    - is_draft - чи зберегти розділ, як чорнетку

-->


@section('content')

    <link rel="stylesheet" href="{{ asset('css/fanfic/create.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chapter/create.css') }}">

    <h1>Редагувати розділ</h1>

    <form method="post">
        @csrf

        <label for="chapter_title" class="required">
            Заголовок розділу
        </label>
        <input type="text" name="chapter_title" id="chapter_title" required value="{{ $chapter->title }}">

        <label for="chapter_content" class="required">
            Контент розділу
        </label>
        <textarea name="chapter_content" id="chapter_content" required>{{ $chapter->content }}</textarea>

        <label for="notify">
            Попередження перед розділом
        </label>
        <textarea name="notify" id="notify">{{ $chapter->additional_descriptions['notify'] }}</textarea>

        <label for="notes">
            Нотатки після розділу
        </label>
        <textarea name="notes" id="notes">{{ $chapter->additional_descriptions['notes'] }}</textarea>

        <label for="is_draft">
            <input type="checkbox" name="is_draft" value="1" id="is_draft" @checked($chapter->is_draft)>
            Чертнетка
        </label>

        <input type="submit" value="Зберегти">

    </form>

    @include('widgets.button', [
            'title' => 'Переглянути розділ',
            'url' => route('FanficPage', ['ff_slug' => $fanfic->slug, 'chapter_slug' => $chapter->slug]),
        ])

@endsection
