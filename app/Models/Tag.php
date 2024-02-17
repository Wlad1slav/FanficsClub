<?php

namespace App\Models;

use App\Traits\BaseGenerationTrait;
use App\Traits\ConvertStringAttributesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    use BaseGenerationTrait;
    use ConvertStringAttributesTrait;

    protected $table = 'tags';
    protected $guarded = [];

    private bool $hasSlug = false;

    private array $BASE_ROWS = [];

    public function __construct()
    {

        if ($this->count() === 0) {
            // Якщо таблиця пустая, то в ній генеруються стандартні рядки
            $this->BASE_ROWS = include '../config/default-database/tags.php';
            $this->generate();
        }
    }

}
