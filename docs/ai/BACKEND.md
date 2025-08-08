# Backend Architecture Documentation - CMS Faisal

## Overview

The backend of CMS Faisal is built on Laravel 11, following modern PHP development practices and architectural patterns. It implements a clean, scalable architecture with clear separation of concerns using repositories, services, and base classes.

## Architecture Patterns

### Repository Pattern
The application uses the Repository pattern to abstract data access logic from business logic:

```
Controller → Service → Repository → Model → Database
```

### Service Layer
Business logic is encapsulated in service classes, keeping controllers thin and focused on HTTP concerns.

### Base Classes
Common functionality is extracted into base classes to promote code reuse and consistency.

## Directory Structure

```
app/
├── Actions/                    # Laravel Jetstream actions
│   ├── Fortify/               # Authentication actions
│   └── Jetstream/             # Team management actions
├── Base/                      # Base classes for common functionality
│   ├── BaseCollection.php     # Base collection class
│   ├── BaseController.php     # Base controller class
│   ├── BaseModel.php          # Base model class
│   ├── BaseRepository.php     # Base repository class
│   ├── BaseRequest.php        # Base form request class
│   ├── BaseService.php        # Base service class
│   └── Construct/             # Constructor utilities
├── Console/                   # Artisan commands
│   └── Commands/             
├── Enums/                     # Enum classes
│   ├── ContentType.php        # Content type enumeration
│   ├── Permission.php         # Permission enumeration
│   └── Role.php               # Role enumeration
├── Http/                      # HTTP layer
│   ├── Controllers/           # Controllers
│   ├── Middleware/            # Custom middleware
│   └── Requests/              # Form request classes
├── Models/                    # Eloquent models
│   ├── Collection/            # Collection-related models
│   ├── Component/             # Component-related models
│   ├── ContentType/           # Content type models
│   ├── Datamaster/            # Master data models
│   ├── Page/                  # Page-related models
│   ├── SeoMeta.php            # SEO metadata model
│   ├── Tag.php                # Tag model
│   ├── TagContent.php         # Tag content pivot model
│   └── User.php               # User model
├── Providers/                 # Service providers
│   ├── AppServiceProvider.php # Main app service provider
│   ├── FortifyServiceProvider.php # Fortify configuration
│   └── JetstreamServiceProvider.php # Jetstream configuration
├── Repositories/              # Repository classes
│   ├── Auth/                  # Authentication repositories
│   ├── Collection/            # Collection repositories
│   ├── Component/             # Component repositories
│   ├── ContentType/           # Content type repositories
│   ├── Localization/          # Localization repositories
│   └── Page/                  # Page repositories
├── Services/                  # Service classes
│   ├── Auth/                  # Authentication services
│   ├── Collection/            # Collection services
│   ├── Component/             # Component services
│   ├── ContentType/           # Content type services
│   ├── Localization/          # Localization services
│   └── Page/                  # Page services
└── Traits/                    # Reusable traits
    ├── ApplyPagination.php    # Pagination trait
    ├── ResponseHelper.php     # API response trait
    ├── UseAttachment.php      # File attachment trait
    └── Model/                 # Model-specific traits
```

## Base Classes

### BaseController
Provides common controller functionality and response helpers.

```php
<?php

namespace App\Base;

use App\Traits\ResponseHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseControllerLaravel;

class BaseController extends BaseControllerLaravel
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ResponseHelper;

    /**
     * Default pagination size
     */
    protected int $perPage = 15;

    /**
     * Get pagination size from request
     */
    protected function getPerPage(): int
    {
        return min(
            request()->get('per_page', $this->perPage),
            100 // Maximum allowed
        );
    }

    /**
     * Success response with data
     */
    protected function successWithData($data, string $message = 'Success', array $meta = [])
    {
        return $this->success($data, $message, $meta);
    }

    /**
     * Error response
     */
    protected function errorResponse(string $message, int $code = 400, array $errors = [])
    {
        return $this->error($message, $code, $errors);
    }
}
```

### BaseModel
Provides common model functionality and relationships.

```php
<?php

namespace App\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user who created this record
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this record
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who deleted this record
     */
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Scope to get active records
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get published records
     */
    public function scopePublished($query)
    {
        return $query->where('is_active', true)
                    ->where('published_at', '<=', now());
    }
}
```

### BaseRepository
Provides common repository methods for data access.

```php
<?php

namespace App\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all records
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find record by ID
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Find record by ID or fail
     */
    public function findOrFail(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create new record
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update record
     */
    public function update(int $id, array $data): Model
    {
        $record = $this->findOrFail($id);
        $record->update($data);
        return $record->fresh();
    }

    /**
     * Delete record
     */
    public function delete(int $id): bool
    {
        $record = $this->findOrFail($id);
        return $record->delete();
    }

    /**
     * Get paginated records
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * Find by specific field
     */
    public function findBy(string $field, $value): ?Model
    {
        return $this->model->where($field, $value)->first();
    }

    /**
     * Get records by specific field
     */
    public function getBy(string $field, $value): Collection
    {
        return $this->model->where($field, $value)->get();
    }

    /**
     * Search records
     */
    public function search(string $term, array $fields = []): Collection
    {
        $query = $this->model->newQuery();

        foreach ($fields as $field) {
            $query->orWhere($field, 'LIKE', "%{$term}%");
        }

        return $query->get();
    }
}
```

### BaseService
Provides common service functionality for business logic.

```php
<?php

namespace App\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseService
{
    protected BaseRepository $repository;

    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all records
     */
    public function getAll(): Collection
    {
        return $this->repository->all();
    }

    /**
     * Get paginated records
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    /**
     * Find record by ID
     */
    public function findById(int $id): ?Model
    {
        return $this->repository->find($id);
    }

    /**
     * Create new record
     */
    public function create(array $data): Model
    {
        $data = $this->prepareDataForCreate($data);
        return $this->repository->create($data);
    }

    /**
     * Update record
     */
    public function update(int $id, array $data): Model
    {
        $data = $this->prepareDataForUpdate($data);
        return $this->repository->update($id, $data);
    }

    /**
     * Delete record
     */
    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Prepare data before creating
     */
    protected function prepareDataForCreate(array $data): array
    {
        if (auth()->check()) {
            $data['created_by'] = auth()->id();
        }

        return $data;
    }

    /**
     * Prepare data before updating
     */
    protected function prepareDataForUpdate(array $data): array
    {
        if (auth()->check()) {
            $data['updated_by'] = auth()->id();
        }

        return $data;
    }
}
```

## Model Architecture

### Content Type Model

```php
<?php

namespace App\Models\ContentType;

use App\Base\BaseModel;
use App\Traits\Model\UseTrackUserActions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContentType extends BaseModel
{
    use HasFactory, UseTrackUserActions;

    protected $fillable = [
        'created_by',
        'updated_by',
        'deleted_by',
        'name',
        'description',
        'type',
    ];

    protected $casts = [
        'type' => \App\Enums\ContentType::class,
    ];

    public static function boot()
    {
        parent::boot();
        self::bootUseTrackUserActions();
    }

    /**
     * Get the fields for this content type
     */
    public function fields()
    {
        return $this->hasMany(ContentTypeField::class)->orderBy('order');
    }

    /**
     * Get pages using this content type
     */
    public function pages()
    {
        return $this->hasMany(\App\Models\Page\Page::class);
    }

    /**
     * Get collection sections using this content type
     */
    public function collectionSections()
    {
        return $this->hasMany(\App\Models\Collection\CollectionSection::class);
    }

    /**
     * Get components using this content type
     */
    public function components()
    {
        return $this->hasMany(\App\Models\Component\Component::class);
    }
}
```

### Page Model with Dynamic Content

```php
<?php

namespace App\Models\Page;

use App\Models\ContentType\ContentType;
use App\Models\SeoMeta;
use App\Traits\Model\UseTrackUserActions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory, UseTrackUserActions;

    protected $fillable = [
        'created_by',
        'updated_by',
        'deleted_by',
        'content_type_id',
        'title',
        'slug',
        'template',
        'is_active',
        'published_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();
        self::bootUseTrackUserActions();
    }

    /**
     * Get the content type definition
     */
    public function contentType()
    {
        return $this->belongsTo(ContentType::class);
    }

    /**
     * Get the content values for this page
     */
    public function contentValues()
    {
        return $this->hasMany(PageContent::class);
    }

    /**
     * Get SEO metadata
     */
    public function seoMeta()
    {
        return $this->morphOne(SeoMeta::class, 'metable');
    }

    /**
     * Get page content as key-value pairs
     */
    public function getContentAttribute()
    {
        return $this->contentValues()
            ->with('field')
            ->get()
            ->mapWithKeys(function ($content) {
                return [$content->field->name => $content->value];
            });
    }

    /**
     * Update page content
     */
    public function updateContent(array $contentData)
    {
        foreach ($contentData as $fieldName => $value) {
            $field = $this->contentType->fields()->where('name', $fieldName)->first();
            
            if ($field) {
                $this->contentValues()->updateOrCreate(
                    ['content_type_field_id' => $field->id],
                    ['value' => $value]
                );
            }
        }
    }

    /**
     * Scope for published pages
     */
    public function scopePublished($query)
    {
        return $query->where('is_active', true)
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope for finding by slug
     */
    public function scopeBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }
}
```

## Service Layer Implementation

### Content Type Service

```php
<?php

namespace App\Services\ContentType;

use App\Base\BaseService;
use App\Models\ContentType\ContentType;
use App\Repositories\ContentType\ContentTypeRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ContentTypeService extends BaseService
{
    protected ContentTypeRepository $contentTypeRepository;

    public function __construct(ContentTypeRepository $contentTypeRepository)
    {
        $this->contentTypeRepository = $contentTypeRepository;
        parent::__construct($contentTypeRepository);
    }

    /**
     * Get content types with fields
     */
    public function getAllWithFields(): Collection
    {
        return $this->contentTypeRepository->getAllWithFields();
    }

    /**
     * Get paginated content types with search
     */
    public function getPaginatedWithSearch(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->contentTypeRepository->getPaginatedWithSearch($filters, $perPage);
    }

    /**
     * Create content type with fields
     */
    public function createWithFields(array $data, array $fields = []): ContentType
    {
        $contentType = $this->create($data);

        if (!empty($fields)) {
            $this->addFieldsToContentType($contentType, $fields);
        }

        return $contentType->fresh(['fields']);
    }

    /**
     * Update content type with fields
     */
    public function updateWithFields(int $id, array $data, array $fields = []): ContentType
    {
        $contentType = $this->update($id, $data);

        if (!empty($fields)) {
            // Remove existing fields and add new ones
            $contentType->fields()->delete();
            $this->addFieldsToContentType($contentType, $fields);
        }

        return $contentType->fresh(['fields']);
    }

    /**
     * Add fields to content type
     */
    private function addFieldsToContentType(ContentType $contentType, array $fields): void
    {
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
    }

    /**
     * Get content types by type
     */
    public function getByType(string $type): Collection
    {
        return $this->contentTypeRepository->getByType($type);
    }

    /**
     * Check if content type has content
     */
    public function hasContent(int $id): bool
    {
        $contentType = $this->findById($id);
        
        if (!$contentType) {
            return false;
        }

        return $contentType->pages()->count() > 0 ||
               $contentType->collectionSections()->count() > 0 ||
               $contentType->components()->count() > 0;
    }
}
```

### Page Service

```php
<?php

namespace App\Services\Page;

use App\Base\BaseService;
use App\Models\Page\Page;
use App\Repositories\Page\PageRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PageService extends BaseService
{
    protected PageRepository $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
        parent::__construct($pageRepository);
    }

    /**
     * Get published pages for public API
     */
    public function getPublishedPages(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->pageRepository->getPublishedPaginated($filters, $perPage);
    }

    /**
     * Find published page by slug
     */
    public function findPublishedBySlug(string $slug): ?Page
    {
        return $this->pageRepository->findPublishedBySlug($slug);
    }

    /**
     * Create page with content
     */
    public function createWithContent(array $pageData, array $contentData = []): Page
    {
        // Generate slug if not provided
        if (!isset($pageData['slug'])) {
            $pageData['slug'] = $this->generateSlug($pageData['title']);
        }

        $page = $this->create($pageData);

        if (!empty($contentData)) {
            $page->updateContent($contentData);
        }

        return $page->fresh(['contentType', 'contentValues', 'seoMeta']);
    }

    /**
     * Update page with content
     */
    public function updateWithContent(int $id, array $pageData, array $contentData = []): Page
    {
        $page = $this->update($id, $pageData);

        if (!empty($contentData)) {
            $page->updateContent($contentData);
        }

        return $page->fresh(['contentType', 'contentValues', 'seoMeta']);
    }

    /**
     * Update only page content
     */
    public function updateContent(int $id, array $contentData): Page
    {
        $page = $this->findById($id);
        
        if (!$page) {
            throw new \Exception('Page not found');
        }

        $page->updateContent($contentData);

        return $page->fresh(['contentType', 'contentValues']);
    }

    /**
     * Generate unique slug
     */
    private function generateSlug(string $title, int $id = null): string
    {
        $slug = \Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug, $id)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if slug exists
     */
    private function slugExists(string $slug, int $excludeId = null): bool
    {
        $query = Page::where('slug', $slug);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Publish page
     */
    public function publish(int $id): Page
    {
        return $this->update($id, [
            'is_active' => true,
            'published_at' => now(),
        ]);
    }

    /**
     * Unpublish page
     */
    public function unpublish(int $id): Page
    {
        return $this->update($id, [
            'is_active' => false,
        ]);
    }
}
```

## Repository Implementation

### Content Type Repository

```php
<?php

namespace App\Repositories\ContentType;

use App\Base\BaseRepository;
use App\Models\ContentType\ContentType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ContentTypeRepository extends BaseRepository
{
    public function __construct(ContentType $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all content types with their fields
     */
    public function getAllWithFields(): Collection
    {
        return $this->model->with('fields')->get();
    }

    /**
     * Get paginated content types with search functionality
     */
    public function getPaginatedWithSearch(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with('fields');

        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Apply type filter
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // Apply sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * Get content types by type
     */
    public function getByType(string $type): Collection
    {
        return $this->model->where('type', $type)
                          ->with('fields')
                          ->get();
    }

    /**
     * Find content type with fields by ID
     */
    public function findWithFields(int $id): ?ContentType
    {
        return $this->model->with('fields')->find($id);
    }

    /**
     * Get active content types
     */
    public function getActive(): Collection
    {
        return $this->model->active()->with('fields')->get();
    }
}
```

## Controller Implementation

### API Controller

```php
<?php

namespace App\Http\Controllers\PublicApi;

use App\Base\BaseController;
use App\Services\Page\PageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageController extends BaseController
{
    protected PageService $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    /**
     * Display a listing of published pages
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->get('search'),
            'sort_by' => $request->get('sort_by', 'published_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
        ];

        $pages = $this->pageService->getPublishedPages(
            $filters,
            $this->getPerPage()
        );

        return $this->successWithData(
            $pages->items(),
            'Pages retrieved successfully',
            [
                'pagination' => [
                    'current_page' => $pages->currentPage(),
                    'per_page' => $pages->perPage(),
                    'total' => $pages->total(),
                    'last_page' => $pages->lastPage(),
                ]
            ]
        );
    }

    /**
     * Display the specified page by slug
     */
    public function show(string $slug): JsonResponse
    {
        $page = $this->pageService->findPublishedBySlug($slug);

        if (!$page) {
            return $this->errorResponse('Page not found', 404);
        }

        return $this->successWithData(
            $page->load(['contentType.fields', 'contentValues.field', 'seoMeta']),
            'Page retrieved successfully'
        );
    }
}
```

### Admin Controller

```php
<?php

namespace App\Http\Controllers;

use App\Base\BaseController;
use App\Http\Requests\ContentType\StoreContentTypeRequest;
use App\Http\Requests\ContentType\UpdateContentTypeRequest;
use App\Services\ContentType\ContentTypeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContentTypeController extends BaseController
{
    protected ContentTypeService $contentTypeService;

    public function __construct(ContentTypeService $contentTypeService)
    {
        $this->contentTypeService = $contentTypeService;
    }

    /**
     * Display a listing of content types
     */
    public function index(Request $request): Response
    {
        $filters = [
            'search' => $request->get('search'),
            'type' => $request->get('type'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
        ];

        $contentTypes = $this->contentTypeService->getPaginatedWithSearch(
            $filters,
            $this->getPerPage()
        );

        return Inertia::render('ContentTypes/Index', [
            'contentTypes' => $contentTypes,
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new content type
     */
    public function create(): Response
    {
        return Inertia::render('ContentTypes/Create');
    }

    /**
     * Store a newly created content type
     */
    public function store(StoreContentTypeRequest $request): RedirectResponse
    {
        $this->contentTypeService->createWithFields(
            $request->validated(),
            $request->get('fields', [])
        );

        return redirect()->route('content-types.index')
                        ->with('success', 'Content type created successfully');
    }

    /**
     * Show the form for editing the specified content type
     */
    public function edit(int $id): Response
    {
        $contentType = $this->contentTypeService->findById($id);

        if (!$contentType) {
            abort(404);
        }

        return Inertia::render('ContentTypes/Edit', [
            'contentType' => $contentType->load('fields'),
        ]);
    }

    /**
     * Update the specified content type
     */
    public function update(UpdateContentTypeRequest $request, int $id): RedirectResponse
    {
        $this->contentTypeService->updateWithFields(
            $id,
            $request->validated(),
            $request->get('fields', [])
        );

        return redirect()->route('content-types.index')
                        ->with('success', 'Content type updated successfully');
    }

    /**
     * Remove the specified content type
     */
    public function destroy(int $id): RedirectResponse
    {
        if ($this->contentTypeService->hasContent($id)) {
            return redirect()->back()
                            ->with('error', 'Cannot delete content type that has content');
        }

        $this->contentTypeService->delete($id);

        return redirect()->route('content-types.index')
                        ->with('success', 'Content type deleted successfully');
    }
}
```

## Traits & Utilities

### Response Helper Trait

```php
<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseHelper
{
    /**
     * Return a success JSON response
     */
    protected function success($data = null, string $message = 'Success', array $meta = []): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        if (!empty($meta)) {
            $response['meta'] = $meta;
        }

        return response()->json($response);
    }

    /**
     * Return an error JSON response
     */
    protected function error(string $message, int $code = 400, array $errors = []): JsonResponse
    {
        $response = [
            'success' => false,
            'error' => [
                'message' => $message,
                'code' => $code,
            ],
        ];

        if (!empty($errors)) {
            $response['error']['details'] = $errors;
        }

        return response()->json($response, $code);
    }
}
```

### User Tracking Trait

```php
<?php

namespace App\Traits\Model;

trait UseTrackUserActions
{
    public static function bootUseTrackUserActions()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });

        static::deleting(function ($model) {
            if (auth()->check()) {
                $model->deleted_by = auth()->id();
                $model->save();
            }
        });
    }
}
```

## Authentication & Authorization

### Permission System

```php
<?php

namespace App\Enums;

enum Permission: string
{
    // Content Type Permissions
    case CREATE_CONTENT_TYPE = 'create-content-type';
    case READ_CONTENT_TYPE = 'read-content-type';
    case UPDATE_CONTENT_TYPE = 'update-content-type';
    case DELETE_CONTENT_TYPE = 'delete-content-type';

    // Page Permissions
    case CREATE_PAGE = 'create-page';
    case READ_PAGE = 'read-page';
    case UPDATE_PAGE = 'update-page';
    case DELETE_PAGE = 'delete-page';
    case PUBLISH_PAGE = 'publish-page';

    // Collection Permissions
    case CREATE_COLLECTION = 'create-collection';
    case READ_COLLECTION = 'read-collection';
    case UPDATE_COLLECTION = 'update-collection';
    case DELETE_COLLECTION = 'delete-collection';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
```

### Middleware Implementation

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        if (!auth()->check() || !auth()->user()->can($permission)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => [
                        'message' => 'Insufficient permissions',
                        'code' => 403
                    ]
                ], 403);
            }

            abort(403, 'Insufficient permissions');
        }

        return $next($request);
    }
}
```

This backend architecture provides a solid foundation for the CMS with clear separation of concerns, maintainable code structure, and scalable patterns that can grow with the application's needs.
