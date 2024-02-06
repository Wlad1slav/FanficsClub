<?php

namespace App\Models;

use App\Traits\BaseGenerationTrait;
use App\Traits\ConvertStringAttributesTrait;
use App\Traits\FanfictionAccessTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/*

	1   id                      bigint UNSIGNED	                            Первинний індекс
	2	slug                    varchar(255)	    latin1_bin		        Індекс UNIQUE
	3	name                    varchar(255)	    utf8mb4_unicode_ci	    Індекс UNIQUE
	4	image	                varchar(255)	    utf8mb4_unicode_ci
	5	fandom_category         varchar(255)	    latin1_bin              Індекс fandom_categories('slug')
	6	description	            text	            utf8mb4_unicode_ci
	7	created_at	            timestamp
	8	updated_at	            timestamp
	9	fictions_amount	        bigint
	10	related_media_giant     varchar(255)	    latin1_bin              Індекс fandoms('slug')

*/

class Fandom extends Model
{
    use HasFactory;
    use BaseGenerationTrait;
    use FanfictionAccessTrait;
    use ConvertStringAttributesTrait;

    protected $table = 'fandoms';
    protected $guarded = [];
    private bool $hasSlug = true;

    private array $BASE_ROWS = [
        [
            'name' => 'Гаррі Поттер ДЖ. РОУЛІНГ',
            'fandom_category_id' => 1, // Медіагіганти
            'description' => null,
            'related_media_giant_fandom_id' => null,
            'image' => 'images/fandoms/fandom-harry-potter.webp'
        ],[
            'name' => 'Зоряні Війни',
            'fandom_category_id' => 1,
            'description' => null,
            'related_media_giant_fandom_id' => null,
            'image' => 'images/fandoms/fandom-star-wars.webp'
        ],[
            'name' => 'Marvel',
            'fandom_category_id' => 1,
            'description' => null,
            'related_media_giant_fandom_id' => null,
            'image' => 'images/fandoms/fandom-marvel.webp'
        ],[
            'name' => 'Boku no Hero Academia',
            'fandom_category_id' => 4, // Аніме & Манга
            'description' => null,
            'related_media_giant_fandom_id' => null,
            'image' => 'images/fandoms/fandom-boku-no-hero-academia.webp'
        ],[
            'name' => 'Всесвіт Д.Р.Р. ТОЛКІНА',
            'fandom_category_id' => 2, // Книги & Література
            'description' => null,
            'related_media_giant_fandom_id' => null,
            'image' => 'images/fandoms/fandom-lord-of-the-rings.webp'
        ],
    ];

    public function __construct()
    {

        if ($this->count() === 0)
            // Якщо таблиця пустая, то в ній генеруються стандартні фандоми
            $this->generate();
    }

    public function category(): BelongsTo
    {   // Зв'язок з моделю FandomCategories
        // для кожного екземпляра Fandom можна отримати категорію
        return $this->belongsTo(FandomCategories::class, 'fandom_category_id');
    }

    public function calculatePopularity(): void
    {   // Рахує, скільки фанфіків в кожному фандомі
        $fanfictions = Fanfiction::all()->groupBy('fandom'); // Збираємо усі фанфіки і групуємо за фандомами

        foreach ($this->all() as $fandom) {
            // Перевіряємо, чи існує фандом у групованому масиві
            if (isset($fanfictions[$fandom->slug]))
                $fandom->fictions_amount = $fanfictions[$fandom->slug]->count(); // Рахуємо, скільки фф в фандомі
            else
                $fandom->fictions_amount = 0; // Якщо записи фанфіків відсутні, встановлюємо 0

            $fandom->save(); // Зберігаємо рядок фандому з новою кількістю
        }
    }

    public static function getFandomsOrderedByCategories(?int $fandomsInOneCategory = null): array
    {   // Повертає масив заданой кількості фандомів, відсортированих по їх категоріям.
        // Ключем виступає назва категорії.
        // Елементом виступає строка slug категорії і Laravel колекція з усіма фандомами, що належать категорії.
        // Фандоми всередені Laravel колекції відсортировані по кількості фанфіків в них.

        $result = [];
        foreach (FandomCategories::all() as $category)
            $result[$category->name] = [
                'slug' => $category->slug,
                'fandoms' => $category->getOrderedFandoms(5, 'fictions_amount')
            ];

        return $result;
    }

    public static function getFandomsOrderedByAlphabet(string $categorySlug): array
    {   // Повертає масив фандомів певної категорії, відсортированих по їх алфавіту.
        // Ключем виступає літера, на яку повинаються фандоми.
        // Елементом виступає масив Laravel колекцій фандомів.

        // Отримання категорії, з якої виводити показувати фандоми
        $category = FandomCategories::where('slug', $categorySlug)->first();

        $result = [];

        foreach ($category->getOrderedFandoms(-1, 'name') as $fandom) {
            // Отримання першої літери назви фандому
            // mb_substr потрібно, щоб нормально сприймало кириличеські символи
            $firstLetter = mb_substr($fandom->name, 0, 1);

            if (!isset($result[$firstLetter]))
                $result[$firstLetter] = [];

            $result[$firstLetter][] = $fandom;
        }

        return $result;
    }


}
