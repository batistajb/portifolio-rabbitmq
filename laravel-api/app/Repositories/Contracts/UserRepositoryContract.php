<?php


namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

/**
 * Interface PostRepositoryContract
 *
 * @package App\Repositories\Contracts
 */
interface UserRepositoryContract
{
    public function follows(string $userUuid): Collection|array;
    public function followers(string $userUuid): Collection|array;
}
