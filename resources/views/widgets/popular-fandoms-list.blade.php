<!--

    Віджет з клікабельними контейнерами, що містять інформацію про фандоми.
    Laravel колекція $fandoms містатить фандоми, які будуть виведені.

    З нативними php масивами не працює.

    Приймає параметри:
    bool $hasCta - чи буде показана кнопка з посиланням на сторінку
    з усіма категоріями фандомів (FandomsCategoriesPage)
    bool $hasTitle - чи буде показан h2 заголовок над фандомами

-->

<div id="popular-fandoms-list">
    @if($hasTitle ?? true) <h2>
        Найпопулярніші Фандоми</h2>
    @endif
    <div class="list">

        <!-- Цикл, що проходиться по заданому масиву фандомів -->
        @foreach($fandoms as $fandom)
            <a href="{{ route('CertainFandomPage', ['slug' => $fandom->slug]) }}"
               class="fandom no-select clickable enlargement"
               style="background-image: url('{{ asset($fandom->image) }}')">

               <div class="info">
                   <div>
                       <p class="category">{{ $fandom->fandom_category_name }}</p>
                       <p class="name">{{ $fandom->name }}</p>
                   </div>
               </div>

            </a>
        @endforeach

    </div>

    @if($hasCta ?? false)
        @include('widgets.button', [
            'title' => 'Усі фандоми',
            'url' => route('FandomsCategoriesPage'),
            'styles' => 'center',
            'hoverEffect' => true
        ])
    @endif

</div>
