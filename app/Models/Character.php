<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Character extends Model
{
    use HasFactory;
    use BaseGenerationTrait;

    protected $table = 'characters';
    protected $guarded = [];

    private $db;
    private bool $hasSlug = false;

    private array $BASE_ROWS = [
        [
            'name' => 'Гаррі Поттер',
            'belonging_to_fandom_id' => 1,
        ],[
            'name' => 'Енакін Скайвокер',
            'belonging_to_fandom_id' => 2,
        ],[
            'name' => 'Тоні Старк',
            'belonging_to_fandom_id' => 3,
        ],[
            'name' => 'Фродо Торбін',
            'belonging_to_fandom_id' => 5,
        ],[
            'name' => 'Рон Візлі',
            'belonging_to_fandom_id' => 1,
        ],
    ];

    public function __construct()
    {
        $this->db = DB::table($this->getTable());

        if ($this->db->count() === 0)
            // Якщо таблиця пустая, то в ній генеруються стандартні рядки
            $this->generate();
    }

}
