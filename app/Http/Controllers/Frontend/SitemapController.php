<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page\Page;
use App\Models\Collection\CollectionSection;
use App\Models\Collection\CollectionPost;
use App\Models\Datamaster\Language;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $languages = Language::where('is_active', true)->get();
        
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">';
        
        // Add homepage
        foreach ($languages as $language) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . url('/') . '</loc>';
            $sitemap .= '<lastmod>' . now()->toISOString() . '</lastmod>';
            $sitemap .= '<changefreq>daily</changefreq>';
            $sitemap .= '<priority>1.0</priority>';
            
            // Add alternate language versions
            foreach ($languages as $altLang) {
                if ($altLang->id !== $language->id) {
                    $sitemap .= '<xhtml:link rel="alternate" hreflang="' . $altLang->code . '" href="' . url('/?lang=' . $altLang->code) . '" />';
                }
            }
            
            $sitemap .= '</url>';
            break; // Only add homepage once
        }
        
        // Add pages
        $pages = Page::where('is_active', true)->get();
        foreach ($pages as $page) {
            foreach ($languages as $language) {
                $sitemap .= '<url>';
                $sitemap .= '<loc>' . url('/' . $page->slug . '?lang=' . $language->code) . '</loc>';
                $sitemap .= '<lastmod>' . $page->updated_at->toISOString() . '</lastmod>';
                $sitemap .= '<changefreq>weekly</changefreq>';
                $sitemap .= '<priority>0.8</priority>';
                
                // Add alternate language versions
                foreach ($languages as $altLang) {
                    if ($altLang->id !== $language->id) {
                        $sitemap .= '<xhtml:link rel="alternate" hreflang="' . $altLang->code . '" href="' . url('/' . $page->slug . '?lang=' . $altLang->code) . '" />';
                    }
                }
                
                $sitemap .= '</url>';
            }
        }
        
        // Add collection sections
        $sections = CollectionSection::all();
        foreach ($sections as $section) {
            foreach ($languages as $language) {
                $sitemap .= '<url>';
                $sitemap .= '<loc>' . url('/collection/' . $section->slug . '?lang=' . $language->code) . '</loc>';
                $sitemap .= '<lastmod>' . $section->updated_at->toISOString() . '</lastmod>';
                $sitemap .= '<changefreq>weekly</changefreq>';
                $sitemap .= '<priority>0.7</priority>';
                
                // Add alternate language versions
                foreach ($languages as $altLang) {
                    if ($altLang->id !== $language->id) {
                        $sitemap .= '<xhtml:link rel="alternate" hreflang="' . $altLang->code . '" href="' . url('/collection/' . $section->slug . '?lang=' . $altLang->code) . '" />';
                    }
                }
                
                $sitemap .= '</url>';
            }
            
            // Add posts from this section
            $posts = $section->posts()->where('is_active', true)->get();
            foreach ($posts as $post) {
                foreach ($languages as $language) {
                    $sitemap .= '<url>';
                    $sitemap .= '<loc>' . url('/collection/' . $section->slug . '/' . $post->slug . '?lang=' . $language->code) . '</loc>';
                    $sitemap .= '<lastmod>' . $post->updated_at->toISOString() . '</lastmod>';
                    $sitemap .= '<changefreq>monthly</changefreq>';
                    $sitemap .= '<priority>0.6</priority>';
                    
                    // Add alternate language versions
                    foreach ($languages as $altLang) {
                        if ($altLang->id !== $language->id) {
                            $sitemap .= '<xhtml:link rel="alternate" hreflang="' . $altLang->code . '" href="' . url('/collection/' . $section->slug . '/' . $post->slug . '?lang=' . $altLang->code) . '" />';
                        }
                    }
                    
                    $sitemap .= '</url>';
                }
            }
        }
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200, [
            'Content-Type' => 'application/xml',
            'Cache-Control' => 'public, max-age=3600'
        ]);
    }
    
    public function robots()
    {
        $robots = "User-agent: *\n";
        $robots .= "Allow: /\n";
        $robots .= "Disallow: /admin/\n";
        $robots .= "Disallow: /dashboard/\n";
        $robots .= "\n";
        $robots .= "Sitemap: " . url('/sitemap.xml') . "\n";
        
        return response($robots, 200, [
            'Content-Type' => 'text/plain',
            'Cache-Control' => 'public, max-age=86400'
        ]);
    }
}