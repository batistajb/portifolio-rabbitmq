<?php


namespace App\Services\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface UserServiceContract
 *
 * @package App\Services\Contracts
 */
interface UserServiceContract
{

    /**
     * Find user
     *
     * @param string $uuid
     * @return object|array
     */
    public function getByUuid(string $uuid): object|array;

    /**
     * Find all users
     *
     * @return Collection
     */
    public function getAll(): Collection;
}
