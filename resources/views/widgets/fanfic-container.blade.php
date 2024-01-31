@php

    if (!isset($fanfic)) {
        if (isset($ffId))
            $fanfic = \App\Models\Fanfiction::find($ffId);
        else
            trigger_error("Не заданий ID фанфіка для генерації відповідного контейнера.", E_USER_ERROR);
    }

@endphp



<div class="fanfic-container"
    @style(["border-left: 8px solid rgb({$fanfic->category->rgb_color})"])>   <!-- Лівий кордон, того кольору, що був заданий в
                                                                                базі даних для категорії, якої належить фік -->

    <h3 class="title"><a href="#">{{ $fanfic->title }}</a></h3>     <!-- Назва фанфіка -->

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
        <p class="rating">&uarr; {{ $fanfic->rating }}</p>

        <!-- Анті-рейтинг фанфіку -->
        <p class="anti-rating">&darr; {{ $fanfic->anti_rating }}</p>

    </div>

    <div class="about-ff">

        <!-- Фандом, до якого належить фанфік -->
        <p><span>Фандом:</span>
            <a class="fandom-link"
               href="{{ route('CertainFandomPage', ['slug' => $fanfic->fandom->slug]) }}">
                {{ $fanfic->fandom->name }}
            </a>

            <!-- Якщо фанфік є кросовером, то генеруються посилання на усі пов'язані фандоми -->
            @if($fanfic->crossover !== null)
                @foreach($fanfic->getFandomsAttribute() as $crossover)
                    <a class="fandom-link"
                       href="{{ route('CertainFandomPage', ['slug' => $crossover->slug]) }}">
                        {{ $crossover->name }}
                    </a>
                @endforeach
            @endif
        </p>

        <!-- Перелік персонажів і пейрингів персонажів -->
        @if($fanfic->characters !== null)
            <p><span>Персонажі:</span>
                @foreach(json_decode($fanfic->characters) as $character_id)
                    @if(is_int($character_id)) {{-- Якщо елемент просто id персонажа, то виводиться тільки він --}}
                        @php $character = \App\Models\Character::find($character_id) @endphp
                        <a class="fandom-link" href="#">{{ $character->name }}</a>

                    @else {{-- Якщо елемент - масив персонажів, то виводиться пейрінг --}}
                        @php $paring = []; @endphp
                        @foreach($character_id as $id)
                            @php
                                $character = \App\Models\Character::find($id);
                                $paring[] .= $character->name;
                            @endphp
                        @endforeach
                        <a class="fandom-link" href="#">{{ implode('/', $paring) }}</a>

                    @endif

                @endforeach
            </p>
        @endif

        <!-- Перелік міток, що містить фанфік -->
        @if($fanfic->tags !== null)
            <p><span>Мітки:</span>
                @foreach($fanfic->getTagsAttribute() as $tag)
                    <a class="fandom-link" href="#">
                        {{ $tag->name }}
                        @if($tag->notification !== null)
                            <span>{{ $tag->notification }}</span>
                        @endif
                    </a>
                @endforeach
            </p>
        @endif

        <!-- Опис фанфіка -->
        <p class="description">{{ $fanfic->description }}</p>

    </div>

    <div class="statistic">                                 <!-- Статистика по фанфіку -->
        <p>Оновлено: {{ $fanfic->updated_at }}</p>          <!-- Дата останнього оновлення -->
        <p>Слов: {{ $fanfic->words_amount }}</p>            <!-- Кількість слів -->
        <p>Розділів: {{ $fanfic->chapters_amount }}</p>     <!-- Кількість розділів -->
        <p>Переглядів: {{ $fanfic->views }}</p>             <!-- Кількість переглядів -->
    </div>


</div>
