<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page\Page;
use App\Models\Collection\CollectionSection;
use App\Models\Datamaster\Language;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\App;

class FrontendController extends Controller
{
    public function home(Request $request)
    {
        // Get the homepage (you can define this by slug or set a flag in pages table)
        $homepage = Page::where('slug', 'home')
            ->orWhere('slug', 'homepage')
            ->where('is_active', true)
            ->first();

        if (!$homepage) {
            // If no homepage is found, get the first active page
            $homepage = Page::where('is_active', true)
                ->orderBy('created_at')
                ->first();
        }

        if (!$homepage) {
            return $this->renderWelcomePage();
        }

        $currentLanguage = $this->getCurrentLanguage();
        
        $homepage->loadMissing(['contentType.fields', 'contentValue' => function($query) use ($currentLanguage) {
            $query->where('localization_id', $currentLanguage->id ?? null);
        }, 'pageMetas']);

        return Inertia::render('Frontend/Home', [
            'page' => $homepage,
            'content' => $homepage->value,
            'seoMeta' => $homepage->pageMetas,
            'language' => $currentLanguage,
            'availableLanguages' => Language::where('is_active', true)->get(),
        ]);
    }

    public function page(Request $request, $slug)
    {
        $currentLanguage = $this->getCurrentLanguage();
        
        $page = Page::where('slug', $slug)
            ->where('is_active', true)
            ->with(['contentType.fields', 'contentValue' => function($query) use ($currentLanguage) {
                $query->where('localization_id', $currentLanguage->id ?? null);
            }, 'pageMetas'])
            ->first();

        if (!$page) {
            abort(404);
        }

        // Determine template based on page template or content type
        $template = $page->template ?: 'Default';

        return Inertia::render("Frontend/Templates/{$template}", [
            'page' => $page,
            'content' => $page->value,
            'seoMeta' => $page->pageMetas,
            'language' => $currentLanguage,
            'availableLanguages' => Language::where('is_active', true)->get(),
        ]);
    }

    public function collection(Request $request, $sectionSlug)
    {
        $currentLanguage = $this->getCurrentLanguage();
        
        $section = CollectionSection::where('slug', $sectionSlug)
            ->with(['postContentType.fields', 'posts' => function($query) use ($currentLanguage) {
                $query->where('is_active', true)
                    ->with(['contentValue' => function($subQuery) use ($currentLanguage) {
                        $subQuery->where('localization_id', $currentLanguage->id ?? null);
                    }])
                    ->orderBy('published_at', 'desc')
                    ->orderBy('created_at', 'desc');
            }])
            ->first();

        if (!$section) {
            abort(404);
        }

        // Add content values to posts
        foreach ($section->posts as $post) {
            $post->content = $post->value;
        }

        return Inertia::render('Frontend/Collection/Index', [
            'section' => $section,
            'posts' => $section->posts,
            'language' => $currentLanguage,
            'availableLanguages' => Language::where('is_active', true)->get(),
        ]);
    }

    public function post(Request $request, $sectionSlug, $postSlug)
    {
        $currentLanguage = $this->getCurrentLanguage();
        
        $section = CollectionSection::where('slug', $sectionSlug)->first();
        
        if (!$section) {
            abort(404);
        }

        $post = $section->posts()
            ->where('slug', $postSlug)
            ->where('is_active', true)
            ->with(['contentType.fields', 'contentValue' => function($query) use ($currentLanguage) {
                $query->where('localization_id', $currentLanguage->id ?? null);
            }])
            ->first();

        if (!$post) {
            abort(404);
        }

        // Get related posts (optional)
        $relatedPosts = $section->posts()
            ->where('id', '!=', $post->id)
            ->where('is_active', true)
            ->with(['contentValue' => function($query) use ($currentLanguage) {
                $query->where('localization_id', $currentLanguage->id ?? null);
            }])
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        foreach ($relatedPosts as $relatedPost) {
            $relatedPost->content = $relatedPost->value;
        }

        return Inertia::render('Frontend/Collection/Show', [
            'section' => $section,
            'post' => $post,
            'content' => $post->value,
            'relatedPosts' => $relatedPosts,
            'language' => $currentLanguage,
            'availableLanguages' => Language::where('is_active', true)->get(),
        ]);
    }

    public function switchLanguage(Request $request, $languageCode)
    {
        $language = Language::where('code', $languageCode)
            ->where('is_active', true)
            ->first();

        if (!$language) {
            abort(404);
        }

        session(['current_language' => $language->code]);
        App::setLocale($language->code);

        return redirect()->back();
    }

    protected function getCurrentLanguage()
    {
        $languageCode = session('current_language') ?? config('app.locale', 'en');
        
        return Language::where('code', $languageCode)
            ->where('is_active', true)
            ->first() ?? Language::where('is_default', true)->first();
    }

    protected function renderWelcomePage()
    {
        return Inertia::render('Frontend/Welcome', [
            'message' => 'Welcome to our CMS! Please create some pages in the admin panel.',
            'language' => $this->getCurrentLanguage(),
            'availableLanguages' => Language::where('is_active', true)->get(),
        ]);
    }
}