function confirmAction(action, question) {
    // Підтвердження дії
    const isConfirmed = confirm(question);

    if (isConfirmed) {
        // Якщо підтверджено, відбувається редірект до точки дії
        $.ajax({
            type: "GET",
            url: action,
        });
        location.reload();
    }
}
