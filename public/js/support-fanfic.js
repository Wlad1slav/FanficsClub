// Лайкнути фанфік
$('.like-btn').click(function() {
    var action = $(this).data('action');
    $.ajax({
        type: "GET",
        url: action,
        data: {
            _token: "{{ csrf_token() }}",
        },
        success: function(data) {
            $('.likes-count').text(data.likes);
            $('.dislikes-count').text(data.dislikes);

            $('.like-btn').addClass('selected');
            $('.dislike-btn').removeClass('selected');
        }
    });
});

// Діслайкнути фанфік
$('.dislike-btn').click(function() {
    var action = $(this).data('action');
    $.ajax({
        type: "GET",
        url: action,
        data: {
            _token: "{{ csrf_token() }}",
        },
        success: function(data) {
            $('.dislikes-count').text(data.dislikes);
            $('.likes-count').text(data.likes);

            $('.dislike-btn').addClass('selected');
            $('.like-btn').removeClass('selected');
        }
    });
});

// Підписатися на фанфік чи відписатися
$('.subscribe-btn').click(function() {
    var action = $(this).data('action');
    $.ajax({
        type: "GET",
        url: action,
        data: {
            _token: "{{ csrf_token() }}",
        },
        success: function(data) {
            $('.subscribe-btn a').text(data.btn_text);
        }
    });
});

