@if ($paginator->hasPages())

    @php

    $queryString = $_SERVER['QUERY_STRING'];

    // Роздіє рядок запиту на масив параметрів
    $params = explode('&', $queryString);

    // Фільтрує параметри, видаляючи ті, що містять слово "page"
    $filteredParams = array_filter($params, function($param) {
        return strpos($param, 'page') === false;
    });

    // Збірає фільтровані параметри назад у рядок запиту
    $filteredQueryString = implode('&', $filteredParams);

    @endphp

    <link rel="stylesheet" href="{{ asset('css/pagination.css') }}">

    <p class="ff-amount">{{ $paginator->total() }} фанфіків</p>

    <div class="pagination">

        {{-- Номери сторінок --}}
        @foreach ($elements as $element)
            {{-- "Три крапки" --}}
            @if (is_string($element))
                <p class="disabled"><span>{{ $element }}</span></p>
            @endif

            {{-- Масив посилань --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <p class="active"><span>{{ $page }}</span></p>
                    @else
                        <!-- $_SERVER['QUERY_STRING'] - запит того ж фільтру -->
                        <a href="{{ "$url&$filteredQueryString" }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

    </div>

@endif
