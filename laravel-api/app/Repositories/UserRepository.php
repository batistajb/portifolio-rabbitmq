<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryContract;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository implements UserRepositoryContract
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function follows(string $userUuid): Collection|array
    {
        $user = $this->model->find($userUuid);
        return $user->follows ?? [];
    }

    public function followers(string $userUuid): Collection|array
    {
        $user = $this->model->find($userUuid);
        return $user->followers ?? [];
    }
}
