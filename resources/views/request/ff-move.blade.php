@extends('layouts.main')

@section('title')
    {{ $title }} :: Фанфіки українською
@endsection


@section('content')

    <link rel="stylesheet" href="{{ asset('css/user-requests.css') }}">

    <h1>Запросити перенесення українського фанфіку з іншого сайту</h1>

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
            <p>Ви відправите запит на перенесення українського фанфіку з іншого сайту на наш. Перенесення займе певний час, по завершенню перенесений фанфік буде доданий на сайт під авторством людини, що створила оригінал. По завершенню перенесення, ви будете повідомленні.</p>
        </div>

    </div>

@endsection
