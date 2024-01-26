<div id="popular-fandoms-list">
    <h2>Найпопулярніші Фандоми</h2>
    <div class="list">

        @foreach($fandoms as $fandom)
            <div class="fandom no-select clickable enlargement"
                style="background-image: url('{{ asset($fandom->image) }}')">
                <div class="info">
                    <div>
                        <p class="category">{{ $fandom->fandom_category_name }}</p>
                        <p class="name">{{ $fandom->name }}</p>
                    </div>
                </div>
            </div>
        @endforeach

    </div>





</div>
