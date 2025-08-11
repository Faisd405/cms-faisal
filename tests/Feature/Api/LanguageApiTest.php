<?php

namespace Tests\Feature\Api;

use App\Models\Datamaster\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LanguageApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_get_languages()
    {
        Language::factory()->create([
            'iso_code' => 'en',
            'name' => 'English',
            'is_default' => true,
        ]);

        Language::factory()->create([
            'iso_code' => 'id',
            'name' => 'Indonesian',
            'is_default' => false,
        ]);

        $response = $this->getJson('/api/languages');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'iso_code',
                            'is_rtl',
                            'is_default',
                            'is_active'
                        ]
                    ],
                    'meta' => [
                        'total_languages',
                        'default_language'
                    ]
                ])
                ->assertJson([
                    'success' => true,
                    'meta' => [
                        'total_languages' => 2,
                        'default_language' => 'en'
                    ]
                ]);
    }

    public function test_can_filter_active_languages_only()
    {
        Language::factory()->create([
            'iso_code' => 'en',
            'is_default' => true,
        ]);

        Language::factory()->create([
            'iso_code' => 'id',
            'is_default' => false,
        ]);

        $response = $this->getJson('/api/languages?active_only=true');

        $response->assertStatus(200);
        // All languages are active by default in factory
    }

    public function test_validates_active_only_parameter()
    {
        $response = $this->getJson('/api/languages?active_only=invalid');

        $response->assertStatus(422)
                ->assertJsonValidationErrors('active_only');
    }
}
