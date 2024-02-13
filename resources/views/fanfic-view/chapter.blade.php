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

<link rel="stylesheet" href="{{ asset('css/chapter/comments.css') }}">

<div id="comments">
    <h2>Коментарі</h2>
    <div id="leave-comment">
        <textarea name="comment" id="add_comment" cols="30" rows="10" maxlength="1000"></textarea>
        <input type="submit" value="Відправити">
    </div>

    <div class="reviews">
        @foreach($chapter->reviews as $review)
            @if($review->answer_to_review == null)
                <div class="review">
                    <div class="user">
                        <img class="avatar"
                             src="{{ $review->user->image !== null ?
                                    asset('storage/avatars/' . $review->user->image) :
                                    asset('images/default_avatar.webp') }}" alt="{{ $review->user->name }}">

                        <div class="review-content">
                            <h3>{{ $review->user->name }}</h3>
                            <p>{{ $review->created_at }}</p>
                            <p class="review-text">{{ $review->content }}</p>
                        </div>

                        <div class="action">
                            <a href="#">Відповісти</a>
                            <a href="#">Поскаржитися</a>
                        </div>
                    </div>

                    @foreach($review->answers as $answer)
                        <div class="user answer">
                            <img class="avatar"
                                 src="{{ $answer->user->image !== null ?
                                    asset('storage/avatars/' . $answer->user->image) :
                                    asset('images/default_avatar.webp') }}" alt="{{ $answer->user->name }}">

                            <div class="review-content">
                                <h3>
                                    {{ $answer->user->name }}
                                    <span>Відповідь {{ $answer->answer_user->name }}</span>
                                </h3>
                                <p>{{ $answer->created_at }}</p>
                                <p class="review-text">{{ $answer->content }}</p>
                            </div>

                            <div class="action">
                                <a href="#">Відповісти</a>
                                <a href="#">Поскаржитися</a>
                            </div>
                        </div>
                    @endforeach

                    <div class="leave-comment-to-review">
                        <textarea name="comment" id="add_comment" maxlength="1000"></textarea>
                        <input type="submit" value="Відповісти">
                    </div>
                @endif

            </div>
        @endforeach
    </div>

</div>
