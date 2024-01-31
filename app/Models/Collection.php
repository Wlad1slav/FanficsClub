<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Collection extends Model
{
    use HasFactory;

    protected $table = 'collections';
    protected $guarded = [];

    public function getFandomsAttribute(?int $amount = null)
    {
        $ffIds = json_decode($this->attributes['fanfictions'], true) ?? [];
        return Fanfiction::whereIn('id', $ffIds)->paginate($amount);
    }

    public function author(): BelongsTo
    {   // Зв'язок з моделю User
        return $this->belongsTo(User::class);
    }
}
