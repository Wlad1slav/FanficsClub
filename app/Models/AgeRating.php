<?php

namespace App\Models;

use App\Traits\BaseGenerationTrait;
use App\Traits\FanfictionAccessTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*

    1	id         	    bigint UNSIGNED	                            Первинний індекс
	2	name 	        varchar(255)	    utf8mb4_unicode_ci      Індекс
	3	description	    text	            utf8mb4_unicode_ci
	4	created_at	    timestamp
	5	updated_at	    timestamp

*/

class AgeRating extends Model
{
    use HasFactory;
    use BaseGenerationTrait;
    use FanfictionAccessTrait;

    protected $table = 'age_ratings';
    protected $guarded = [];

    private bool $hasSlug = false;

    private array $BASE_ROWS = [
        [
            'name' => 'PG',
            'rgb_color' => '110 190 90',
            'description' => 'Деякий контент може бути неприйнятним для дітей.
            Може включати легку ненормативну лексику або мінімальну насильство.',
        ],[
            'name' => 'PG-13',
            'rgb_color' => '190 190 0',
            'description' => 'Може містити контент, не підходящий для дітей до 13 років.
            Можлива присутність сильнішої лексики, насильства, сексуальних сцен чи наркотиків.',
        ],[
            'name' => 'R',
            'rgb_color' => '255 150 70',
            'description' => 'Вміст, який може бути непридатним для підлітків до 17 років.
            Містить сильну лексику, інтенсивне насильство, сексуальний контент або зловживання наркотиками.',
        ],[
            'name' => 'NC-17',
            'rgb_color' => '235 50 50',
            'description' => 'Вміст тільки для дорослих і не підходить для підлітків молодше 17 років.
            Може включати екстремальне насильство, графічну сексуальність або грубу лексику.',
        ],[
            'name' => 'Unrated',
            'rgb_color' => '40 40 40',
            'description' => 'За поза межами загальнолюдської моралі, містить дуже спірний або екстремальний матеріал.
            Фанфікі, що мають цей рейтинг не рекомендуються для ознайомлення і не просуваються нашим сайтом.
            Пошукові системи (Google, Bing) не ранжирують фанфіки з даним рейтингом.',
        ],
    ];

    public function __construct()
    {
        if ($this->count() === 0)
            // Якщо таблиця пустая, то в ній генеруються стандартні рядки
            $this->generate();
    }

    public static function getFanfics(int $ageRatingId)
    {   // Повертає усі фандоми з певним віковим рейтингом
        return Fanfiction::where('age_rating_id', $ageRatingId)->get();
    }
}
