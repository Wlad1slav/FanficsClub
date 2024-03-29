<?php

namespace App\Models;

use App\Traits\BaseGenerationTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FandomCategories extends Model
{
    use HasFactory;
    use BaseGenerationTrait;

    protected $table = 'fandom_categories';
    protected $guarded = [];
    private bool $hasSlug = true;

    private array $BASE_ROWS = [
        ['name' => 'Медіагіганти'],
        ['name' => 'Книги & Література'],
        ['name' => 'Фільми & Серіали'],
        ['name' => 'Аніме & Манга'],
        ['name' => 'Мультфільми & Комікси'],
        ['name' => 'Реальні люди & Знаменитості'],
        ['name' => "Комп'ютерні ігри"],
        ['name' => 'Музика'],
    ];

    public function __construct()
    {

        if ($this->count() === 0)
            // Якщо таблиця пустая, то в ній генеруються стандартні категорії
            $this->generate();
    }

    public function fandoms(): HasMany
    {   // Отримати усі фандоми, що належать певной категорії
        return $this->hasMany(Fandom::class, 'fandom_category_id');
    }

    public function getOrderedFandoms(int $amount = 5, string $orderedBy = 'name'): Collection
    {   // Отримати усі фандоми, що належать певной категорії
        return $this->hasMany(Fandom::class, 'fandom_category_id')
            ->orderBy($orderedBy, 'desc') // сортування за заданою колонкою
            ->take($amount)->get(); // отримання тільки задану кількість перших рядків
    }
}
