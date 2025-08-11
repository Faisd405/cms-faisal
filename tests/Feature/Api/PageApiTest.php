<?php

namespace Tests\Feature\Api;

use App\Models\Datamaster\Language;
use App\Models\Page\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PageApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected Language $defaultLanguage;
    protected Language $secondaryLanguage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->defaultLanguage = Language::factory()->create([
            'iso_code' => 'en',
            'name' => 'English',
            'is_default' => true,
        ]);

        $this->secondaryLanguage = Language::factory()->create([
            'iso_code' => 'id',
            'name' => 'Indonesian',
            'is_default' => false,
        ]);
    }

    public function test_can_get_paginated_pages()
    {
        Page::factory()->count(25)->create();

        $response = $this->getJson('/api/pages');

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
                                'meta',
                                'locale',
                                'published_at',
                                'created_at',
                                'updated_at'
                            ]
                        ],
                        'pagination' => [
                            'current_page',
                            'per_page',
                            'total',
                            'last_page'
                        ]
                    ]
                ]);
    }

    public function test_can_get_page_by_slug()
    {
        $page = Page::factory()->create(['slug' => 'test-page']);

        $response = $this->getJson('/api/pages/test-page');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'title',
                        'slug',
                        'status',
                        'meta',
                        'locale'
                    ]
                ])
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'slug' => 'test-page'
                    ]
                ]);
    }

    public function test_returns_404_for_nonexistent_page()
    {
        $response = $this->getJson('/api/pages/nonexistent-page');

        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Page not found'
                ]);
    }

    public function test_can_get_localized_page()
    {
        $page = Page::factory()->create(['slug' => 'test-page']);

        $response = $this->getJson('/api/pages/test-page?locale=en');

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

    public function test_validates_locale_parameter()
    {
        Page::factory()->create(['slug' => 'test-page']);

        $response = $this->getJson('/api/pages/test-page?locale=invalid');

        $response->assertStatus(400)
                ->assertJson([
                    'success' => false,
                    'message' => 'Invalid locale provided'
                ]);
    }

    public function test_can_search_pages()
    {
        Page::factory()->create(['title' => 'Laravel Tutorial']);
        Page::factory()->create(['title' => 'PHP Guide']);

        $response = $this->getJson('/api/pages?search=Laravel');

        $response->assertStatus(200);
        // Additional assertions would depend on search implementation
    }

    public function test_can_limit_page_fields()
    {
        Page::factory()->create(['slug' => 'test-page']);

        $response = $this->getJson('/api/pages/test-page?fields=id,title,slug');

        $response->assertStatus(200);
        // Test that only requested fields are returned
    }

    public function test_respects_per_page_limit()
    {
        Page::factory()->count(50)->create();

        $response = $this->getJson('/api/pages?per_page=5');

        $response->assertStatus(200)
                ->assertJsonPath('data.pagination.per_page', 5);
    }

    public function test_validates_per_page_maximum()
    {
        $response = $this->getJson('/api/pages?per_page=150');

        $response->assertStatus(422)
                ->assertJsonValidationErrors('per_page');
    }
}
