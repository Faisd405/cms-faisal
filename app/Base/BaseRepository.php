<?php

namespace App\Base;

use App\Base\Construct\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;
    protected bool $withTrashed = false;
    protected bool $withOnlyTrashed = false;
    protected array $with = [];
    protected array $withCount = [];
    protected array $searchable = [];
    protected array $filterable = [];
    protected array $selectable = [];
    protected array $appendable = [];
    protected string $sortable = 'id:asc';

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    // Start BaseCore CRUD
    public function getAll(array $params = [], bool $withPaginate = true)
    {
        $model = $this->prepareQuery($params);

        return $withPaginate
            ? $model->paginate($params['limit'] ?? 15)
            : $model->get();
    }

    public function find(int $id, array $params = [])
    {
        return $this->prepareQuery($params)->find($id);
    }

    public function getByWhere(array $where, array $params = [])
    {
        $model = $this->prepareQuery($params)->where($where);
        return $model->get();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $model = $this->model->find($id);

        if ($model) {
            $model->update($data);
            return $model;
        }

        return null;
    }

    public function delete(int $id)
    {
        $model = $this->model->find($id);

        if ($model) {
            return $model->delete();
        }

        return null;
    }

    public function restore(int $id)
    {
        $model = $this->model->withTrashed()->find($id);

        if ($model) {
            return $model->restore();
        }

        return null;
    }

    public function forceDelete(int $id)
    {
        $model = $this->model->withTrashed()->find($id);

        if ($model) {
            return $model->forceDelete();
        }

        return null;
    }

    // End BaseCore CRUD

    protected function prepareQuery(array $params = [])
    {
        $model = $this->model->query();

        if (!empty($this->with) || !empty($params['with'])) {
            $model->with(array_merge($this->with, $params['with'] ?? []));
        }

        if (!empty($this->withCount) || !empty($params['withCount'])) {
            $model->withCount(array_merge($this->withCount, $params['withCount'] ?? []));
        }

        if (!empty($params['filter'])) {
            $this->applyFilters($model, $params['filter']);
        }

        if (!empty($params['search'])) {
            $this->applySearch($model, $params['search']);
        }

        if (!empty($params['select']) && array_intersect($params['select'], $this->selectable)) {
            $model->select($params['select']);
        }

        $this->applySorting($model, $params['sort'] ?? $this->sortable);

        if ($this->withTrashed) {
            $model->withTrashed();
        }

        if ($this->withOnlyTrashed) {
            $model->onlyTrashed();
        }

        return $model;
    }

    public function appendCollection(array $data)
    {
        foreach ($this->appendable as $appendable) {
            $data[$appendable] = $this->model->{$appendable};
        }

        return $data;
    }

    protected function applyFilters($model, $filter)
    {
        $model->where(function ($query) use ($filter) {
            foreach ($this->filterable as $filterable) {
                $query->orWhere($filterable, $filter);
            }
        });
    }

    protected function applySearch($model, $search)
    {
        $model->where(function ($query) use ($search) {
            foreach ($this->searchable as $searchable) {
                $query->orWhere($searchable, 'like', '%' . $search . '%');
            }
        });
    }

    protected function applySorting($model, $sortParams)
    {
        if (is_array($sortParams)) {
            foreach ($sortParams as $sort) {
                [$column, $direction] = explode(':', $sort);
                $model->orderBy($column, $direction);
            }
        } else {
            [$column, $direction] = explode(':', $sortParams);
            $model->orderBy($column, $direction);
        }
    }
}
