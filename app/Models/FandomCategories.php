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
    use BaseGenerationTrait;

    protected $table = 'fandom_categories';
    protected $guarded = [];
    private $db;
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
        $this->db = DB::table($this->getTable());

        if ($this->db->count() === 0)
            // Якщо таблиця пустая, то в ній генеруються стандартні категорії
            $this->generate();
    }
}
