@extends('fanfic-edit.layout')

<!--



-->


@section('content')

    <link rel="stylesheet" href="{{ asset('css/fanfic/create.css') }}">

    <h1>Редагувати твір</h1>

    <form method="post">
        @csrf

        <label for="is_draft">
            <input type="checkbox" name="is_draft" value="1" id="is_draft" @checked($fanfic->is_draft)>
            Скритий твір
        </label>

        <!-- Назва фанфіка -->
        <div class="field">

            <label for="ff_name" class="main required">Назва</label>

            <div>
                <input type="text"
                       value="{{ $fanfic->title }}"
                       name="ff_name"
                       id="ff_name"
                       required>

                @error('ff_name')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <!-- Тип фанфіка (ориганільний твір/переклад) -->
        <div class="field">

            <label class="main required">Авторство</label>

            <div>
                <label for="type_of_work-original-work">
                    <input type="radio"
                           name="type_of_work"
                           value="0"
                           id="type_of_work-original-work"
                           @checked(!$fanfic->is_translate)
                           required> <!-- is_translate = 0 (false) -->
                    Я автор
                </label>
                <label for="type_of_work-translate">
                    <!-- При виборі "Переклад з іншої мови" з'являється
                    поля для вводу ім'я автора і посилання на оригінал -->
                    <input type="radio"
                           value="1"
                           name="type_of_work"
                           id="type_of_work-translate"
                           @checked($fanfic->is_translate)
                           required> <!-- is_translate = 1 (true) -->
                    Це переклад з іншої мови
                </label>

                <label for="anonymity">
                    <!-- Чи хоче користувач викласти свій фанфік анонімно -->
                    <input type="checkbox" name="anonymity" id="anonymity" value="1" @checked($fanfic->is_anonymous)>
                    Анонімний твір
                </label>

                @error('type_of_work')
                <p class="error">{{ $message }}</p>
                @enderror

                @error('anonymity')
                <p class="error">{{ $message }}</p>
                @enderror


            </div>

        </div>

        <!-- Автор оригінала (з'являється, якщо тип фанфіка - переклад) -->
        <div class="field no-display" id="with-translate-1">

            <label for="ff_original_author" class="main required">Автор оригінала</label>

            <div>
                <input type="text" name="ff_original_author" id="ff_original_author">

                @error('ff_original_author')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <!-- Посилання на оригінал (з'являється, якщо тип фанфіка - переклад) -->
        <div class="field no-display" id="with-translate-2">

            <label for="ff_original_link" class="main required">Посилання на оригінал</label>

            <div>
                <input type="text" name="ff_original_link" id="ff_original_link"
                       placeholder="https://archiveofourown.org/works/0000000">

                @error('ff_original_link')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <!-- Статус твору - чи закінчений він, чи заморожений, чи ще в процесі? -->
        <div class="field">

            <label class="main required">Статус твору</label>

            <div>

                <label for="in_process">
                    <input type="radio"
                           name="status"
                           id="in_process"
                           value="in_process" checked required>
                    В процесі
                </label>

                <label for="frozen">
                    <input type="radio"
                           name="status"
                           id="frozen"
                           value="frozen"
                           @checked($fanfic->is_frozen) required>
                    Заморожений
                </label>

                <label for="completed">
                    <input type="radio"
                           name="status"
                           id="completed"
                           value="completed"
                           @checked($fanfic->is_completed) required>
                    Закінчений
                </label>

                @error('status')
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
                    'default_content' => implode(',', $fandoms_selected) . ', '
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
                @include('widgets.characters-select', ['has_label' => false, 'default_values' => $characters_selected]) <!-- Віджет з вибором персонажів -->

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
                               value="{{ $rating->id }}"
                               @checked($fanfic->age_rating->id == $rating->id) required>
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
                               value="{{ $category->id }}"
                               @checked($fanfic->category->id == $category->id) required>

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
                    'default_content' => count($tags_selected) > 0 ? implode(',', $tags_selected) . ', ' : ''
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
                <textarea name="ff_description" id="ff_description" style="height: 150px;" maxlength="550">{{ $fanfic->description }}</textarea>
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
                <textarea name="ff_notes" id="ff_notes" style="height: 150px;" maxlength="550">{{ $fanfic->additional_descriptions }}</textarea>
                <p class="notify">550 символів</p>

                @error('ff_notes')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <input type="submit" value="Зберегти">

        <script src="{{ asset('js/fanfic-create.js') }}"></script>

    </form>

    @include('widgets.button', [
            'title' => 'Переглянути твір',
            'url' => route('FanficPage', ['ff_slug' => $fanfic->slug]),
        ])

@endsection
