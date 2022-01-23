<?php

namespace App\Models;

use App\Traits\ManagerFollows;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory, ManagerFollows;

    protected $table = 'following';

    protected $fillable = [
        'user_uuid',
        'user_name_follow',
        'user_uuid_follow'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class,'user_id','user_uuid_follow');
    }
}
