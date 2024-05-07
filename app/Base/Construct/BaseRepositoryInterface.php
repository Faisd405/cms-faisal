<?php

namespace App\Base\Construct;

interface BaseRepositoryInterface
{
    public function getAll(array $params, bool $withPaginate);

    public function find(int $id, array $params = []);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function restore(int $id);

    public function forceDelete(int $id);
}
