<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'tags';
    protected $guarded = [];

    private $tableConnect;

    public function __construct()
    {
        $this->tableConnect = DB::table($this->table);
    }

    // Отримати категорію (TagCategory), до якого належить тег
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getTableConnect()
    {
        return $this->tableConnect;
    }


}
