@extends('layouts.main')

@section('title')
    {{ $title }} :: Фанфіки українською
@endsection


@section('content')

    <link rel="stylesheet" href="{{ asset('css/user-requests.css') }}">

    <h1>Додати новий теґ на сайт</h1>

    <div class="request-description">

        <form method="post" enctype="multipart/form-data">
            @csrf
            <label class="required" for="tag_name">Назва теґу</label>
            <input type="text" name="tag_name" id="tag_name" required>
            @error('tag_name')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="tag_description">Опис теґу</label>
            <textarea name="tag_description" id="tag_description" maxlength="500"></textarea>
            @error('tag_description')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="related_fandom">З яким фандомом пов'язаний</label>

            <input type="text" name="related_fandom" id="related_fandom" list="related_fandoms_list">
            <datalist id="related_fandoms_list">
                @foreach($fandoms as $fandom)
                    <option value="{{ $fandom->name }}">{{ $fandom->description }}</option>
                @endforeach
            </datalist>
            @error('related_fandoms_list')
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
            <h2>Що трапиться?</h2>
            <p>На сайт відразу буде доданий новий теґ з вказаною вами назвою та описом. Ви відразу зможете його використовувати для
                <a href="{{ route('FanficCreateAction') }}">створення свого фанфіку</a>, без попередньої модерації.</p>
        </div>

    </div>

    <div class="description">
        <h2>Що таке теґ?</h2>
        <p>Теґ – це ключове слово або фраза, яка використовується для опису змісту і допомагає фільтрувати контент за певною темою.</p>
    </div>

@endsection
