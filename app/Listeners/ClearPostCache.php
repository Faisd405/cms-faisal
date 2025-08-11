<?php

namespace App\Listeners;

use App\Events\PostUpdated;
use App\Services\Cache\ApiCacheService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ClearPostCache implements ShouldQueue
{
    use InteractsWithQueue;

    protected ApiCacheService $cacheService;

    /**
     * Create the event listener.
     */
    public function __construct(ApiCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Handle the event.
     */
    public function handle(PostUpdated $event): void
    {
        $sectionSlug = $event->post->section->slug ?? null;
        $this->cacheService->clearPostCache($sectionSlug, $event->post->slug);
    }
}
