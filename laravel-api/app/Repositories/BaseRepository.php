<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    public Model $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getByUuid(string $uuid)
    {
        return $this->model->where([ "uuid" => $uuid ])->first()??[];
    }

    public function getById(int $id)
    {
        // TODO: Implement getById() method.
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function getByAttribute(string $field, string $attribute)
    {
        // TODO: Implement getByAttribute() method.
    }

    public function store(array $data): Model
    {
        return $this->model->create($data);
    }

    public function updateByUuid(array $data, string $uuid): Model
    {
        return $this->model->updateOrCreate(["uuid" => $uuid], $data);
    }

    public function updateById(array $data, int $id)
    {
        // TODO: Implement updateById() method.
    }

    public function delete(string $uuid): bool
    {
        $model = $this->getByUuid($uuid);
        return $model ? $model->delete() : false;
    }

    public function getWithRelation(string $relation)
    {
        // TODO: Implement getWithRelation() method.
    }

    public function getByIdWithoutGlobalScopes(int $id)
    {
        // TODO: Implement getByIdWithoutGlobalScopes() method.
    }
}
