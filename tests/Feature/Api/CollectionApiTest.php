<?php

namespace Tests\Feature\Api;

use App\Models\Collection\CollectionSection;
use App\Models\Collection\CollectionPost;
use App\Models\Datamaster\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CollectionApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected Language $defaultLanguage;
    protected CollectionSection $section;

    protected function setUp(): void
    {
        parent::setUp();

        $this->defaultLanguage = Language::factory()->create([
            'iso_code' => 'en',
            'name' => 'English',
            'is_default' => true,
        ]);

        $this->section = CollectionSection::factory()->create([
            'slug' => 'news'
        ]);
    }

    public function test_can_get_sections()
    {
        CollectionSection::factory()->count(5)->create();

        $response = $this->getJson('/api/collection/sections');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'title',
                                'slug',
                                'description',
                                'created_at',
                                'updated_at'
                            ]
                        ]
                    ]
                ]);
    }

    public function test_can_get_section_by_slug()
    {
        $response = $this->getJson('/api/collection/sections/news');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'slug' => 'news'
                    ]
                ]);
    }

    public function test_can_get_posts_in_section()
    {
        CollectionPost::factory()->count(10)->create([
            'section_id' => $this->section->id
        ]);

        $response = $this->getJson('/api/collection/sections/news/posts');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'title',
                                'slug',
                                'excerpt',
                                'status',
                                'locale',
                                'published_at',
                                'created_at',
                                'updated_at'
                            ]
                        ]
                    ],
                    'meta' => [
                        'section_slug',
                        'locale'
                    ]
                ]);
    }

    public function test_can_get_specific_post()
    {
        $post = CollectionPost::factory()->create([
            'section_id' => $this->section->id,
            'slug' => 'test-post'
        ]);

        $response = $this->getJson('/api/collection/sections/news/posts/test-post');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'slug' => 'test-post'
                    ]
                ]);
    }

    public function test_can_get_localized_post()
    {
        $post = CollectionPost::factory()->create([
            'section_id' => $this->section->id,
            'slug' => 'test-post'
        ]);

        $response = $this->getJson('/api/collection/sections/news/posts/test-post?locale=en');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'locale' => [
                            'iso_code' => 'en'
                        ]
                    ]
                ]);
    }

    public function test_returns_404_for_nonexistent_section()
    {
        $response = $this->getJson('/api/collection/sections/nonexistent');

        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Section not found'
                ]);
    }

    public function test_returns_404_for_nonexistent_post()
    {
        $response = $this->getJson('/api/collection/sections/news/posts/nonexistent');

        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Post not found'
                ]);
    }

    public function test_fallback_to_default_locale_for_posts()
    {
        $secondaryLanguage = Language::factory()->create([
            'iso_code' => 'id',
            'is_default' => false
        ]);

        $post = CollectionPost::factory()->create([
            'section_id' => $this->section->id,
            'slug' => 'test-post'
        ]);

        // Request in secondary language that doesn't have content
        $response = $this->getJson('/api/collection/sections/news/posts/test-post?locale=id');

        $response->assertStatus(200)
                ->assertJsonPath('meta.fallback_used', true);
    }
}
