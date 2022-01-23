<?php

namespace App\Repositories;

use App\Models\Follower;
use App\Repositories\Contracts\PostRepositoryContract;

class FollowerRepository extends BaseRepository implements PostRepositoryContract
{
    /**
     * @param Follower $model
     */
    public function __construct(Follower $model)
    {
        parent::__construct($model);
    }
}
