<?php

namespace App\Listeners;

use App\Events\PageUpdated;
use App\Services\Cache\ApiCacheService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ClearPageCache implements ShouldQueue
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
    public function handle(PageUpdated $event): void
    {
        $this->cacheService->clearPageCache($event->page->slug);
    }
}
