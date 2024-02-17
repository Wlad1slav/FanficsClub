function confirmAction(action, question, reload=true) {
    // Підтвердження дії
    const isConfirmed = confirm(question);

    if (isConfirmed) {
        // Якщо підтверджено, відбувається редірект до точки дії
        $.ajax({
            type: "GET",
            url: action,
        });

        if (reload) {
            location.reload();
        }
    }
}
