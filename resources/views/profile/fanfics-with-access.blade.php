@extends('profile.layout')


@section('content')

    <h1>Твори до яких я маю доступ</h1>

    @if($coauthorAccess !== null)
        @foreach($coauthorAccess as $fanfic)

            <h2>Де ви співавтор</h2>

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
    @endif

    @if($editorAccess !== null)
        @foreach($editorAccess as $fanfic)

            <h2>Де ви редактор</h2>

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
    @endif

@endsection
