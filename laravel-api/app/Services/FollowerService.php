<?php


namespace App\Services;

use App\Models\Follower;
use App\Repositories\FollowerRepository;
use App\Services\Contracts\FollowerServiceContract;

/**
 * Class to Services of Follower
 *
 * @package App\Services
 */
class FollowerService implements FollowerServiceContract
{

    private FollowerRepository $repositoryContract;

    public function __construct(FollowerRepository $repositoryContract)
    {
        $this->repositoryContract = $repositoryContract;
    }

}
