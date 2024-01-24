<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagCategory extends Model
{
    use HasFactory;

    protected $table = 'tag_categories';
    protected $guarded = [];

    // Отримати тегі (Tag) в цій категорії
    public function getTags()
    {
        return $this->hasMany(Tag::class);
    }
}
