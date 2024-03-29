@extends('layouts.main')

@section('title')
    {{ $title }} :: Фанфіки українською
@endsection


@section('content')

    <link rel="stylesheet" href="{{ asset('css/user-requests.css') }}">

    <h1>Запросити ШІ-переклад фанфіку на українську мову</h1>

    <div class="request-description">

        <form method="post">
            @csrf
            <label for="link">Посилання на фанфік</label>
            <input type="url" name="link" id="link" required>
            <input type="submit" value="Відправити">
            <p class="notify">Перш за все розглядаються фанфіки з <a href="https://archiveofourown.org/">AO3</a></p>

            @error('link')
                <p class="error">{{ $message }}</p>
            @enderror

            @if(session('success'))
                <p class="alert-success">
                    {{ session('success') }}
                </p>
            @endif
        </form>

        <div class="description">
            <h2>Що трапиться?</h2>
            <p>Ви відправите запит на ШІ-переклад фанфіку роботом. Переклад займе певний час, по завершенню, перекладений фанфік буде доданий в ваш профіль (<a
                    href="{{ route('FanficListPage') }}">Мій профіль>Роботи</a>). По завершенню ШІ-перекладу, ви будете повідомленні.</p>
        </div>

    </div>

@endsection
