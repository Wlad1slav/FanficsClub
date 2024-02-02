<link rel="stylesheet" href="{{ asset('css/filter.css') }}">

<div id="filter">

    <form action="#" method="get">

        <input type="submit" value="Пошук">

        <!-- Вибір, по якої колонці будуть відсортировані фанфіки -->
        <label for="sort-by">
            Сортувати по
        </label>
        <select name="sort-by" id="sort-by">
            <option value="words_amount">Словам</option>
            <option value="rating">Рейтингу</option>
            <option value="anti_rating">Анті-Рейтингу</option>
            <option value="views">Переглядам</option>
        </select>

        <!-- Віджет для виборів антрибутів
         Вибірається фандоми, по яким буде відбуватися пошук -->
        @include('widgets.select-attributes', [
            'attrs' => $fandoms,
            'heading' => 'Фандоми',
            'textarea_selected_id_name' => 'fandoms-selected',
            'notify' => 'Якщо ви бажаєте шукати оригінальні роботи, то можете залишити поле пустим.',
            'placeholder' => 'Виберіть фандом',
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

                <label for="age-rating-{{ $rating->id }}">
                    <input type="checkbox" name="age-rating[]" id="age-rating-{{ $rating->id }}" value="{{ $rating->id }}">
                    <span style="background: rgb({{ $rating->rgb_color }})">{{ $rating->name }}</span>
                </label>

                <p class="notify">{{ $rating->description }}</p>
            @endforeach
        </div>

        <div class="checkboxes-container">
            <!-- Вибір, з якими КАТЕГОРІЯМИ будуть показуватися фанфіки -->

            <h2>Категорія</h2>
            @foreach($categories as $category)

                <label for="category-{{ $category->id }}">
                    <input type="checkbox" name="category[]" id="category-{{ $category->id }}" value="{{ $category->id }}">
                    <span style="background: rgb({{ $category->rgb_color }})">{{ $category->name }}</span>
                </label>

                <p class="notify">{{ $category->description }}</p>
            @endforeach
        </div>

        @include('widgets.select-attributes', [
            'attrs' => $tags,
            'heading' => 'Теґи',
            'textarea_selected_id_name' => 'tags-selected',
            'placeholder' => 'Виберіть тег',
            'rows' => 5,
        ])

        <input type="submit" value="Пошук">

    </form>

</div>
