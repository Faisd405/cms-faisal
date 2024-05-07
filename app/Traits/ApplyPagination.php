<?php

namespace App\Traits;

/**
 * Class ApplyPagination
 *
 * @method applyPagination($pipeline, $request, $withPaginate)
 */
trait ApplyPagination
{
    /**
     * Apply pagination to the pipeline.
     *
     * @param mixed $pipeline
     * @param array $request
     * @param bool $withPaginate
     */
    public function applyPagination($pipeline, $request, $withPaginate)
    {
        return $withPaginate
            ? $pipeline->paginate($request['limit'] ?? 10, ['*'], 'page', $request['page'] ?? 1)
            : $pipeline->get();
    }
}
