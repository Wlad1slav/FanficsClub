<script>
    let charactersInput = 0;

    function selectCharacter() {
        /* Функція, що відпрацьовує, коли користувач вибірає персонажа
        При виборі персонажа, з'являється нове поле для вибору йому пари
        Довжина пейрінга може бути безкінечною */
        console.log('selectCharacter')

        if (charactersInput === 0) {
            document.getElementById('add-character-button').classList.remove('no-display');
        }

        charactersInput++;

        var characters = @json($characters); // Отримання усіх персонажів у json форматі

        // Створюємо елемент <input> і встановлюємо його атрибути
        var input = document.createElement('input');
        input.id = `character-select-${charactersInput}-input`;
        input.setAttribute('placeholder', 'Виберіть пейрінг');
        input.setAttribute('list', `character-select-${charactersInput}`); // list = відповідному номеру пернсонажа
        input.onchange = selectCharacter;

        // Видалення функції з минулого вибору персонажу
        document.getElementById(`character-select-${charactersInput-1}-input`).onchange = null;

        // Створюємо елемент <datalist> і встановлюємо його id
        var dataList = document.createElement('datalist');
        dataList.id = `character-select-${charactersInput}`; // id = відповідному номеру пернсонажа

        // Додаємо опції до <datalist> використовуючи масив characters
        characters.forEach(function(character) {
            var option = document.createElement('option');
            option.value = character.name;
            dataList.appendChild(option);
        });

        // Додаємо <input> і <datalist> до DOM
        var container = document.getElementById('characters-select-container');
        container.appendChild(input);
        container.appendChild(dataList);
    }

    function addCharacter() {
        /* Додає персонажа, чи пейрінг, в textarea з усіма персонажами */

        var paring = '';

        for (var i = 0; i < charactersInput+1; i++) {
            // Отримання вибраного персонажа
            let character = document.getElementById(`character-select-${i}-input`).value;

            if (i === 0) {
                paring = character;
                document.getElementById(`character-select-${i}-input`).value = ''; // Оновлення полей вибору персонажей
            } else {
                if (paring !== '' && document.getElementById(`character-select-${i}-input`).value !== '') {
                    paring += '/';
                }
                paring += document.getElementById(`character-select-${i}-input`).value;

                // Оновлення полей вибору персонажей
                document.getElementById(`character-select-${i}-input`).remove();
                document.getElementById(`character-select-${i}`).remove();
            }

            console.log(paring);
        }

        // Оновлення полей вибору персонажей
        document.getElementById(`character-select-0-input`).onchange = selectCharacter;

        console.log(charactersInput)

        document.getElementById('characters').value += `${paring}, `;

        // Оновлення полей вибору персонажей
        document.getElementById(`character-select-0-input`).onchange = selectCharacter;
        charactersInput = 0;
        document.getElementById('add-character-button').classList.add('no-display');
    }
</script>

@if($has_label ?? true)
    <label for="characters">Персонажі</label>
@endif
<textarea id="characters"
          name="characters"
          rows="5"
          style="margin-bottom: var(--indent-small);">{{ $_GET['characters'] ?? '' }}{{ $default_values ?? '' }}{{ old('characters') }}</textarea>

<div style="display: flex; align-items: flex-start; width: 100%;" id="characters-select">
    <div id="characters-select-container" style="width: 100%;">
        <input onchange="selectCharacter()"
               id="character-select-0-input"
               list="character-select-0"
               placeholder="Виберіть персонажа">
        <datalist id="character-select-0">
            @foreach($characters as $character)
                <option value="{{ $character->name }}">{{ $character->belonging_to_fandom->name }}</option>
            @endforeach
        </datalist>
    </div>

    @include('widgets.button', [
        'title' => 'Додати',
        'action' => 'addCharacter()',
        'styles' => 'no-display mrg-top-0 mrg-bottom-0',
        'id' => 'add-character-button'
    ])
</div>
