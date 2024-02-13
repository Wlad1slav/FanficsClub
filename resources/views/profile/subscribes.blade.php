@extends('profile.layout')


@section('content')

    <h1>Мої підписки</h1>

    <ul>
        @foreach($subscribes as $subscribe)
            <li style="display: flex; align-items: center;">
                <a class="huge-link" style="margin-right: var(--indent-medium); margin-bottom: 0;"
                   href="{{ route('FanficPage', ['ff_slug' => $subscribe->fanfiction->slug]) }}">{{ $subscribe->fanfiction->title }}</a>

                @include('widgets.button', [
                    'title' => Auth::user()->isSubscribed($subscribe->fanfiction) ? 'Відписатися' : 'Підписатися',
                    'styles' => 'subscribe-btn',
                    'data' => ['name' => 'action', 'value' => route('SubscribeAction', $subscribe->fanfiction->slug)]
                ])
            </li>
        @endforeach
    </ul>

    <script src="{{ asset('js/support-fanfic.js') }}"></script>

@endsection
