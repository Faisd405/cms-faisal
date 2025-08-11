<?php

namespace App\Services\Cache;

use Illuminate\Support\Facades\Cache;

class ApiCacheService
{
    /**
     * Clear all page-related cache
     */
    public function clearPageCache(string $slug = null): void
    {
        if ($slug) {
            // Clear specific page cache for all locales
            $this->clearCacheByPattern("api_page_{$slug}_*");
        } else {
            // Clear all pages cache
            $this->clearCacheByPattern('api_pages_*');
        }
    }

    /**
     * Clear all section-related cache
     */
    public function clearSectionCache(string $slug = null): void
    {
        if ($slug) {
            $this->clearCacheByPattern("api_section_{$slug}*");
        } else {
            $this->clearCacheByPattern('api_sections_*');
        }
    }

    /**
     * Clear all post-related cache
     */
    public function clearPostCache(string $sectionSlug = null, string $postSlug = null): void
    {
        if ($postSlug && $sectionSlug) {
            $this->clearCacheByPattern("api_post_{$sectionSlug}_{$postSlug}_*");
        } elseif ($sectionSlug) {
            $this->clearCacheByPattern("api_section_{$sectionSlug}_posts_*");
            $this->clearCacheByPattern("api_post_{$sectionSlug}_*");
        } else {
            $this->clearCacheByPattern('api_*_posts_*');
            $this->clearCacheByPattern('api_post_*');
        }
    }

    /**
     * Clear language cache
     */
    public function clearLanguageCache(): void
    {
        $this->clearCacheByPattern('api_languages_*');
    }

    /**
     * Clear cache for specific locale
     */
    public function clearLocaleCache(string $localeCode): void
    {
        $this->clearCacheByPattern("*_{$localeCode}_*");
    }

    /**
     * Clear all API cache
     */
    public function clearAllApiCache(): void
    {
        $this->clearCacheByPattern('api_*');
    }

    /**
     * Clear cache by pattern (simplified for compatibility)
     */
    private function clearCacheByPattern(string $pattern): void
    {
        // For most cache drivers (file, database), we can't delete by pattern
        // So we'll maintain a simple approach and just flush all cache
        // This is acceptable for most use cases and ensures compatibility

        try {
            Cache::flush();
            \Log::info('API cache cleared', ['pattern' => $pattern]);
        } catch (\Exception $e) {
            \Log::error('Failed to clear cache', [
                'pattern' => $pattern,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Warm up cache for a specific page
     */
    public function warmPageCache(string $slug, array $locales = []): void
    {
        // Implementation would depend on your specific warming strategy
        // This is a placeholder for future enhancement
    }

    /**
     * Get cache statistics
     */
    public function getCacheStats(): array
    {
        // This would require a cache driver that supports statistics
        // Like Redis with specific commands
        return [
            'total_keys' => 0,
            'memory_usage' => 0,
            'hit_rate' => 0,
        ];
    }

    /**
     * Clear specific cache key
     */
    public function clearKey(string $key): void
    {
        Cache::forget($key);
    }

    /**
     * Clear multiple specific cache keys
     */
    public function clearKeys(array $keys): void
    {
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}
