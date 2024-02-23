<?php

namespace App\Models;

use App\Traits\BaseGenerationTrait;
use App\Traits\ConvertStringAttributesTrait;
use App\Traits\FanfictionAccessTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

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

    private array $BASE_ROWS;

    public function __construct()
    {

        if ($this->count() === 0) {
            // Якщо таблиця пустая, то в ній генеруються стандартні рядки
            $this->BASE_ROWS = include '../config/default-database/fandoms.php';
            $this->generate();
        }
    }

    public function category(): BelongsTo
    {   // Зв'язок з моделю FandomCategories
        // для кожного екземпляра Fandom можна отримати категорію
        return $this->belongsTo(FandomCategories::class, 'fandom_category_id');
    }

    public function calculatePopularity(): int
    {   // Рахує, скільки фанфіків в певному фандомі

        return Fanfiction::where('is_draft', false)->whereJsonContains('fandoms_id', $this->id)->count();
    }

    public static function calculateAllPopularity(): void
    {   // Рахує, скільки фанфіків в усіх фандомах і зберігає значення

        foreach (self::all() as $fandom) {
            $fandom->fictions_amount = $fandom->calculatePopularity();
            $fandom->save();
        }

        Cache::pull('top_fandoms');
        Cache::pull('top_fandoms_50');
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
        $category = Cache::remember("fandom_category_$categorySlug", 60*60*168, function () use ($categorySlug) {
            return FandomCategories::where('slug', $categorySlug)->first();
        });

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
