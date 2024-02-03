@extends('layouts.main')

@section('title')
    {{ $title }} :: Фанфіки українською
@endsection


@section('content')

    <script>

        function selectAttr(textareaId, textLineId) {
            // Функція, що встановлює атрібути в певну textarea

            var attr = document.getElementById(textLineId).value; // Отримання вибраного атрібута

            // Встановлення атрібуту в textarea
            document.getElementById(textareaId).value += `${attr}, `;

            document.getElementById(textLineId).value = ''; // Очищення строки з вибором атрібуту
        }

    </script>

    <h1>Пошук фанфіків</h1>

    @include('widgets.filter-widget')

    @if($fanfics !== null) <!-- $fanfics отримується в контролері FilterController -->
    {{ $fanfics->links('widgets.pagination') }}
        <div id="fanfics">
            @foreach($fanfics as $fanfic)
                @include('widgets.fanfic-container', ['fanfic' => $fanfic])
            @endforeach
            <a href="#filter">Фільтрувати</a>
        </div>
    {{ $fanfics->links('widgets.pagination') }}
    @endif

    <script>
        window.location.hash = "#fanfics";
    </script>

@endsection
