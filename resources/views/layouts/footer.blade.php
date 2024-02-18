<link rel="stylesheet" href="{{ asset('css/footer.css') }}">

<footer>

    <div class="links">
        <div>
            <h4>Про сайт</h4>
            <a href="{{ route('AboutSitePage') }}#about-us">Про нас</a>
            <a href="{{ route('AboutSitePage') }}#contact-us">Зв'язок з нами</a>
            <a href="{{ route('AboutSitePage') }}#site-map">Мапа сайту</a>
        </div>

        <div>
            <h4>Політика</h4>
            <a href="#">Користувача угода</a>
            <a href="#">Політика конфіденційності</a>
        </div>

        <div>
            <h4>Правила</h4>
            <a href="{{ route('RulesPage') }}#publish">Викладення творів</a>
            <a href="#">Вміст творів</a>
            <a href="#">Коментарі під розділами</a>
        </div>
    </div>

    <p>Сайт не несе відповідальності за вміст творів. Усі твори належать їх авторам.</p>

</footer>
