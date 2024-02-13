<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isLikedFf(Fanfiction $fanfic): bool
    {
        return Like::where('gave_like', $this->id)->where('fanfiction', $fanfic->id)->exists();
    }

    public function isDislikedFf(Fanfiction $fanfic): bool
    {
        return Dislike::where('gave_dislike', $this->id)->where('fanfiction', $fanfic->id)->exists();
    }

    public function isSubscribed(Fanfiction $fanfic): bool
    {
        return Subscribe::where('user_id', Auth::user()->id)
            ->where('fanfiction_id', $fanfic->id)->exists();
    }
}
