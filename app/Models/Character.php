<?php

namespace App\Models;

use App\Traits\BaseGenerationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Character extends Model
{
    use HasFactory;
    use BaseGenerationTrait;

    protected $table = 'characters';
    protected $guarded = [];

    private bool $hasSlug = false;

    private array $BASE_ROWS;

    public function __construct()
    {

        if ($this->count() === 0) {
            // Якщо таблиця пустая, то в ній генеруються стандартні рядки
            $this->BASE_ROWS = include '../config/default-database/characters.php';
            $this->generate();
        }
    }

    public function belonging_to_fandom(): BelongsTo
    {   // Зв'язок з моделю Fandom
        return $this->belongsTo(Fandom::class);
    }

    public static function convertCharactersStrToArray(?string $str): array
    {

        // Перетворення строку з імена користувачів на масив імен
        $charactersNames = array_filter(preg_split('/,\s?/', $str ?? ''));

        $parings = [];
        foreach ($charactersNames as $character)
            // Якщо в елементі з ім'ям персонажу, є "/"
            // то це пейрінг декількох персонажів
            if (strpos($character, '/'))
                $parings[] = explode('/', $character);

        $paringsIds = [];
        foreach ($parings as $paring)
            // Перетворення масивів з імена персонажів в пейренгах
            // на масив айдившників персонажів в пейренгах
            $paringsIds[] = self::whereIn('name', $paring)->pluck('id')->toArray();

        return [
            'characters' => self::whereIn('name', $charactersNames)->pluck('id')->toArray(),
            'parings' => $paringsIds
        ];

    }

    public static function convertOriginalCharactersToArray(?string $str): array
    {
        return array_filter(preg_split('/,\s?/', $str ?? ''));
    }

}
