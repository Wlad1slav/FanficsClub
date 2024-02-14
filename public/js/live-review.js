$('.send-review').click(function() {
    // Залишити коментар під розділом

    var action = $(this).data('action');
    var reviewText = $('#add_comment').val();
    $.ajax({
        type: "GET",
        url: `${action}`,
        data: {
            _token: "{{ csrf_token() }}",
            comment: reviewText
        },
        success: function(data) {
            // Створює новий елемент коментаря
            var newComment = $('<div class="review"></div>');
            var userContent = $('<div class="user"></div>');
            var avatarImg = $(`<img class="avatar" src="${data.user.image ?? '/images/default_avatar.webp'}">`);
            var reviewContent = $('<div class="review-content"></div>');
            var userName = $('<h3></h3>').text(data.user.name);
            var commentDate = $('<p></p>').text(data.created_at);
            var commentText = $('<p class="review-text"></p>').text(data.content);

            // Збирає елементи разом
            reviewContent.append(userName).append(commentDate).append(commentText);
            userContent.append(avatarImg).append(reviewContent);
            newComment.append(userContent);

            // Додає новий коментар у блок коментарів на сторінці
            $('.reviews').prepend(newComment);

            // Очищує поле введення коментаря
            $('#add_comment').val('');
        }
    });
});

function answerToReviewTextarea(review, userName, userId) {
    let answer = document.getElementById(`answer-${review}`);
    answer.classList.remove('no-display');

    let field = answer.querySelector('textarea');
    field.value = `${userName}, `
    field.setAttribute('data-answer-to-review', review);
    field.setAttribute('data-answer-to-user', userId);
}

function answerToReview(toReview) {
    // Відповідь на вже існуючий коментар

    let answerTo = document.getElementById(`answer-${toReview}`);
    let answerText = answerTo.querySelector('textarea').value;

    let toUser = answerTo.querySelector('textarea').getAttribute('data-answer-to-user');
    let action = answerTo.querySelector('textarea').getAttribute('data-action');

    $.ajax({
        type: "GET",
        url: `${action}`,
        data: {
            _token: "{{ csrf_token() }}",
            answer_to_review: toReview,
            answer_to_user: toUser,
            comment: answerText,
        },
        success: function(data) {

            // Створює новий елемент коментаря
            var userContent = $('<div class="user answer"></div>');
            var avatarImg = $(`<img class="avatar" src="${data.user.image ?? '/images/default_avatar.webp'}">`);
            var reviewContent = $('<div class="review-content"></div>');
            var userName = $('<h3></h3>').text(data.user.name);
            var commentDate = $('<p></p>').text(data.created_at);
            var commentText = $('<p class="review-text"></p>').text(data.content);

            // Збирає елементи разом
            reviewContent.append(userName).append(commentDate).append(commentText);
            userContent.append(avatarImg).append(reviewContent);

            // Додає новий коментар у блок коментарів на сторінці
            $(`#${data.answer_to}`).append(userContent);

            // Очищує поле введення коментаря
            $(`#add_comment_to_${data.answer_to}`).val('');
        }
    });
}
