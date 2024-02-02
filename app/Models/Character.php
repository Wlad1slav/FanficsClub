<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Character extends Model
{
    use HasFactory;
    use BaseGenerationTrait;

    protected $table = 'characters';
    protected $guarded = [];

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

        if ($this->count() === 0)
            // Якщо таблиця пустая, то в ній генеруються стандартні рядки
            $this->generate();
    }

    public function belonging_to_fandom(): BelongsTo
    {   // Зв'язок з моделю Fandom
        return $this->belongsTo(Fandom::class);
    }

}
