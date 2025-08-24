<?php

namespace App\Services;

use App\Models\SeoMeta;
use Illuminate\Support\Facades\Config;

class SeoService
{
    public static function generateMeta($seoable = null, $fallbackTitle = null, $fallbackDescription = null)
    {
        $seoMeta = null;
        
        if ($seoable && $seoable->pageMetas) {
            $seoMeta = $seoable->pageMetas;
        }

        $title = $seoMeta?->title ?: $fallbackTitle ?: config('app.name');
        $description = $seoMeta?->description ?: $fallbackDescription ?: config('app.description', 'Welcome to our website');
        $keywords = $seoMeta?->keywords ?: config('app.keywords', '');
        $image = $seoMeta?->image ?: config('app.default_og_image', '');
        
        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'image' => $image,
            'url' => request()->url(),
            'type' => 'website',
            'site_name' => config('app.name'),
        ];
    }

    public static function getStructuredData($page = null, $post = null)
    {
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('app.name'),
            'url' => config('app.url'),
        ];

        if ($post) {
            $structuredData = [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $post->title,
                'description' => $post->excerpt ?? '',
                'url' => request()->url(),
                'datePublished' => $post->published_at?->toISOString(),
                'dateModified' => $post->updated_at->toISOString(),
                'author' => [
                    '@type' => 'Organization',
                    'name' => config('app.name')
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => config('app.name'),
                    'url' => config('app.url')
                ]
            ];
        } elseif ($page) {
            $structuredData = [
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                'name' => $page->title,
                'description' => $page->pageMetas?->description ?? '',
                'url' => request()->url(),
                'isPartOf' => [
                    '@type' => 'WebSite',
                    'name' => config('app.name'),
                    'url' => config('app.url')
                ]
            ];
        }

        return $structuredData;
    }
}