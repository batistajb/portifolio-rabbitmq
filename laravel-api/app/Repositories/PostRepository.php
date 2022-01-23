<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\User;
use App\Repositories\Contracts\PostRepositoryContract;

class PostRepository extends BaseRepository implements PostRepositoryContract
{
    public function __construct(Post $model)
    {
        parent::__construct($model);
    }

    public function getByFilter(array $params): object
    {
        return $this->model->where($params)->get();
    }

    public function getCountPostsToday(string $userUuid)
    {
        return $this->model
            ->where('user_id', $userUuid)
            ->where('created_at', '<=', now())
            ->count();
    }
}
