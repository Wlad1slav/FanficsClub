@extends('profile.layout')

<!--

Поля:
    type_of_work (radio) - Робота переклад чи авторська робота
    anonymity (checkbox) - Чи буде робота анонімною
    originality_of_work (radio) - Оригінальність роботи - фанфік чи оригінальний твір
    ff_name (text) - Назва фанфіка
    ff_original_author (text) - Ім'я автора оригінала
    ff_original_link (text) - Посилання на оригінал
    fandoms_selected (textarea) - Фандоми, до яких відноситься фанфік
    characters (textarea) - Персонажі, які присутні в фандомі
    age_rating (radio) - Віковий рейтинг фанфіка
    category (radio) - Категорія фанфіка
    tags_selected (textarea) - Теґи фанфіка
    ff_description (textarea) - Опис фанфіка
    ff_notes (textarea) - Примітки
-->

@section('content')

    <link rel="stylesheet" href="{{ asset('css/fanfic/create.css') }}">

    <h1>Створити фанфік</h1>

    <form method="post" enctype="multipart/form-data">
        @csrf

        <!-- Тип фанфіка (ориганільний твір/переклад) -->
        <div class="field">

            <label class="main required">Авторство</label>

            <div>
                <label for="type_of_work-original-work">
                    <input type="radio"
                           name="type_of_work"
                           value="0"
                           id="type_of_work-original-work"
                           checked required> <!-- is_translate = 0 (false) -->
                    Я автор
                </label>
                <label for="type_of_work-translate">
                    <!-- При виборі "Переклад з іншої мови" з'являється
                    поля для вводу ім'я автора і посилання на оригінал -->
                    <input type="radio"
                           value="1"
                           name="type_of_work"
                           id="type_of_work-translate"
                           required> <!-- is_translate = 1 (true) -->
                    Це переклад з іншої мови
                </label>

                <label for="anonymity">
                    <!-- Чи хоче користувач викласти свій фанфік анонімно -->
                    <input type="checkbox" name="anonymity" value="1" id="anonymity">
                    Викласти твір анонімно
                </label>

                @error('type_of_work')
                    <p class="error">{{ $message }}</p>
                @enderror

                @error('anonymity')
                    <p class="error">{{ $message }}</p>
                @enderror


            </div>

        </div>

        <!-- Оригінальність роботи (чи це оригінальна робота чи то фанфік по певному фандому) -->
        <div class="field">

            <label class="main required">Оригінальність</label>

            <div>
                <label for="originality_of_work-original">
                    <input type="radio"
                           name="originality_of_work"
                           value="1"
                           id="originality_of_work-original"
                           required> <!-- is_original = 1 (true) -->
                    Оригінальний твір
                </label>
                <label for="originality_of_work-fanfic">
                    <!-- При виборі "Фанфік по фандому" з'являється
                    поле для вводу фандомів, до яких належить фанфік -->
                    <input type="radio"
                           name="originality_of_work"
                           value="0"
                           id="originality_of_work-fanfic"
                           checked required> <!-- is_original = 0 (false) -->
                    Фанфік по фандому
                </label>

                @error('originality_of_work')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <!-- Назва фанфіка -->
        <div class="field">

            <label for="ff_name" class="main required">Назва</label>

            <div>
                <input type="text" name="ff_name" id="ff_name" required value="{{ old('ff_name') }}">

                @error('ff_name')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

        </div>

        @if(\Illuminate\Support\Facades\Auth::user()->fanfictions->count() > 0)
            <!-- Приквел фанфіка -->
            <div class="field">

                <label for="prequel" class="main required">Приквел</label>

                <div>
                    <select name="prequel" id="prequel">
                        <option value="-1">Немає</option>
                        @foreach(\Illuminate\Support\Facades\Auth::user()->fanfictions as $fanfic)
                            <option value="{{ $fanfic->id }}" @selected(old('prequel') == $fanfic->id)>{{ $fanfic->title }}</option>
                        @endforeach
                    </select>

                    @error('prequel')
                    <p class="error">{{ $message }}</p>
                    @enderror

                    <p class="notify">Ви можете указати попередній фанфік серії, якщо такий є</p>
                </div>

            </div>
        @endif

        <!-- Автор оригінала (з'являється, якщо тип фанфіка - переклад) -->
        <div class="field no-display" id="with-translate-1">

            <label for="ff_original_author" class="main required">Автор оригінала</label>

            <div>
                <input type="text" name="ff_original_author" id="ff_original_author" value="{{ old('ff_original_author') }}">

                @error('ff_original_author')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <!-- Посилання на оригінал (з'являється, якщо тип фанфіка - переклад) -->
        <div class="field no-display" id="with-translate-2">

            <label for="ff_original_link" class="main required">Посилання на оригінал</label>

            <div>
                <input type="text" name="ff_original_link" id="ff_original_link" value="{{ old('ff_original_link') }}"
                       placeholder="https://archiveofourown.org/works/0000000">

                @error('ff_original_link')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <!-- Віджет для вибору антрибутів
        Вибірається фандоми, до яких буде відноситися фанфік -->
        <div class="field" id="if-fanfic">
            <label class="main required">Фандоми</label>
            <div>
                @include('widgets.select-attributes', [
                    'attrs' => $fandoms,
                    'textarea_selected_id_name' => 'fandoms_selected',
                    'placeholder' => 'Виберіть фандом',
                    'default_content' => old('fandoms_selected')
                ])

                @error('fandoms_selected')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Віджет для вибору персонажів
         Вибіраються персонажі і пейренги, які будуть присутні у фанфіку -->
        <div class="field">
            <label class="main">Персонажі</label>
            <div>
                @include('widgets.characters-select', ['has_label' => false]) <!-- Віджет з вибором персонажів -->

                <textarea name="characters_original"
                          id="characters-original"
                          class="no-display"
                          placeholder="Іван Мельник, Тарас Данилюк/Наталя Кринська...">{{ old('characters_original') }}</textarea>
{{--                    <p class="notify">Введіть імена оригінальних персонажів через кому</p>--}}


                @error('characters')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>
        </div>



        <!-- Вибір вікового рейтингу, до якої відноситься фанфік -->
        <div class="field">
            <label class="main required">Віковий рейтинг</label>
            <div>
                @foreach($ageRatings as $rating)

                    <label for="age_rating-{{ $rating->id }}">
                        <input type="radio"
                               name="age_rating"
                               id="age_rating-{{ $rating->id }}"
                               value="{{ $rating->id }}" required
                            @checked(old('age_rating') == $rating->id)>
                        <span style="background: rgb({{ $rating->rgb_color }})"
                              class="radio-color">{{ $rating->name }}</span>
                    </label>

                    <p class="notify">{{ $rating->description }}</p>
                @endforeach

                @error('age_rating')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Вибір категорії, до якої відноситься фанфік -->
        <div class="field">
            <label class="main required">Категорія</label>

            <div>
                @foreach($categories as $category)

                    <label for="category-{{ $category->id }}">
                        <input type="radio"
                               name="category"
                               id="category-{{ $category->id }}"
                               value="{{ $category->id }}" required
                            @checked(old('category') == $category->id)>

                        <span style="background: rgb({{ $category->rgb_color }})"
                              class="radio-color">{{ $category->name }}</span>
                    </label>

                    <p class="notify">{{ $category->description }}</p>
                @endforeach

                @error('category')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Віджет для вибору антрибутів
         Вибірається теги для фанфіка -->
        <div class="field" id="if-fanfic">
            <label class="main">Теґи</label>
            <div>
                @include('widgets.select-attributes', [
                    'attrs' => $tags,
                    'textarea_selected_id_name' => 'tags_selected',
                    'placeholder' => 'Виберіть теґ',
                    'rows' => 5,
                    'default_content' => old('tags_selected')
                ])

                @error('tags_selected')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Опис фанфіка -->
        <div class="field">

            <label for="ff_description" class="main">Опис</label>

            <div>
                <textarea name="ff_description"
                          id="ff_description"
                          style="height: 150px;"
                          maxlength="550">{{ old('ff_description') }}</textarea>
                <p class="notify">550 символів</p>

                @error('ff_description')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <!-- Нотатки -->
        <div class="field">

            <label for="ff_notes" class="main">Нотатки</label>

            <div>
                <textarea name="ff_notes" id="ff_notes" style="height: 150px;" maxlength="550">{{ old('ff_notes') }}</textarea>
                <p class="notify">550 символів</p>

                @error('ff_notes')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <input type="submit" value="Створити">


        <script src="{{ asset('js/fanfic-create.js') }}"></script>

    </form>

@endsection
