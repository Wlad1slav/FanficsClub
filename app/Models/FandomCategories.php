<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

require_once 'SlugGenerationTrait.php';

class FandomCategories extends Model
{
    use HasFactory;
    use SlugGenerationTrait;

    protected $table = 'fandom_categories';
    protected $guarded = [];
    private $db;

    private array $CATEGORIES = [
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
        $this->db = DB::table($this->getTable());

        if ($this->db->count() === 0)
            // Якщо таблиця пустая, то в ній генеруються стандартні категорії
            $this->generate();
    }


    public function generate() {
        // Метод, що генерує категорії фандомів

        foreach ($this->CATEGORIES as $num => $category) {
            // Якщо в якомусь з масивів не задан slug для категорії фандом,
            // то він генерується
            if (!isset($category['slug']))
                $category['slug'] = self::getSlug($category['name']);
            $this->CATEGORIES[$num] = $category;
        }

        // Після перевірки коректності масиву, дані заносяться в таблицю
        $this->db->insert($this->CATEGORIES);
    }
}
