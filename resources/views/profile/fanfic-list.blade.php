@extends('profile.layout')


@section('content')

    <h1>Мої твори</h1>

    @include('widgets.button', [
        'title' => 'Створити новий',
        'url' => route('FanficCreatePage'),
    ])

    <div>

        @foreach($fanfics as $fanfic)

            <div class="fanfic-profile-container">
                <a href="{{ route('ChapterListPage', ['ff_slug' => $fanfic->slug]) }}">{{ $fanfic->title }}</a>

                <p>
                    @foreach($fanfic->fandoms as $fandom)
                        / {{ $fandom->name }} /
                    @endforeach
                </p>

                <p>
                    <span class="grow">{{ $fanfic->likes->count() }}</span> | <span
                        class="fall">{{ $fanfic->dislikes->count() }}</span>
                </p>

                <p>
                    {{ $fanfic->views->count() }}
                </p>

                @include('widgets.button', [
                    'title' => 'Перейти',
                    'url' => route('FanficPage', $fanfic->slug),
                ])
            </div>

        @endforeach

    </div>

@endsection
