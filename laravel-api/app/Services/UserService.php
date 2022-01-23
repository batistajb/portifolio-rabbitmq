<?php


namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Contracts\UserServiceContract;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class to Services of Users
 *
 * @package App\Services
 */
class UserService implements UserServiceContract
{

    private UserRepository $repositoryContract;

    public function __construct(UserRepository $repositoryContract)
    {
        $this->repositoryContract = $repositoryContract;
    }

    public function getByUuid(string $uuid): object|array
    {
        return  $this->repositoryContract->getByUuid($uuid);
    }

    public function getAll(): Collection
    {
        return  $this->repositoryContract->all();
    }
}
