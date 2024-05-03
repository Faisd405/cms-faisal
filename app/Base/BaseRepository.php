<?php

namespace App\Base;

use App\Base\Construct\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    protected bool $withTrashed = false;

    protected bool $withOnlyTrashed = false;

    protected string|array $with = [];

    protected string|array $withCount = [];

    protected string|array $searchable = [];

    protected string|array $filterable = [];

    protected string|array $sortable = 'id:asc';

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    // Start BaseCore CRUD

    public function getAll(array $params = [], bool $withPaginate = true)
    {
        $model = $this->model->query();

        if (!empty($this->with) || !empty($params['with'])) {
            $model->with(array_merge($this->with, $params['with'] ?? []));
        }

        if (!empty($this->withCount) || !empty($params['withCount'])) {
            $model->withCount(array_merge($this->withCount, $params['withCount'] ?? []));
        }

        if (!empty($params['filter'])) {
            $model->where(function ($query) use ($params) {
                foreach ($this->filterable as $filterable) {
                    $query->orWhere($filterable, $params['filter']);
                }
            });
        }

        if (!empty($params['search'])) {
            $model->where(function ($query) use ($params) {
                foreach ($this->searchable as $searchable) {
                    $query->orWhere($searchable, 'like', '%' . $params['search'] . '%');
                }
            });
        }

        $sortParams = !empty($params['sort']) ? $params['sort'] : $this->sortable;

        if (is_array($sortParams)) {
            foreach ($sortParams as $sort) {
                [$column, $direction] = explode(':', $sort);
                $model->orderBy($column, $direction);
            }
        } else {
            [$column, $direction] = explode(':', $sortParams);
            $model->orderBy($column, $direction);
        }

        if ($this->withTrashed) {
            $model->withTrashed();
        }

        if ($this->withOnlyTrashed) {
            $model->onlyTrashed();
        }

        if ($withPaginate) {
            return $model->paginate($params['limit'] ?? 15);
        }

        return $model->get();
    }

    public function find(int $id, array $params = [])
    {
        $model = $this->model->query();

        if (!empty($this->with) || !empty($params['with'])) {
            $model->with(array_merge($this->with, $params['with'] ?? []));
        }

        if (!empty($this->withCount) || !empty($params['withCount'])) {
            $model->withCount(array_merge($this->withCount, $params['withCount'] ?? []));
        }

        return $model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $model = $this->model->find($id);

        if (empty($model)) {
            return null;
        }

        return $model->update($data);
    }

    public function delete(int $id)
    {
        $model = $this->model->find($id);

        if (empty($model)) {
            return null;
        }

        return $model->delete();
    }

    // End BaseCore CRUD
}
