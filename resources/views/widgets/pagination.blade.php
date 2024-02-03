@if ($paginator->hasPages())

    <link rel="stylesheet" href="{{ asset('css/pagination.css') }}">

    <p class="ff-amount">{{ $paginator->total() }} фанфіків</p>

    <div class="pagination">

        {{-- Попередня сторінка --}}
        @if ($paginator->onFirstPage())
            <span class="disable row">&laquo;</span>
        @else
            <a class="row row" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
        @endif

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
                        <a href="{{ "$url&{$_SERVER['QUERY_STRING']}" }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Наступна сторінка --}}
        @if ($paginator->hasMorePages())
            <a class="row" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
        @else
            <span class="disabled row">&raquo;</span>
        @endif

    </div>

@endif
