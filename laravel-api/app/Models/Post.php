<?php

namespace App\Models;

use App\Traits\HasPrimaryKeyUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, HasPrimaryKeyUuid, SoftDeletes;

    protected $fillable = [
        'id',
        'content',
        'user_id',
        'user_name',
        'url',
        'type',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function (Post $post) {
            $post->user_name = User::find($post->user_id)->name;
        });

    }

    public function getKeyName(): string
    {
        return "uuid";
    }

    public function followers()
    {
        return $this->belongsToMany(User::class,'followers','user_uuid','user_uuid_follower','user_id','uuid');
    }

    public function follows()
    {
        return $this->belongsToMany(User::class,'following','user_uuid','user_uuid_follow','user_id','uuid');
    }
}
