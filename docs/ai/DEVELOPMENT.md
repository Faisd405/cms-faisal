# Development Guide - CMS Faisal

## Table of Contents

1. [Getting Started](#getting-started)
2. [Development Workflow](#development-workflow)
3. [Code Standards](#code-standards)
4. [Testing](#testing)
5. [Database Development](#database-development)
6. [API Development](#api-development)
7. [Frontend Development](#frontend-development)
8. [Security Guidelines](#security-guidelines)
9. [Performance Optimization](#performance-optimization)
10. [Debugging](#debugging)

## Getting Started

### Development Environment Setup

1. **Prerequisites Check**
   ```bash
   php --version    # Should be 8.2+
   composer --version
   node --version   # Should be 18+
   npm --version
   ```

2. **Local Development Tools**
   ```bash
   # Install Laravel Valet (macOS) or Laragon (Windows)
   # Or use built-in server
   php artisan serve --host=0.0.0.0 --port=8000
   ```

3. **IDE Configuration**
   - **VS Code**: Install PHP Intelephense, Laravel Extension Pack, Vue Language Features
   - **PHPStorm**: Enable Laravel, Vue.js, and Blade plugins

### Git Workflow

```bash
# Clone and setup
git clone https://github.com/Faisd405/cms-faisal.git
cd cms-faisal
git checkout develop

# Create feature branch
git checkout -b feature/content-type-improvements

# Work on changes...

# Commit with conventional commits
git add .
git commit -m "feat(content-types): add field validation rules"

# Push and create PR
git push origin feature/content-type-improvements
```

## Development Workflow

### Branch Strategy

```
main
├── develop
│   ├── feature/content-management
│   ├── feature/api-improvements
│   └── hotfix/security-patch
└── release/v1.2.0
```

**Branch Types:**
- `main` - Production-ready code
- `develop` - Integration branch for features
- `feature/*` - New features
- `hotfix/*` - Critical fixes
- `release/*` - Release preparation

### Commit Convention

Follow [Conventional Commits](https://www.conventionalcommits.org/):

```
type(scope): description

feat(auth): add two-factor authentication
fix(api): resolve pagination issue in pages endpoint
docs(readme): update installation instructions
style(components): format vue components
refactor(services): extract common pagination logic
test(unit): add content type service tests
chore(deps): update laravel to 11.x
```

### Development Commands

```bash
# Setup development environment
make dev-setup

# Start development servers
make dev-start

# Run tests
make test

# Code quality checks
make lint
make stan
make format

# Build assets
make build-dev
make build-prod
```

### Makefile

```makefile
# Makefile for CMS Faisal

.PHONY: help dev-setup dev-start test lint stan format build-dev build-prod

help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

dev-setup: ## Setup development environment
	composer install
	pnpm install
	cp .env.example .env
	php artisan key:generate
	php artisan migrate
	php artisan db:seed

dev-start: ## Start development servers
	php artisan serve &
	pnpm run dev

test: ## Run all tests
	php artisan test
	pnpm run test

lint: ## Run code linting
	./vendor/bin/pint --test
	pnpm run eslint

stan: ## Run static analysis
	./vendor/bin/phpstan analyse

format: ## Format code
	./vendor/bin/pint
	pnpm run eslint-fix

build-dev: ## Build assets for development
	pnpm run build

build-prod: ## Build assets for production
	pnpm run build --mode production
```

## Code Standards

### PHP Standards (PSR-12)

```php
<?php

declare(strict_types=1);

namespace App\Services\ContentType;

use App\Base\BaseService;
use App\Models\ContentType\ContentType;
use App\Repositories\ContentType\ContentTypeRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Content Type Service
 * 
 * Handles business logic for content type management
 */
final class ContentTypeService extends BaseService
{
    public function __construct(
        private readonly ContentTypeRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * Get content types with pagination and filters
     */
    public function getPaginated(
        array $filters = [],
        int $perPage = 15
    ): LengthAwarePaginator {
        return $this->repository->getPaginatedWithSearch($filters, $perPage);
    }

    /**
     * Create content type with validation
     */
    public function create(array $data): ContentType
    {
        $this->validateContentTypeData($data);
        
        return $this->repository->create(
            $this->prepareDataForCreate($data)
        );
    }

    private function validateContentTypeData(array $data): void
    {
        if (empty($data['name'])) {
            throw new \InvalidArgumentException('Content type name is required');
        }

        if ($this->repository->existsByName($data['name'])) {
            throw new \DomainException('Content type name already exists');
        }
    }
}
```

### Laravel Pint Configuration

```json
{
    "preset": "laravel",
    "rules": {
        "blank_line_before_statement": {
            "statements": ["break", "continue", "declare", "return", "throw", "try"]
        },
        "method_argument_space": {
            "on_multiline": "ensure_fully_multiline"
        },
        "no_unused_imports": true,
        "not_operator_with_successor_space": true,
        "trailing_comma_in_multiline": {
            "elements": ["arrays"]
        },
        "phpdoc_scalar": true,
        "unary_operator_spaces": true,
        "binary_operator_spaces": {
            "default": "single_space"
        }
    }
}
```

### JavaScript/Vue Standards

```javascript
// Use composition API with script setup
<script setup>
import { ref, computed, onMounted } from 'vue'
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import { contentTypeSchema } from '@/utils/validation'

// Props definition
const props = defineProps({
  contentType: {
    type: Object,
    default: () => ({})
  },
  isEditing: {
    type: Boolean,
    default: false
  }
})

// Emits definition
const emit = defineEmits(['submit', 'cancel'])

// Reactive state
const isLoading = ref(false)
const errors = ref({})

// Computed properties
const formTitle = computed(() => 
  props.isEditing ? 'Edit Content Type' : 'Create Content Type'
)

// Form handling
const { values, errors: formErrors, handleSubmit } = useForm({
  validationSchema: toTypedSchema(contentTypeSchema),
  initialValues: props.contentType
})

// Methods
const onSubmit = handleSubmit(async (values) => {
  isLoading.value = true
  try {
    emit('submit', values)
  } catch (error) {
    errors.value = error.response?.data?.errors || {}
  } finally {
    isLoading.value = false
  }
})

// Lifecycle
onMounted(() => {
  // Component mounted logic
})
</script>

<template>
  <form @submit="onSubmit" class="space-y-6">
    <!-- Form content -->
  </form>
</template>
```

### ESLint Configuration

```javascript
// .eslintrc.js
module.exports = {
  env: {
    browser: true,
    es2021: true,
    node: true
  },
  extends: [
    'eslint:recommended',
    '@vue/eslint-config-prettier',
    'plugin:vue/vue3-recommended'
  ],
  parserOptions: {
    ecmaVersion: 2021,
    sourceType: 'module'
  },
  rules: {
    'vue/multi-word-component-names': 'off',
    'vue/attribute-hyphenation': 'error',
    'vue/component-definition-name-casing': ['error', 'PascalCase'],
    'vue/html-closing-bracket-newline': ['error', {
      singleline: 'never',
      multiline: 'always'
    }],
    'vue/max-attributes-per-line': ['error', {
      singleline: 3,
      multiline: 1
    }],
    'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'warn',
    'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'warn'
  }
}
```

## Testing

### Test Structure

```
tests/
├── Feature/                 # Integration tests
│   ├── Api/
│   │   ├── PageApiTest.php
│   │   └── ContentTypeApiTest.php
│   ├── Auth/
│   │   └── LoginTest.php
│   └── Admin/
│       ├── ContentTypeTest.php
│       └── PageManagementTest.php
├── Unit/                    # Unit tests
│   ├── Services/
│   │   ├── ContentTypeServiceTest.php
│   │   └── PageServiceTest.php
│   ├── Models/
│   │   └── ContentTypeTest.php
│   └── Repositories/
│       └── ContentTypeRepositoryTest.php
└── TestCase.php            # Base test case
```

### Writing Tests

#### Unit Test Example

```php
<?php

namespace Tests\Unit\Services;

use App\Models\ContentType\ContentType;
use App\Repositories\ContentType\ContentTypeRepository;
use App\Services\ContentType\ContentTypeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ContentTypeServiceTest extends TestCase
{
    use RefreshDatabase;

    private ContentTypeService $service;
    private $mockRepository;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->mockRepository = Mockery::mock(ContentTypeRepository::class);
        $this->service = new ContentTypeService($this->mockRepository);
    }

    /** @test */
    public function it_can_create_content_type_with_valid_data(): void
    {
        // Arrange
        $data = [
            'name' => 'Test Content Type',
            'description' => 'Test description',
            'type' => 'page'
        ];

        $expectedContentType = new ContentType($data);
        
        $this->mockRepository
            ->shouldReceive('existsByName')
            ->with($data['name'])
            ->once()
            ->andReturn(false);
            
        $this->mockRepository
            ->shouldReceive('create')
            ->once()
            ->andReturn($expectedContentType);

        // Act
        $result = $this->service->create($data);

        // Assert
        $this->assertInstanceOf(ContentType::class, $result);
        $this->assertEquals($data['name'], $result->name);
    }

    /** @test */
    public function it_throws_exception_when_name_already_exists(): void
    {
        // Arrange
        $data = ['name' => 'Existing Name'];
        
        $this->mockRepository
            ->shouldReceive('existsByName')
            ->with($data['name'])
            ->once()
            ->andReturn(true);

        // Act & Assert
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Content type name already exists');
        
        $this->service->create($data);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
```

#### Feature Test Example

```php
<?php

namespace Tests\Feature\Api;

use App\Models\ContentType\ContentType;
use App\Models\Page\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PageApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_published_pages(): void
    {
        // Arrange
        $contentType = ContentType::factory()->create(['type' => 'page']);
        
        Page::factory()->count(3)->create([
            'content_type_id' => $contentType->id,
            'is_active' => true,
            'published_at' => now()->subDay()
        ]);

        Page::factory()->create([
            'content_type_id' => $contentType->id,
            'is_active' => false // Unpublished
        ]);

        // Act
        $response = $this->getJson('/api/pages');

        // Assert
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'slug',
                            'is_active',
                            'published_at',
                            'content_type'
                        ]
                    ],
                    'meta' => [
                        'current_page',
                        'per_page',
                        'total',
                        'last_page'
                    ]
                ]);

        $this->assertEquals(3, count($response->json('data')));
    }

    /** @test */
    public function it_can_get_page_by_slug(): void
    {
        // Arrange
        $contentType = ContentType::factory()->create(['type' => 'page']);
        $page = Page::factory()->create([
            'content_type_id' => $contentType->id,
            'slug' => 'test-page',
            'is_active' => true,
            'published_at' => now()->subDay()
        ]);

        // Act
        $response = $this->getJson('/api/pages/test-page');

        // Assert
        $response->assertStatus(200)
                ->assertJsonPath('data.slug', 'test-page')
                ->assertJsonPath('data.id', $page->id);
    }

    /** @test */
    public function authenticated_user_can_create_page(): void
    {
        // Arrange
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $contentType = ContentType::factory()->create(['type' => 'page']);
        
        $pageData = [
            'title' => 'New Test Page',
            'content_type_id' => $contentType->id,
            'is_active' => true
        ];

        // Act
        $response = $this->postJson('/pages', $pageData);

        // Assert
        $response->assertRedirect('/pages');
        
        $this->assertDatabaseHas('pages', [
            'title' => 'New Test Page',
            'created_by' => $user->id
        ]);
    }
}
```

### Frontend Testing (Vitest + Vue Test Utils)

```javascript
// tests/unit/components/FormInput.test.js
import { mount } from '@vue/test-utils'
import { describe, it, expect } from 'vitest'
import FormInput from '@/Components/Form/FormInput.vue'

describe('FormInput', () => {
  it('renders with correct props', () => {
    const wrapper = mount(FormInput, {
      props: {
        label: 'Test Label',
        modelValue: 'test value',
        required: true,
        error: 'Test error'
      }
    })

    expect(wrapper.find('label').text()).toBe('Test Label')
    expect(wrapper.find('input').element.value).toBe('test value')
    expect(wrapper.find('input').attributes('required')).toBeDefined()
    expect(wrapper.find('.error-message').text()).toBe('Test error')
  })

  it('emits update:modelValue on input', async () => {
    const wrapper = mount(FormInput, {
      props: {
        modelValue: ''
      }
    })

    const input = wrapper.find('input')
    await input.setValue('new value')

    expect(wrapper.emitted('update:modelValue')).toBeTruthy()
    expect(wrapper.emitted('update:modelValue')[0]).toEqual(['new value'])
  })
})
```

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run specific test file
php artisan test tests/Feature/Api/PageApiTest.php

# Run with coverage
php artisan test --coverage

# Frontend tests
pnpm run test
pnpm run test:watch
pnpm run test:coverage
```

## Database Development

### Migration Best Practices

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_type_fields', function (Blueprint $table) {
            $table->id();
            
            // Foreign key with explicit naming
            $table->unsignedBigInteger('content_type_id');
            $table->foreign('content_type_id', 'fk_ct_fields_content_type_id')
                  ->references('id')
                  ->on('content_types')
                  ->onDelete('cascade');
            
            // Core fields
            $table->string('name');
            $table->string('label');
            $table->string('type');
            $table->json('options')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_required')->default(false);
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['content_type_id', 'order']);
            $table->unique(['content_type_id', 'name'], 'unique_field_name_per_content_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_type_fields');
    }
};
```

### Factory Patterns

```php
<?php

namespace Database\Factories\ContentType;

use App\Models\ContentType\ContentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContentTypeFactory extends Factory
{
    protected $model = ContentType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'type' => $this->faker->randomElement(['page', 'collection', 'component']),
            'created_by' => 1, // Assuming user ID 1 exists
        ];
    }

    public function page(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'page',
        ]);
    }

    public function collection(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'collection',
        ]);
    }

    public function withFields(array $fields = []): static
    {
        return $this->afterCreating(function (ContentType $contentType) use ($fields) {
            if (empty($fields)) {
                $fields = [
                    ['name' => 'title', 'label' => 'Title', 'type' => 'text'],
                    ['name' => 'content', 'label' => 'Content', 'type' => 'textarea'],
                ];
            }

            foreach ($fields as $index => $field) {
                $contentType->fields()->create([
                    'name' => $field['name'],
                    'label' => $field['label'],
                    'type' => $field['type'],
                    'options' => $field['options'] ?? [],
                    'is_required' => $field['is_required'] ?? false,
                    'order' => $index + 1,
                ]);
            }
        });
    }
}
```

### Seeder Development

```php
<?php

namespace Database\Seeders;

use App\Models\ContentType\ContentType;
use App\Models\Page\Page;
use App\Models\User;
use Illuminate\Database\Seeder;

class DevelopmentSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@cms-faisal.com',
        ]);

        // Create content types with fields
        $pageContentType = ContentType::factory()
            ->page()
            ->withFields([
                ['name' => 'banner_title', 'label' => 'Banner Title', 'type' => 'text', 'is_required' => true],
                ['name' => 'banner_subtitle', 'label' => 'Banner Subtitle', 'type' => 'text'],
                ['name' => 'featured_image', 'label' => 'Featured Image', 'type' => 'image'],
                ['name' => 'content', 'label' => 'Content', 'type' => 'textarea', 'is_required' => true],
            ])
            ->create(['name' => 'Landing Page']);

        // Create sample pages
        Page::factory()
            ->count(5)
            ->create([
                'content_type_id' => $pageContentType->id,
                'created_by' => $admin->id,
            ])
            ->each(function (Page $page) {
                // Add sample content
                $page->updateContent([
                    'banner_title' => fake()->sentence(4),
                    'banner_subtitle' => fake()->sentence(8),
                    'content' => fake()->paragraphs(3, true),
                ]);
            });
    }
}
```

## API Development

### API Resource Development

```php
<?php

namespace App\Http\Resources\Page;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'template' => $this->template,
            'is_active' => $this->is_active,
            'published_at' => $this->published_at?->toISOString(),
            
            // Relationships
            'content_type' => new ContentTypeResource($this->whenLoaded('contentType')),
            'content' => $this->when(
                $this->relationLoaded('contentValues'),
                function () {
                    return $this->contentValues->mapWithKeys(function ($content) {
                        return [$content->field->name => $content->value];
                    });
                }
            ),
            'seo_meta' => new SeoMetaResource($this->whenLoaded('seoMeta')),
            
            // Metadata
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            'creator' => new UserResource($this->whenLoaded('creator')),
        ];
    }
}
```

### API Controller Best Practices

```php
<?php

namespace App\Http\Controllers\Api;

use App\Base\BaseController;
use App\Http\Requests\Page\StorePageRequest;
use App\Http\Requests\Page\UpdatePageRequest;
use App\Http\Resources\Page\PageCollection;
use App\Http\Resources\Page\PageResource;
use App\Services\Page\PageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageController extends BaseController
{
    public function __construct(
        private readonly PageService $pageService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/pages",
     *     summary="Get paginated list of published pages",
     *     tags={"Pages"},
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", maximum=100)),
     *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Success")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'per_page' => 'integer|between:1,100',
            'search' => 'string|max:255',
            'sort' => 'in:title,published_at,created_at',
            'direction' => 'in:asc,desc',
        ]);

        $filters = $request->only(['search', 'sort', 'direction']);
        $perPage = $request->get('per_page', $this->perPage);

        $pages = $this->pageService->getPublishedPages($filters, $perPage);

        return $this->success(
            new PageCollection($pages),
            'Pages retrieved successfully'
        );
    }

    /**
     * @OA\Get(
     *     path="/api/pages/{slug}",
     *     summary="Get page by slug",
     *     tags={"Pages"}
     * )
     */
    public function show(string $slug): JsonResponse
    {
        $page = $this->pageService->findPublishedBySlug($slug);

        if (!$page) {
            return $this->error('Page not found', 404);
        }

        return $this->success(
            new PageResource($page->load(['contentType.fields', 'contentValues.field', 'seoMeta'])),
            'Page retrieved successfully'
        );
    }

    /**
     * Store a new page (Protected route)
     */
    public function store(StorePageRequest $request): JsonResponse
    {
        $page = $this->pageService->createWithContent(
            $request->validated(),
            $request->get('content', [])
        );

        return $this->success(
            new PageResource($page),
            'Page created successfully',
            [],
            201
        );
    }

    /**
     * Update a page (Protected route)
     */
    public function update(UpdatePageRequest $request, int $id): JsonResponse
    {
        $page = $this->pageService->updateWithContent(
            $id,
            $request->validated(),
            $request->get('content', [])
        );

        return $this->success(
            new PageResource($page),
            'Page updated successfully'
        );
    }
}
```

### Request Validation

```php
<?php

namespace App\Http\Requests\Page;

use App\Base\BaseRequest;
use Illuminate\Validation\Rule;

class StorePageRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-page');
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/',
                Rule::unique('pages', 'slug')
            ],
            'content_type_id' => 'required|exists:content_types,id',
            'template' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'published_at' => 'nullable|date',
            'content' => 'array',
            'content.*' => 'nullable',
            'seo_meta' => 'array',
            'seo_meta.title' => 'nullable|string|max:255',
            'seo_meta.description' => 'nullable|string|max:500',
            'seo_meta.keywords' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Page title is required',
            'slug.regex' => 'Slug can only contain lowercase letters, numbers, and hyphens',
            'slug.unique' => 'This slug is already taken',
            'content_type_id.required' => 'Content type is required',
            'content_type_id.exists' => 'Selected content type does not exist',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Auto-generate slug if not provided
        if (!$this->has('slug') && $this->has('title')) {
            $this->merge([
                'slug' => \Str::slug($this->title)
            ]);
        }
    }
}
```

## Frontend Development

### Component Development Standards

```vue
<!-- Components/ContentEditor.vue -->
<script setup>
import { ref, computed, watch } from 'vue'
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import { z } from 'zod'
import FormInput from '@/Components/Form/FormInput.vue'
import FormTextarea from '@/Components/Form/FormTextarea.vue'
import FormSelect from '@/Components/Form/FormSelect.vue'

// Props with TypeScript-like validation
const props = defineProps({
  contentType: {
    type: Object,
    required: true,
    validator: (value) => value && typeof value.id === 'number'
  },
  initialContent: {
    type: Object,
    default: () => ({})
  },
  readonly: {
    type: Boolean,
    default: false
  }
})

// Events
const emit = defineEmits({
  'content-change': (content) => typeof content === 'object',
  'validation-change': (isValid) => typeof isValid === 'boolean'
})

// Dynamic validation schema based on content type fields
const validationSchema = computed(() => {
  const schema = {}
  
  props.contentType.fields?.forEach(field => {
    let fieldSchema = z.any()
    
    if (field.is_required) {
      switch (field.type) {
        case 'text':
        case 'textarea':
          fieldSchema = z.string().min(1, `${field.label} is required`)
          break
        case 'select':
          fieldSchema = z.string().min(1, `Please select ${field.label}`)
          break
        case 'checkbox':
          fieldSchema = z.array(z.string()).min(1, `Please select at least one ${field.label}`)
          break
      }
    } else {
      fieldSchema = fieldSchema.optional()
    }
    
    schema[field.name] = fieldSchema
  })
  
  return z.object(schema)
})

// Form handling
const { values, errors, meta, setFieldValue } = useForm({
  validationSchema: toTypedSchema(validationSchema.value),
  initialValues: props.initialContent
})

// Watchers
watch(values, (newValues) => {
  emit('content-change', newValues)
}, { deep: true })

watch(() => meta.value.valid, (isValid) => {
  emit('validation-change', isValid)
})

// Component mapping
const getFieldComponent = (fieldType) => {
  const components = {
    text: FormInput,
    textarea: FormTextarea,
    select: FormSelect,
    // ... other field types
  }
  return components[fieldType] || FormInput
}

// Methods
const updateFieldValue = (fieldName, value) => {
  setFieldValue(fieldName, value)
}
</script>

<template>
  <div class="space-y-6">
    <div
      v-for="field in contentType.fields"
      :key="field.id"
      class="field-wrapper"
    >
      <component
        :is="getFieldComponent(field.type)"
        :label="field.label"
        :model-value="values[field.name]"
        :error="errors[field.name]"
        :required="field.is_required"
        :readonly="readonly"
        :options="field.options"
        @update:model-value="updateFieldValue(field.name, $event)"
      />
    </div>
  </div>
</template>

<style scoped>
.field-wrapper {
  @apply transition-all duration-200;
}

.field-wrapper:focus-within {
  @apply ring-2 ring-indigo-500 ring-opacity-50 rounded-lg;
}
</style>
```

### State Management with Pinia

```javascript
// stores/content.js
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { contentTypesApi } from '@/utils/api'

export const useContentStore = defineStore('content', () => {
  // State
  const contentTypes = ref([])
  const currentContentType = ref(null)
  const loading = ref(false)
  const error = ref(null)

  // Getters
  const contentTypesByType = computed(() => (type) => 
    contentTypes.value.filter(ct => ct.type === type)
  )

  const getContentTypeById = computed(() => (id) => 
    contentTypes.value.find(ct => ct.id === id)
  )

  // Actions
  const fetchContentTypes = async () => {
    loading.value = true
    error.value = null
    
    try {
      const response = await contentTypesApi.getAll()
      contentTypes.value = response.data.data
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  const createContentType = async (data) => {
    loading.value = true
    error.value = null
    
    try {
      const response = await contentTypesApi.create(data)
      contentTypes.value.push(response.data.data)
      return response.data.data
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  const updateContentType = async (id, data) => {
    loading.value = true
    error.value = null
    
    try {
      const response = await contentTypesApi.update(id, data)
      const index = contentTypes.value.findIndex(ct => ct.id === id)
      if (index !== -1) {
        contentTypes.value[index] = response.data.data
      }
      return response.data.data
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  const deleteContentType = async (id) => {
    loading.value = true
    error.value = null
    
    try {
      await contentTypesApi.delete(id)
      const index = contentTypes.value.findIndex(ct => ct.id === id)
      if (index !== -1) {
        contentTypes.value.splice(index, 1)
      }
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    // State
    contentTypes,
    currentContentType,
    loading,
    error,
    
    // Getters
    contentTypesByType,
    getContentTypeById,
    
    // Actions
    fetchContentTypes,
    createContentType,
    updateContentType,
    deleteContentType
  }
})
```

## Security Guidelines

### Authentication & Authorization

```php
// Middleware for API rate limiting
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ApiRateLimit
{
    public function handle(Request $request, Closure $next, string $limit = '60'): mixed
    {
        $key = $request->ip();
        
        if (auth()->check()) {
            $key = 'user:' . auth()->id();
            $limit = '100'; // Higher limit for authenticated users
        }

        if (RateLimiter::tooManyAttempts($key, $limit)) {
            return response()->json([
                'error' => 'Too many requests'
            ], 429);
        }

        RateLimiter::hit($key, 60); // 1 minute window

        return $next($request);
    }
}
```

### Input Validation & Sanitization

```php
// Custom validation rules
namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SanitizedHtml implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail('The :attribute must be a string.');
            return;
        }

        // Check for potentially dangerous HTML
        $dangerous = [
            '<script',
            'javascript:',
            'onload=',
            'onerror=',
            'onclick=',
        ];

        foreach ($dangerous as $pattern) {
            if (stripos($value, $pattern) !== false) {
                $fail('The :attribute contains potentially dangerous content.');
                return;
            }
        }
    }
}
```

### CSRF Protection

```php
// Custom CSRF middleware for API
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiCsrfProtection
{
    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('DELETE')) {
            $token = $request->header('X-CSRF-TOKEN') 
                  ?? $request->input('_token');

            if (!hash_equals(session()->token(), $token)) {
                return response()->json([
                    'error' => 'CSRF token mismatch'
                ], 419);
            }
        }

        return $next($request);
    }
}
```

## Performance Optimization

### Database Query Optimization

```php
// Eloquent relationship optimization
class PageRepository extends BaseRepository
{
    public function getPublishedWithContent(array $filters = [], int $perPage = 15)
    {
        return $this->model
            ->with([
                'contentType:id,name,type',
                'contentValues' => function ($query) {
                    $query->select('page_id', 'content_type_field_id', 'value')
                          ->with('field:id,name,type');
                },
                'seoMeta:metable_id,metable_type,title,description'
            ])
            ->select('id', 'title', 'slug', 'content_type_id', 'is_active', 'published_at')
            ->published()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('slug', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }
}
```

### Caching Strategy

```php
// Service layer caching
namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CachedContentTypeService extends ContentTypeService
{
    public function getAll(): Collection
    {
        return Cache::remember(
            'content_types.all',
            now()->addHours(1),
            fn() => parent::getAll()
        );
    }

    public function findById(int $id): ?ContentType
    {
        return Cache::remember(
            "content_types.{$id}",
            now()->addHours(1),
            fn() => parent::findById($id)
        );
    }

    public function create(array $data): ContentType
    {
        $contentType = parent::create($data);
        
        // Clear cache
        Cache::forget('content_types.all');
        
        return $contentType;
    }
}
```

### Frontend Performance

```javascript
// Lazy loading components
const LazyTinyMCE = defineAsyncComponent({
  loader: () => import('@tinymce/tinymce-vue'),
  loadingComponent: LoadingSpinner,
  errorComponent: ErrorComponent,
  delay: 200,
  timeout: 3000
})

// Virtual scrolling for large lists
import { RecycleScroller } from 'vue-virtual-scroller'

// Image optimization
const optimizeImage = (url, width = 800, height = 600, quality = 80) => {
  if (!url) return ''
  
  // Use image CDN or service
  return `${url}?w=${width}&h=${height}&q=${quality}&auto=format`
}
```

## Debugging

### Laravel Debugging Tools

```php
// Custom debug helper
if (!function_exists('dd_query')) {
    function dd_query() {
        DB::listen(function ($query) {
            dump([
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time' => $query->time
            ]);
        });
    }
}

// Model debugging
class Page extends Model
{
    protected static function boot()
    {
        parent::boot();
        
        if (app()->environment('local')) {
            static::addGlobalScope('debug', function ($builder) {
                $builder->withCount(['contentValues', 'seoMeta']);
            });
        }
    }
}
```

### Frontend Debugging

```javascript
// Vue DevTools integration
if (import.meta.env.DEV) {
  app.config.performance = true
  app.config.globalProperties.$log = console.log
}

// Debug store
export const useDebugStore = defineStore('debug', () => {
  const apiCalls = ref([])
  const errors = ref([])
  
  const logApiCall = (method, url, data) => {
    if (import.meta.env.DEV) {
      apiCalls.value.push({
        method,
        url,
        data,
        timestamp: new Date().toISOString()
      })
    }
  }
  
  return { apiCalls, errors, logApiCall }
})
```

---

*This development guide provides comprehensive guidelines for contributing to the CMS Faisal project. Follow these standards to ensure code quality, security, and maintainability.*
