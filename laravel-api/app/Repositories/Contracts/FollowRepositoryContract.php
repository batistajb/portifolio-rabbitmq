<?php


namespace App\Repositories\Contracts;

use App\Models\Follow;

/**
 * Interface PostRepositoryContract
 *
 * @package App\Repositories\Contracts
 */
interface FollowRepositoryContract
{
    public function followRecover(array $data);
}
