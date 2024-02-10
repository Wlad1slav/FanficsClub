@extends('layouts.main')

@section('title')
    {{ $title }} :: Фанфіки українською
@endsection


@section('content')

    <link rel="stylesheet" href="{{ asset('css/fanfic/view.css') }}">

    <div id="fanfic">
        @if($fanfic->author == \Illuminate\Support\Facades\Auth::user())
            <div class="author-actions">

                <a href="{{ route('ChapterListPage', ['ff_slug' => $fanfic->slug]) }}">Редагувати</a>
                <a href="{{ route('ChapterCreatePage', ['ff_slug' => $fanfic->slug]) }}">Нова глава</a>

            </div>
        @endif

        <div class="ff-info">

            <div class="short-info">                                        <!-- Коротка інформація про фанфік -->

                <div class="info">
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
                </div>

                <div class="rating">
                    <!-- Рейтинг фанфіку -->
                    <p class="rating grow">{{ $fanfic->rating }}</p>
                    <p>|</p>
                    <!-- Анті-рейтинг фанфіку -->
                    <p class="anti-rating fall">{{ $fanfic->anti_rating }}</p>
                </div>

            </div>

            <div>
                <!-- Фандоми, до яких належить фанфік -->
                <h3>Фандоми</h3>

                <!-- Якщо фанфік належить певним фандомам, то генеруються посилання на усі пов'язані фандоми -->
                @php $fandoms = ''; @endphp
                @foreach($fanfic->getFandomsAttribute() as $fandom)
                    @php $fandoms .= "$fandom->name, "; @endphp
                    <a class="fandom-link"
                       href="{{ route('FilterPage', ['fandoms-selected' => $fandom->name]) }}">
                        {{ $fandom->name }}
                    </a>
                @endforeach
            </div>


            <div>
                <!-- Перелік персонажів і пейрингів персонажів -->
                @php
                    $charactersAll = json_decode($fanfic->characters, true);
                @endphp
                @if(count($charactersAll['parings']) > 0) <!-- Стосунки -->
                    <div>
                        <h3>Стосунки</h3>
                        @foreach($charactersAll['parings'] as $paring)
                            @php
                                foreach ($paring as $key => $character)
                                    $paring[$key] = \App\Models\Character::find($character)->name;
                                $paring = implode('/', $paring)
                            @endphp
                            <a class="fandom-link"
                               href="{{ route('FilterPage', ['fandoms-selected' => $fandoms, 'characters' => $paring]) }}">
                                {{ $paring }}</a>
                        @endforeach
                    </div>
                @endif

                @if(count($charactersAll['characters']) > 0) <!-- Персонажі -->
                    <div>
                        <h3>Персонажі</h3>
                        @foreach($charactersAll['characters'] as $character_id)
                            @php $character = \App\Models\Character::find($character_id) @endphp
                            <a class="fandom-link"
                               href="{{ route('FilterPage', ['fandoms-selected' => $fandoms, 'characters' => $character->name]) }}">
                                {{ $character->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <!-- Перелік теґів, що містить фанфік -->
                @if($fanfic->tags->count() > 0)
                    <h3>Теґи</h3>
                    @foreach($fanfic->tags as $tag)
                        @if($tag->notification !== null)
                            <a class="fandom-link"
                               href="{{ route('FilterPage', ['fandoms-selected' => $fandoms, 'tags-selected' => $tag->name]) }}">
                                {{ $tag->name }} <span>{{ $tag->notification }}</span></a>
                        @else
                            <a class="fandom-link"
                               href="{{ route('FilterPage', ['fandoms-selected' => $fandoms, 'tags-selected' => $tag->name]) }}">
                                {{ $tag->name }}</a>
                        @endif
                    @endforeach

                @endif

            </div>

                <div class="statistic">                                         <!-- Статистика по фанфіку -->
                <p>Створено: {{ $fanfic->created_at->format('Y-m-d') }}</p>     <!-- Дата створення -->
                <p>Оновлено: {{ $fanfic->updated_at->format('Y-m-d H:i') }}</p>                      <!-- Дата останнього оновлення -->
                <p>Слов: {{ $fanfic->words_amount }}</p>                        <!-- Кількість слів -->
                <p>Розділів: {{ $fanfic->chapters_amount }}</p>                 <!-- Кількість розділів -->
                <p>Переглядів: {{ $fanfic->views }}</p>                         <!-- Кількість переглядів -->
            </div>

        </div>

        <div class="description">
            <h1>{{ $fanfic->title }}</h1>
            <h2>{{ $fanfic->author->name }}</h2>

            <div class="desc">
                @if(strlen($fanfic->description) > 0)
                    <h3>Опис</h3>
                    <p>{{ $fanfic->description }}</p>
                @endif
            </div>

            <div class="desc">
                @if(strlen($fanfic->additional_descriptions) > 0)
                    <h3 class="desc">Нотатки</h3>
                    <p>{{ $fanfic->additional_descriptions }}</p>
                @endif
            </div>
        </div>

        @if($chapters !== null)

            <div class="action">
                <form action="{{ route('ChapterSelectAction', ['ff_slug' => $fanfic->slug ]) }}"
                      method="post"
                      class="select-chapter">
                    @csrf
                    <select name="chapter" id="chapter-select">
                        @foreach($chapters as $c)
                            <option value="{{ $c->slug }}" @selected($c==$chapter)>{{ $c->title }}</option>
                        @endforeach
                    </select>

                    <input type="submit" value="Вибрати">
                </form>

                <div>
                    <a href="#" class="support positive">↑</a>
                    <a href="#" class="support negative">↓</a>

                    @include('widgets.button', [
                        'title' => 'Підписатися',
                        'url' => '#',
                    ])

                    @include('widgets.button', [
                        'title' => 'Зберегти в колекцію',
                        'url' => '#',
                    ])

                    @include('widgets.button', [
                        'title' => 'Завантажити',
                        'url' => '#',
                    ])
                </div>

            </div>

        @endif

    </div>

    @if($chapters !== null)
        @include('fanfic-view.chapter', ['chapter' => $chapter ?? $chapters->first()])
    @endif

@endsection
