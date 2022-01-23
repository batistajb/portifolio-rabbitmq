<?php

namespace App\Observers;

use App\Models\Post;
use Bschmitt\Amqp\Message;
use Bschmitt\Amqp\Amqp;
use Exception;

class PostObserver
{
    const MODEL_NAME = "Post";

    public function created(Post $post)
    {
        $post->id = $post->uuid;
        try {
            $message = new Message($post->toJson());
            (new Amqp)->publish('model.post.created', $message);
        } catch (Exception $exception) {
            $this->reportException([
               'exception' => $exception,
               'uuid' => $post->uuid,
               'operation' => 'created'
            ]);
        }
    }

    public function updated(Post $post)
    {
        $post->id = $post->uuid;
        $message = new Message($post->toJson());
        try {
            (new Amqp)->publish('model.post.updated', $message);
        } catch (Exception $exception) {
            $this->reportException([
               'exception' => $exception,
               'uuid' => $post->uuid,
               'operation' => 'updated'
            ]);
        }
    }

    public function deleted(Post $post)
    {
        $message = new Message(json_encode(['uuid' => $post->uuid]));
        try {
            (new Amqp)->publish('model.post.deleted', $message);
        } catch (Exception $exception) {
            $this->reportException([
                'exception' => $exception,
                'uuid' => $post->uuid,
                'operation' => 'deleted'
            ]);
        }
    }

    protected function reportException(array $params)
    {
        list(
            'exception' => $exception,
            'uuid' => $uuid,
            'operation' => $operation
        ) = $params;
        $myException = new Exception("The resource:" . self::MODEL_NAME . " with UUID: $uuid not synced on $operation", 0, $exception);
        report($myException);
    }
}
