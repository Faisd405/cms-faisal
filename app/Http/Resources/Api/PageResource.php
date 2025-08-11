<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->when(isset($this->content), $this->content),
            'status' => $this->status,
            'meta' => [
                'seo_title' => $this->seo_title,
                'seo_description' => $this->seo_description,
                'seo_keywords' => $this->seo_keywords,
            ],
            'content_type' => $this->whenLoaded('contentType', function () {
                return [
                    'id' => $this->contentType->id,
                    'name' => $this->contentType->name,
                    'slug' => $this->contentType->slug,
                ];
            }),
            'locale' => [
                'iso_code' => app()->getLocale(),
            ],
            'published_at' => $this->published_at?->toISOString(),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
