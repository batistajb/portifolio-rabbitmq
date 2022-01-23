<?php

namespace App\Repositories;

use App\Models\Follow;
use App\Repositories\Contracts\FollowRepositoryContract;

class FollowRepository extends BaseRepository implements FollowRepositoryContract
{
    /**
     * @param Follow $model
     */
    public function __construct(Follow $model)
    {
        parent::__construct($model);
    }

    public function followRecover(array $data)
    {
        return $this->model->where($data)->first();
    }
}
