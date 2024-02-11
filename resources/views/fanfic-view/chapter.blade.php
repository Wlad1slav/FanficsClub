<link rel="stylesheet" href="{{ asset('css/chapter/view.css') }}">

<div id="chapter">

    <h2>{{ $chapter->title }}</h2>

    @if($chapter->additional_descriptions['notify'])
        <div class="chapter-notify">
            <p>{{ $chapter->additional_descriptions['notify'] }}</p>
        </div>
    @endif

    <div class="chapter-content">
{{--        {!! nl2br(e($chapter->content)) !!}--}}
        <p>{{ $chapter->content }}</p>
    </div>

        @if($chapter->additional_descriptions['notes'])
            <div class="chapter-notes">
                <p>{{ $chapter->additional_descriptions['notes'] }}</p>
            </div>
        @endif

    <form action="{{ route('ChapterSelectAction', ['ff_slug' => $fanfic->slug ]) }}"
          method="post"
          class="select-chapter">
        @csrf
        <select name="chapter" id="chapter-select">
            @foreach($chapters as $chapter)
                <option value="{{ $chapter->slug }}">{{ $chapter->title }}</option>
            @endforeach
        </select>

        <input type="submit" value="Вибрати">
    </form>
</div>
