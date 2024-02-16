@extends('layouts.main')

@section('title')
    {{ $title }} :: Фанфіки українською
@endsection


@section('content')

    <link rel="stylesheet" href="{{ asset('css/user-requests.css') }}">

    <h1>Додати новий фандом на сайт</h1>

    <div class="request-description">

        <form method="post" enctype="multipart/form-data">
            @csrf
            <label class="required" for="fandom_name">Назва фандому</label>
            <input type="text" name="fandom_name" id="fandom_name" required>
            @error('fandom_name')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="fandom_description">Опис фандому</label>
            <textarea name="fandom_description" id="fandom_description" maxlength="500"></textarea>
            @error('fandom_description')
                <p class="error">{{ $message }}</p>
            @enderror

            <label class="required" for="fandom_category">Категорія фандому</label>
            <select name="fandom_category" id="fandom_category">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('fandom_category')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="related_mediagiant">Вибрати медіагіганта, з яким пов'язаний фандом, якщо такий є</label>

            <input type="text" name="related_mediagiant" id="related_mediagiant" list="related_mediagiants_list">
            <datalist id="related_mediagiants_list">
                @foreach($media_giants as $giant)
                    <option value="{{ $giant->name }}">{{ $giant->description }}</option>
                @endforeach
            </datalist>
            @error('related_mediagiant')
                <p class="error">{{ $message }}</p>
            @enderror
            <p class="notify" style="width: 100%; text-align: right;"><a href="{{ route('CertainCategoryPage', 'mediahihanty') }}">Усі медіагіганти</a></p>

            <label for="fandom_image">Прев'ю фандому</label>
            <input type="file"
                   name="fandom_image"
                   id="fandom_image"
                   accept=".jpg, .jpeg, .png, .webp">
            @error('fandom_image')
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
            <p>На сайт відразу буде доданий новий
                <a href="{{ route('FandomsCategoriesPage') }}">фандом</a> з вказаною вами назвою, категорією, описом і прев'ю. Ви відразу зможете його використовувати для
                <a href="{{ route('FanficCreateAction') }}">створення свого фанфіку</a>, без попередньої модерації.</p>
        </div>

    </div>

    <div class="description">
        <h2>Що таке фандом?</h2>
        <p>Фандом – це спільнота фанатів, які поділяють спільний ентузіазм і захоплення певним художнім твором, серіалом, фільмом, книгою, персонажем або будь-яким іншим аспектом популярної культури.</p>
        <p>На нашому сайті члени фандому можуть спокійно створювати та просувати власний контент – фанфіки українською мовою, що будуть належати певному фандому.</p>
    </div>

@endsection
