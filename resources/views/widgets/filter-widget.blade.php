<link rel="stylesheet" href="{{ asset('css/filter.css') }}">
<!--

Параметри:
    sort_by - По якому полю сортирувати фанфіки
    fandoms_selected - Перелік фандомів, до яких повинні належати фанфіки
    characters - Персонажі, які повинні бути присутні в фанфіках
    age_rating - Вікові рейтинги до якого повинні належати фанфіки
    category - Категорії до яких повинні належати фанфіки
    tags_selected - Тегі, які повинні міститися в фанфіку
-->

<div id="filter">

    <form action="#" method="get">

        <input type="submit" value="Пошук">

        <!-- Вибір, по якої колонці будуть відсортировані фанфіки -->
        <label for="sort_by">
            Сортувати по
        </label>
        <select name="sort_by" id="sort_by" >
            <option value="updated_at" @selected(($_GET['sort_by'] ?? '') == 'updated_at')>Даті оновленя</option>
            <option value="created_at" @selected(($_GET['sort_by'] ?? '') == 'created_at')>Даті створеня</option>
            <option value="words_amount" @selected(($_GET['sort_by'] ?? '') == 'words_amount')>Словам</option>
            <option value="rating" @selected(($_GET['sort_by'] ?? '') == 'rating')>Рейтингу</option>
            <option value="anti_rating" @selected(($_GET['sort_by'] ?? '') == 'anti_rating')>Анті-Рейтингу</option>
            <option value="views" @selected(($_GET['sort_by'] ?? '') == 'views')>Переглядам</option>
        </select>

        <!-- Віджет для виборів антрибутів
         Вибірається фандоми, по яким буде відбуватися пошук -->
        @include('widgets.select-attributes', [
            'attrs' => $fandoms,
            'heading' => 'Фандоми',
            'textarea_selected_id_name' => 'fandoms_selected',
            'notify' => 'Якщо ви бажаєте шукати оригінальні роботи, то можете залишити поле пустим.',
            'placeholder' => 'Виберіть фандом',
            'default_content' => $_GET['fandoms_selected'] ?? '',
        ])

        @include('widgets.characters-select') <!-- Віджет з вибором персонажів -->

        <!-- Вибір, з якими СТАТУСАМИ будуть показуватися фанфіки -->
        <!--<div class="checkboxes-container black">
            <h2>Статус</h2>
            <label for="status-1">
                <input type="checkbox" name="status[]" id="status-1" value="in_progress">
                <span>В процесі</span>
            </label>
            <label for="status-2">
                <input type="checkbox" name="status[]" id="status-2" value="is_completed">
                <span>Завершено</span>
            </label>
            <label for="status-3">
                <input type="checkbox" name="status[]" id="status-3" value="is_frozen">
                <span>Заморожено</span>
            </label>
        </div>-->

        <div class="checkboxes-container">
            <!-- Вибір, з якими ВІКОВИМИ РЕЙТИНГАМИ будуть показуватися фанфіки -->

            <h2>Віковий рейтинг</h2>
            @foreach($ageRatings as $rating)

                <label for="age_rating-{{ $rating->id }}">
                    <input type="checkbox"
                           name="age_rating[]"
                           id="age_rating-{{ $rating->id }}"
                           value="{{ $rating->id }}"
                           @checked(in_array($rating->id, $_GET['age_rating'] ?? []))>
                    <span style="background: rgb({{ $rating->rgb_color }})">{{ $rating->name }}</span>
                </label>

                <p class="notify" style="width: 50%;">{{ $rating->description }}</p>
            @endforeach
        </div>

        <div class="checkboxes-container">
            <!-- Вибір, з якими КАТЕГОРІЯМИ будуть показуватися фанфіки -->

            <h2>Категорія</h2>
            @foreach($categories as $category)

                <label for="category-{{ $category->id }}">
                    <input type="checkbox"
                           name="category[]"
                           id="category-{{ $category->id }}"
                           value="{{ $category->id }}"
                           @checked(in_array($category->id, $_GET['category'] ?? []))>

                    <span style="background: rgb({{ $category->rgb_color }})">{{ $category->name }}</span>
                </label>

                <p class="notify" style="width: 50%;">{{ $category->description }}</p>
            @endforeach
        </div>

        @include('widgets.select-attributes', [
            'attrs' => $tags,
            'heading' => 'Теґи',
            'textarea_selected_id_name' => 'tags_selected',
            'placeholder' => 'Виберіть теґ',
            'rows' => 5,
            'default_content' => $_GET['tags_selected'] ?? '',
        ])

        <input type="submit" value="Пошук">

    </form>

</div>
