<nav>
    <div>
        <a href="{{ route('ChapterListPage', ['ff_slug' => $fanfic->slug]) }}">Розділи</a>
        @if(\App\Policies\FanfictionPolicy::isAuthorStatic(Auth::user(), $fanfic))
            <a href="{{ route('FanficEditPage', ['ff_slug' => $fanfic->slug]) }}">Редагувати твір</a>
            <a href="{{ route('ChapterCreatePage', ['ff_slug' => $fanfic->slug]) }}">Створити розділ</a>
            <a href="{{ route('UsersAccessPage', ['ff_slug' => $fanfic->slug]) }}">Керувати доступом</a>
        @endif
    </div>
    <a href="{{ route('FanficPage', ['ff_slug' => $fanfic->slug]) }}" style="text-align: center; border-top: 2px solid var(--clr-grey)">Переглянути</a>
</nav>
