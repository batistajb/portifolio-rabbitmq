<?php


namespace App\Services\Contracts;


use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface FollowServiceContract
 *
 * @package App\Services\Contracts
 */
interface FollowServiceContract
{
    public function followers(string $userUuid): Collection|array;
    public function follows(string $userUuid): Collection|array;
    public function follow(array $data): array;
    public function unfollow(array $data): array;
}
