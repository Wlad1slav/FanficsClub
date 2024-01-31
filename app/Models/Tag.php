<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tag extends Model
{
    use HasFactory;
    use BaseGenerationTrait;

    protected $table = 'tags';
    protected $guarded = [];

    private $db;
    private bool $hasSlug = false;

    private array $BASE_ROWS = [
        [
            'name' => 'AU',
            'description' => '',
            'notification' => null,
        ],[
            'name' => 'OOC',
            'description' => '',
            'notification' => null,
        ],[
            'name' => 'Драма',
            'description' => '',
            'notification' => null,
        ],[
            'name' => 'PWP',
            'description' => '',
            'notification' => '18+',
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
