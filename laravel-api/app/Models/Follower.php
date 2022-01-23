<?php

namespace App\Models;

use App\Traits\HasPrimaryKeyUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id_follower',
        'user_name_follower',
        'user_uuid_follower',
        'user_uuid'
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(function (Builder $builder) {
            // $builder->where(['status' => true]);
        });
    }
}
