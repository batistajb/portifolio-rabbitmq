<?php


namespace App\Repositories\Contracts;

/**
 * Interface PostRepositoryContract
 *
 * @package App\Repositories\Contracts
 */
interface PostRepositoryContract
{
    public function getByFilter(array $params): object;

    public function getCountPostsToday(string $userUuid);
}
