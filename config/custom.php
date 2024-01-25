<?php

return [

    // Літери, правильну транслітерацію яких,
    // метод Str::slug не підтримує
    //
    // Використовується в SlugGenerationTraits
    'ukranian_transliteration' => [
        'г' => 'h', 'ґ' => 'g',
        'е' => 'e', 'є' => 'ie',
        'и' => 'y', 'і' => 'i',
        'й' => 'j',
    ]
];
