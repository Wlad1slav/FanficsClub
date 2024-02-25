@extends('layouts.main')

@section('title')
    {{ $title }} :: Фанфіки українською
@endsection


@section('content')

    <link rel="stylesheet" href="{{ asset('css/user-requests.css') }}">

    <h1>Повідомити про проблему на сайті</h1>

    <div class="request-description">

        <form method="post" enctype="multipart/form-data">
            @csrf

            <label class="required" for="report_theme">Тема повідомлення</label>
            <input type="text" name="report_theme" id="report_theme" required max="255">
            @error('report_theme')
                <p class="error">{{ $message }}</p>
            @enderror

            <label class="required" for="report_message">Повідомлення</label>
            <textarea name="report_message" id="report_message"></textarea>
            @error('report_message')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="screenshot">Скріншот</label>
            <input type="file"
                   name="screenshot"
                   id="screenshot"
                   accept=".jpg, .jpeg, .png, .webp">
            @error('screenshot')
                <p class="error">{{ $message }}</p>
            @enderror

            <input type="submit" value="Відправити">


            @if(session('success'))
                <p class="alert-success">
                    {{ session('success') }}
                </p>
            @endif
        </form>

        <div class="description">
            <h2>Дякуємо!</h2>
            <p>Дякуємо вам за повідомлення адміністрації про помилку на сайті. У випадку, якщо в вас є питання, то ви можете задати його через цю форму чи напряму зв'язатися з нами через
                <a href="{{ route('AboutSitePage') }}">офіційні контакти</a>.</p>
        </div>

    </div>

@endsection
