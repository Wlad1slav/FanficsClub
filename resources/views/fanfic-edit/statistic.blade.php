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

    <h1>Статистика по {{ $fanfic->title }}</h1>

    <table>
        <thead>
            <tr>
                <th>Рейтинг</th>
                <th>Антирейтинг</th>
                <th>Переглядів</th>
                <th>Підписників</th>
            </tr>
        </thead>
        <tr style="text-align: center;">
            <td>{{ $fanfic->likes->count() }}</td>
            <td>{{ $fanfic->dislikes->count() }}</td>
            <td>{{ $fanfic->views->count() }}</td>
            <td>{{ $fanfic->subscribes->count() }}</td>
        </tr>
    </table>


@endsection
