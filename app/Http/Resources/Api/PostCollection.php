<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($post) {
                return new PostResource($post);
            }),
            'pagination' => $this->when($this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator, [
                'current_page' => $this->currentPage(),
                'per_page' => $this->perPage(),
                'total' => $this->total(),
                'last_page' => $this->lastPage(),
                'from' => $this->firstItem(),
                'to' => $this->lastItem(),
            ]),
            'meta' => [
                'locale' => app()->getLocale(),
                'total_items' => $this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator ? $this->total() : $this->count(),
            ]
        ];
    }
}
