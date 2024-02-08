<nav>
    <div>
        <a href="#">Редагувати твір</a>
        <a href="#">Розділи</a>
        <a href="{{ route('ChapterCreatePage', ['ff_slug' => $fanfic->slug]) }}">Створити розділ</a>
    </div>
    <a href="{{ route('FanficPage', ['ff_slug' => $fanfic->slug]) }}" style="text-align: center; border-top: 2px solid var(--clr-grey)">Переглянути</a>
</nav>
