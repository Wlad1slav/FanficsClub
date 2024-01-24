<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'user_profiles';
    protected $guarded = [];

    // Отримати користувача (User), до якого належить профіль
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
