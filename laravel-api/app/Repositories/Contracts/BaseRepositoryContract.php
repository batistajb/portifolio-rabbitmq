<?php

namespace App\Repositories\Contracts;

interface BaseRepositoryContract
{
    public function getById(int $id);
    public function getByUuid(string $uuid);
    public function all();
    public function getByAttribute(string $field, string $attribute);
    public function store(array $data);
    public function updateByUuid(array $data, string $uuid);
    public function updateById(array $data, int $id);
    public function delete(string $uuid);
    public function getWithRelation(string $relation);
    public function getByIdWithoutGlobalScopes(int $id);
}
