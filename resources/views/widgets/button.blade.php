<!--

Віджет кнопка. Клікабельний контейнер, який гнучно налаштовується.

Приймає параметри:
string $title - текст в кнопці
string $url - посилання, по якому введе кнопка
string $styles - додаткові класи кнопки
bool $hoverEffect - чи буде особливий ефект при наводенні на кнопку

-->

<div class="button
    {{ $styles ?? '' }}
    @if($hoverEffect ?? false) hover-effect @endif"
    @if(isset($action)) onclick="{{ $action }}" @endif
    @if(isset($id)) id="{{ $id }}" @endif>

    <a @if(isset($url)) href="{{ $url }}" @endif>
        <p>{{ $title ?? 'Стандартна назва' }}</p>
        @if($hoverEffect ?? false)
            <span>&GT;</span>
        @endif
    </a>

</div>
