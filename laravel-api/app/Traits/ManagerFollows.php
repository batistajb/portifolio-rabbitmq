<?php

namespace App\Traits;

use App\Models\Follow;
use App\Models\Follower;
use App\Models\User;
use Bschmitt\Amqp\Amqp;
use Bschmitt\Amqp\Message;
use Exception;

trait ManagerFollows
{
    /**
     *  Setup model event hooks.
     */
    protected static function boot()
    {
        parent::boot();
        static::created(function (Follow $follow) {
            self::saveFollower($follow);
            self::sendRabbitmq($follow, 'created');
        });
        static::deleted(function (Follow $follow) {
            self::deleteFollower($follow);
            self::sendRabbitmq($follow, 'deleted');
        });
    }

    private static function deleteFollower(Follow $follow)
    {
        $data['user_uuid'] = $follow->user_uuid_follow;
        $data['user_uuid_follower'] = $follow->user_uuid;
        $follower = Follower::where($data)->first();
        $follower->delete();
    }

    private static function saveFollower(Follow $follow)
    {
        $user = User::where('uuid', $follow->user_uuid)->first();
        $follower = new Follower();
        $follower->user_name_follower = $user->name;
        $follower->user_uuid_follower = $user->uuid;
        $follower->user_uuid = $follow->user_uuid_follow;
        $follower->save();
    }

    private static function sendRabbitmq(Follow $follow, string $operation)
    {
        $routeKey ='model.follow.' . $operation;
        logger($follow->toJson());
        try {
            $message = new Message($follow->toJson());
            (new Amqp)->publish($routeKey, $message);
        } catch (Exception $exception) {
            self::reportException([
                'exception' => $exception,
                'id' => $follow->id,
                'operation' => $operation
            ]);
        }
    }

    private static function reportException(array $params)
    {
        list(
            'exception' => $exception,
            'id' => $id,
            'operation' => $operation
            ) = $params;
        $myException = new Exception("The resource: follow with id: $id not synced on $operation", 0, $exception);
        report($myException);
    }
}
