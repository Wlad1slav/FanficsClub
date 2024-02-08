<nav>
    <div>
        <a href="{{ route('MyProfilePage') }}">Профіль</a>
        <a href="{{ route('FanficListPage') }}">Роботи</a>
        <a href="{{ route('FanficCreatePage') }}">Опублікувати</a>
        <a href="#">Колекції</a>
        <a href="#">Підписки</a>
    </div>
    <a href="{{ route('LogoutAction') }}" style="text-align: center; border-top: 2px solid var(--clr-grey)">Вийти</a>
</nav>
