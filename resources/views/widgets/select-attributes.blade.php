<script>

    function selectAttr(textareaId, textLineId) {
        // Функція, що встановлює атрібути в певну textarea

        var attr = document.getElementById(textLineId).value; // Отримання вибраного атрибуту

        var textarea = document.getElementById(textareaId).value;
        if (!textarea.includes(attr)) // Якщо textarea не містить атрибуту
            // Встановлення атрібуту в textarea
            document.getElementById(textareaId).value += `${attr}, `;

        document.getElementById(textLineId).value = ''; // Очищення строки з вибором атрибуту
    }

</script>

<!--

Приймає параметри:
    $attrs - Атрибути, які можна вибирати
    $heading - Заголовок для віджету
    $textarea_selected_id_name - id i name текстового поля, в якому будуть зберігатися атрибути
    $notify - Попередження під textarea
    $placeholder - Що буде показано в строці пошуку у якості підказки

Генерує параметри:
    $listId - Для отримання оригінального id
    для переліка усіх атрибутів і строки вибору

-->

@php $listId = rand(1000, 10000); @endphp

<!-- Текстове поле з вибраними атрибутами -->
<label for="{{ $textarea_selected_id_name ?? 'selected' }}">{{ $heading }}</label>
<textarea name="{{ $textarea_selected_id_name ?? 'selected' }}"
          id="{{ $textarea_selected_id_name ?? 'selected' }}" rows="5"></textarea>

<p class="notify">{{ $notify ?? '' }}</p> <!-- Попередження під текстовим полем -->

<!-- Вибір атрибутів. Після вибору, вони попадають в textarea. -->
<input onchange="selectAttr('{{ $textarea_selected_id_name ?? 'selected' }}', 'select-{{ $listId }}')"
       list="list-{{ $listId }}" id="select-{{ $listId }}" placeholder="{{ $placeholder ?? '' }}" class="select">

<datalist id="list-{{ $listId }}">
    @foreach($attrs as $attr)
        <option value="{{ $attr->name }}"></option>
    @endforeach
</datalist>
