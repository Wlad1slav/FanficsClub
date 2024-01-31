<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/*

    1   id 	            bigint UNSIGNED                             Первинний індекс
	2	name	        varchar(255)	    utf8mb4_unicode_ci      Індекс
	3	rgb_color	    varchar(255)	    utf8mb4_unicode_ci
	4	description	    text	            utf8mb4_unicode_ci
	5	created_at	    timestamp
	6	updated_at	    timestamp

*/

class Category extends Model
{
    use HasFactory;
    use BaseGenerationTrait;
    use FanfictionAccessTrait;

    protected $table = 'categories';
    protected $guarded = [];

    private bool $hasSlug = false;

    private array $BASE_ROWS = [
        [
            'name' => 'Ґен',
            'rgb_color' => '200 100 0',
            'description' => 'Без акценту на романтичних відношенях.',
        ],[
            'name' => 'Ч/Ж',
            'rgb_color' => '70 180 70',
            'description' => 'З акцентом на романтичних відношенях між чоловіком і жінкою.',
        ],[
            'name' => 'Ч/Ч',
            'rgb_color' => '70 150 240',
            'description' => 'З акцентом на романтичних відношенях між чоловіком і чоловіком.',
        ],[
            'name' => 'Ж/Ж',
            'rgb_color' => '255 150 240',
            'description' => 'З акцентом на романтичних відношенях між жінкою і жінкою.',
        ],
    ];

    public function __construct()
    {

        if ($this->count() === 0)
            // Якщо таблиця пустая, то в ній генеруються стандартні рядки
            $this->generate();
    }

    public static function getFanfics(string $categoryId)
    {   // Повертає усі фандоми з певной категорією
        return Fanfiction::where('category_id', $categoryId)->get();
    }

}
