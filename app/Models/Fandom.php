<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    protected $table = 'fandoms';
    protected $guarded = [];
    private $db;
    private bool $hasSlug = true;

    private array $BASE_ROWS = [
        [
            'name' => 'Гаррі Поттер',
            'fandom_category' => 'mediahihanty',
            'description' => null,
            'related_media_giant' => null,
            'image' => 'images/fandoms/fandom-harry-potter.webp'
        ],[
            'name' => 'Зоряні Війни',
            'fandom_category' => 'mediahihanty',
            'description' => null,
            'related_media_giant' => null,
            'image' => 'images/fandoms/fandom-star-wars.webp'
        ],[
            'name' => 'Marvel',
            'fandom_category' => 'mediahihanty',
            'description' => null,
            'related_media_giant' => null,
            'image' => 'images/fandoms/fandom-marvel.webp'
        ],[
            'name' => 'Boku no Hero Academia',
            'fandom_category' => 'anime-manha',
            'description' => null,
            'related_media_giant' => null,
            'image' => 'images/fandoms/fandom-boku-no-hero-academia.webp'
        ],[
            'name' => 'Всесвіт Д.Р.Р. ТОЛКІНА',
            'fandom_category' => 'knyhy-literatura',
            'description' => null,
            'related_media_giant' => null,
            'image' => 'images/fandoms/fandom-lord-of-the-rings.webp'
        ],
    ];

    public function __construct()
    {
        $this->db = DB::table($this->getTable());

        if ($this->db->count() === 0)
            // Якщо таблиця пустая, то в ній генеруються стандартні фандоми
            $this->generate();
    }

//    public function getPopularFandoms(int $num = 5): array {
//
//    }

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

    public static function getFandomsOrderedByFfAmount(int $amount)
    { // Повертає фандоми, сортируючи по кількості фанфіків в них
        $fandoms = Fandom::orderBy('fictions_amount')->take(5)->get();
        foreach ($fandoms as $key=>$fandom) {
            $fandom['fandom_category_name'] = FandomCategories::where('slug', $fandom['fandom_category'])->first()->name;
            $fandoms[$key] = $fandom;
        }
        return $fandoms;
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
                'fandoms' => Fandom::where('fandom_category', $category->slug)
                ->take($fandomsInOneCategory)
                ->orderBy('fictions_amount')
                ->get()
            ];

        return $result;
    }

    public static function getFandomsOrderedByAlphabet(string $categorySlug): array
    {   // Повертає масив фандомів певної категорії, відсортированих по їх алфавіту.
        // Ключем виступає літера, на яку повинаються фандоми.
        // Елементом виступає масив Laravel колекцій фандомів.

        $result = [];

        foreach (self::where('fandom_category', $categorySlug)->orderBy('name')->get() as $fandom) {
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
