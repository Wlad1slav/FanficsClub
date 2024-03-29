<!--

    Віджет контейнер з інформацією про фанфік.
    Використовується в переліку фанфіків.

    Приймає Laravel колекцію $fanfic з фанфіком, який треба вивести.
    Якщо $fanfic не заданий, то використовується параметр $ffId,
    для знаходження фанфіка по ID.

-->

@php

    if (!isset($fanfic)) {
        if (isset($ffId))
            $fanfic = \App\Models\Fanfiction::find($ffId);
        else
            trigger_error("Не заданий ID фанфіка для генерації відповідного контейнера.", E_USER_ERROR);
    }

@endphp

<link rel="stylesheet" href="{{ asset('css/fanfic/container.css') }}">

<div class="fanfic-container"
    @style(["border-left: 8px solid rgb({$fanfic->category->rgb_color})"])>   <!-- Лівий кордон, того кольору, що був заданий в
                                                                                базі даних для категорії, якої належить фік -->

    <!-- Назва фанфіка -->
    <h3 class="title"><a href="{{ route('FanficPage', ['ff_slug' => $fanfic->slug]) }}">{{ $fanfic->title }}</a></h3>

    <div class="short-info">                                        <!-- Коротка інформація про фанфік -->

        <!-- Категорія, до якої належить фанфік. Колір -
        той, що був заданий для категорії в базі даних -->
        <p @style(["background: rgb({$fanfic->category->rgb_color})"])
        title="{{ $fanfic->category->description }}">
            {{ $fanfic->category->name }}</p>

        <!-- Віковой рейтинг, до якого належить фанфік.
        Колір той, що був заданий для категорії в базі даних -->
        <p @style(["background: rgb({$fanfic->age_rating->rgb_color})"])
        title="{{ $fanfic->age_rating->description }}">
            {{ $fanfic->age_rating->name }}</p>

        <!-- Якщо фанфік - переклад з іншої мови, то
         додається примітка у короткій інформації -->
        @if($fanfic->is_translate)
            <p class="translate-notify">Переклад</p>
        @elseif($fanfic->is_robot_translate)
            <p class="translate-notify"
            title="Фанфік був перекладений роботом.">Роботичний переклад</p>
        @elseif($fanfic->is_postponed)
            <p class="translate-notify"
               title="Фанфік був перенесений з іншого сайту.">Перенесено</p>
        @endif

        <!-- Статус фанфіка -->
        @if($fanfic->is_completed)
            <p style="background: rgb(70,160,100)">Завершен</p>
        @elseif($fanfic->is_frozen)
            <p style="background: rgb(50,135,160)">Заморожений</p>
        @else
            <p style="background: rgb(160,155,50)">В процесі</p>
        @endif

        <!-- Рейтинг фанфіку -->
        <p class="rating">&uarr; {{ $fanfic->likes->count() }}</p>

        <!-- Анті-рейтинг фанфіку -->
        <p class="anti-rating">&darr; {{ $fanfic->dislikes->count() }}</p>

    </div>

    <div class="about-ff">

        @if($fanfic->fandoms_id !== null)
            <!-- Якщо твір - фанфік, то виводяться фандоми до яких він відноситься
            і персонажі, які присутні в фанфіку -->

            <!-- Фандом, до якого належить фанфік -->
            <p><span>Фандом:</span>

                <!-- Якщо фанфік належить певним фандомам, то генеруються посилання на усі пов'язані фандоми -->
                @if($fanfic->fandoms_id !== null)
                    @php $fandoms = ''; @endphp
                    @foreach($fanfic->getFandomsAttribute() as $fandom)
                        @php $fandoms .= "$fandom->name, "; @endphp
                        <a class="fandom-link"
                           href="{{ route('FilterPage', ['fandoms_selected' => $fandom->name, 'type_of_works' => 'fanfic']) }}">
                            {{ $fandom->name }}
                        </a>
                    @endforeach
                @else
                    <!-- Якщо не належить жодному фандому, то встановлюється "Оригінальна робота" -->
                    <a class="fandom-link" href="#">
                        Оригінальна робота
                    </a>
                @endif
            </p>

            <!-- Перелік персонажів і пейрингів персонажів -->
            @if(count($fanfic->characters['characters']) > 0 || count($fanfic->characters['parings']) > 0)
                <p><span>Персонажі:</span>

                    <!-- Пейренги -->
                    @foreach($fanfic->characters['parings'] as $paring)
                        @php
                            foreach ($paring as $key => $character)
                                $paring[$key] = \App\Models\Character::find($character)->name;
                            $paring = implode('/', $paring)
                        @endphp
                        <a class="fandom-link"
                           href="{{ route('FilterPage', ['fandoms_selected' => $fandoms, 'characters' => $paring, 'type_of_works' => 'fanfic']) }}">
                            {{ $paring }}</a>
                    @endforeach

                    <!-- Персонажі -->
                    @foreach($fanfic->characters['characters'] as $character_id)
                        @php $character = \App\Models\Character::find($character_id) @endphp
                        <a class="fandom-link"
                           href="{{ route('FilterPage', ['fandoms_selected' => $fandoms, 'characters' => $character->name, 'type_of_works' => 'fanfic']) }}">
                            {{ $character->name }}
                        </a>
                    @endforeach

                </p>
            @endif

        @else
            <!-- Якщо твір - оригінальний, то виводяться кастомні персонажі
                і помітка, що це оригінальний твір -->

                <p>Оригінальний твір</p>

            @if(count($fanfic->characters) > 0)
                <div>
                    <p><span>Персонажі:</span>
                    {{ implode(', ', $fanfic->characters) }}</p>
                </div>
            @endif
        @endif

        <!-- Перелік теґів, що містить фанфік -->
        @if($fanfic->tags->count() > 0)
            <p><span>Теґи:</span>
                @foreach($fanfic->tags as $tag)

                    @if($fanfic->fandoms_id !== null)
                        @if($tag->notification !== null)
                            <a class="fandom-link"
                               href="{{ route('FilterPage', ['fandoms_selected' => $fandoms ?? '', 'tags_selected' => $tag->name, 'type_of_works' => 'fanfic']) }}">
                                {{ $tag->name }} <span>{{ $tag->notification }}</span></a>
                        @else
                            <a class="fandom-link"
                               href="{{ route('FilterPage', ['fandoms_selected' => $fandoms ?? '', 'tags_selected' => $tag->name, 'type_of_works' => 'fanfic']) }}">
                                {{ $tag->name }}</a>
                        @endif

                    @else
                        @if($tag->notification !== null)
                            <a class="fandom-link"
                               href="{{ route('FilterPage', ['fandoms_selected' => $fandoms ?? '', 'tags_selected' => $tag->name, 'type_of_works' => 'original']) }}">
                                {{ $tag->name }} <span>{{ $tag->notification }}</span></a>
                        @else
                            <a class="fandom-link"
                               href="{{ route('FilterPage', ['fandoms_selected' => $fandoms ?? '', 'tags_selected' => $tag->name, 'type_of_works' => 'original']) }}">
                                {{ $tag->name }}</a>
                        @endif


                    @endif
                @endforeach
            </p>
        @endif

        @if($fanfic->prequel_id !== null and isset($fanfic->prequel_id))
             <p>
                 <span>Сиквел твору: </span>
                 <a class="fandom-link"
                    href="{{ route('FanficPage', $fanfic->prequel->slug) }}">
                    {{ $fanfic->prequel->title }}
                 </a>
             </p>
        @endif

        <!-- Опис фанфіка -->
        <p class="description">{{ $fanfic->description }}</p>

    </div>

    <div class="statistic">                                                 <!-- Статистика по фанфіку -->
        <p>Оновлено: {{ $fanfic->updated_at->format('Y-m-d H:i') }}</p>     <!-- Дата останнього оновлення -->
        <p>Слов: {{ $fanfic->words_amount }}</p>                            <!-- Кількість слів -->

        @if($fanfic->chapters_amount !== null)
            <p>Розділів: {{ $fanfic->chapters_amount }}</p>                     <!-- Кількість розділів -->
        @endif

        <p>Переглядів: {{ $fanfic->views->count() }}</p>                             <!-- Кількість переглядів -->
    </div>


</div>
