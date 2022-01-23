<?php

namespace App\Models;

use App\Traits\HasPrimaryKeyUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasPrimaryKeyUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    public function getKeyName(): string
    {
        return "uuid";
    }

    public function follows(): HasMany
    {
        return $this->hasMany(Follow::class,'user_uuid');
    }

    public function followers(): HasMany
    {
        return $this->hasMany(Follower::class,'user_uuid');
    }

    public function postsUserFollows()
    {
        return $this->belongsToMany(Post::class,'following','user_uuid','user_uuid_follow','uuid','user_id');
    }

    public function postsUserFollowers()
    {
        return $this->belongsToMany(Post::class,'followers','user_uuid','user_uuid_follower','uuid','user_id');
    }

    public function myPosts()
    {
        return $this->hasMany(Post::class,'user_id','uuid');
    }
}
