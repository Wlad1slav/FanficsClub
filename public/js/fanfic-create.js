// Перевірка, авторства фанфіку. Якщо тип фанфіка - переклад,
// то з'являються додаткові поля (Автор оригінала і Посилання на оригінал)
document.getElementById('type_of_work-translate').addEventListener('change', function () {
    document.getElementById('with-translate-1').classList.remove('no-display');
    document.getElementById('with-translate-2').classList.remove('no-display');
});

// Якщо користувач повертає авторство фанфіка на "Я автор",
// то поля для вводу автора оригінала і посилання на оригінал знову становляться невидимими
document.getElementById('type_of_work-original-work').addEventListener('change', function () {
    document.getElementById('with-translate-1').classList.add('no-display');
    document.getElementById('with-translate-2').classList.add('no-display');
});

/////////////////////////////////////////////////////////
// Перевірка, оригінальності роботи. Якщо тип роботи - фанфік,
// то з'являється додаткове поле для вибору фандомів, які відносяться до фанфіка
document.getElementById('originality_of_work-fanfic').addEventListener('change', function () {
    document.getElementById('if-fanfic').classList.remove('no-display');
});

// Якщо користувач встановлює оригінальність фанфіка на оригінальний твір,
// то поля для вводу автора оригінала і посилання на оригінал знову становляться невидимими
document.getElementById('originality_of_work-original').addEventListener('change', function () {
    document.getElementById('if-fanfic').classList.add('no-display');
});
