@extends('layouts.main')

@section('title')
    {{ $title }} :: Фанфіки українською
@endsection


@section('content')

    <link rel="stylesheet" href="{{ asset('css/user-requests.css') }}">

    <h1>Додати новий персонажа на сайт</h1>

    <div class="request-description">

        <form method="post" enctype="multipart/form-data">
            @csrf
            <label class="required" for="character_name">Ім'я персонажу</label>
            <input type="text" name="character_name" id="character_name" required>
            @error('character_name')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="related_fandom">З яким фандомом пов'язаний</label>

            <input type="text" name="related_fandom" id="related_fandom" list="related_fandoms_list" required>
            <datalist id="related_fandoms_list">
                @foreach($fandoms as $fandom)
                    <option value="{{ $fandom->name }}">{{ $fandom->description }}</option>
                @endforeach
            </datalist>
            @error('related_fandoms_list')
                <p class="error">{{ $message }}</p>
            @enderror

            <input type="submit" value="Додати">


            @if(session('success'))
                <p class="alert-success">
                    {{ session('success') }}
                </p>
            @endif
        </form>

        <div class="description">
            <h2>Що трапиться?</h2>
            <p>На сайт відразу буде доданий новий персонаж зі вказаною вами назвою та описом. Ви відразу зможете його використовувати в
                <a href="{{ route('FanficCreateAction') }}">свойому фанфіку</a>, без попередньої модерації.</p>
        </div>

    </div>

@endsection
