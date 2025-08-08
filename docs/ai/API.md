# API Documentation - CMS Faisal

## Base URL
- **Development**: `http://localhost:8000/api`
- **Production**: `https://your-domain.com/api`

## Authentication

The CMS provides both public and protected endpoints:
- **Public endpoints**: No authentication required
- **Protected endpoints**: Require authentication via Laravel Sanctum tokens

### Authentication Headers
For protected endpoints, include the authentication token:
```http
Authorization: Bearer {your-token}
Accept: application/json
Content-Type: application/json
```

## Response Format

All API responses follow a consistent JSON format:

### Success Response
```json
{
    "success": true,
    "data": {
        // Response data
    },
    "message": "Operation successful",
    "meta": {
        // Pagination or additional metadata
    }
}
```

### Error Response
```json
{
    "success": false,
    "error": {
        "code": "ERROR_CODE",
        "message": "Error description",
        "details": {
            // Additional error details
        }
    }
}
```

## Public API Endpoints

### Pages

#### Get All Pages
```http
GET /api/pages
```

**Query Parameters:**
- `per_page` (integer, optional): Number of items per page (default: 15)
- `page` (integer, optional): Page number (default: 1)
- `search` (string, optional): Search term for title or content
- `is_active` (boolean, optional): Filter by active status

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Home Page",
            "slug": "home",
            "template": "home.blade.php",
            "is_active": true,
            "published_at": "2025-01-01T00:00:00.000000Z",
            "content_type": {
                "id": 1,
                "name": "Landing Page",
                "type": "page"
            },
            "content": [
                {
                    "field_name": "banner_title",
                    "field_type": "text",
                    "value": "Welcome to Our Site"
                }
            ],
            "seo_meta": {
                "title": "Home - Our Website",
                "description": "Welcome to our amazing website",
                "keywords": "home, welcome, website"
            }
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 1,
        "last_page": 1
    }
}
```

#### Get Page by Slug
```http
GET /api/pages/{slug}
```

**Parameters:**
- `slug` (string, required): The page slug

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Home Page",
        "slug": "home",
        "template": "home.blade.php",
        "is_active": true,
        "published_at": "2025-01-01T00:00:00.000000Z",
        "content_type": {
            "id": 1,
            "name": "Landing Page",
            "type": "page",
            "fields": [
                {
                    "id": 1,
                    "name": "banner_title",
                    "type": "text",
                    "is_required": true,
                    "options": {},
                    "order": 1
                }
            ]
        },
        "content": [
            {
                "field_id": 1,
                "field_name": "banner_title",
                "field_type": "text",
                "value": "Welcome to Our Site"
            }
        ],
        "seo_meta": {
            "title": "Home - Our Website",
            "description": "Welcome to our amazing website",
            "keywords": "home, welcome, website",
            "og_title": "Home - Our Website",
            "og_description": "Welcome to our amazing website",
            "og_image": "https://example.com/images/home-og.jpg"
        }
    }
}
```

### Collections

#### Get All Sections
```http
GET /api/collection/sections
```

**Query Parameters:**
- `per_page` (integer, optional): Number of items per page (default: 15)
- `page` (integer, optional): Page number (default: 1)
- `search` (string, optional): Search term for name or description
- `is_active` (boolean, optional): Filter by active status

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Blog",
            "slug": "blog",
            "description": "Our company blog",
            "is_active": true,
            "posts_count": 5,
            "content_type": {
                "id": 2,
                "name": "Blog Section",
                "type": "collection"
            }
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 1,
        "last_page": 1
    }
}
```

#### Get Section by Slug
```http
GET /api/collection/sections/{sectionSlug}
```

**Parameters:**
- `sectionSlug` (string, required): The section slug

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Blog",
        "slug": "blog",
        "description": "Our company blog",
        "is_active": true,
        "posts_count": 5,
        "content_type": {
            "id": 2,
            "name": "Blog Section",
            "type": "collection",
            "fields": [
                {
                    "id": 2,
                    "name": "featured_image",
                    "type": "image",
                    "is_required": false,
                    "options": {
                        "allowed_types": ["jpg", "png", "webp"],
                        "max_size": "2MB"
                    },
                    "order": 1
                }
            ]
        }
    }
}
```

#### Get Posts in Section
```http
GET /api/collection/sections/{sectionSlug}/posts
```

**Parameters:**
- `sectionSlug` (string, required): The section slug

**Query Parameters:**
- `per_page` (integer, optional): Number of items per page (default: 15)
- `page` (integer, optional): Page number (default: 1)
- `search` (string, optional): Search term for title or excerpt
- `is_active` (boolean, optional): Filter by active status
- `sort` (string, optional): Sort field (published_at, title, created_at)
- `order` (string, optional): Sort order (asc, desc)

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "First Blog Post",
            "slug": "first-blog-post",
            "excerpt": "This is our first blog post...",
            "is_active": true,
            "published_at": "2025-01-01T00:00:00.000000Z",
            "section": {
                "id": 1,
                "name": "Blog",
                "slug": "blog"
            },
            "content": [
                {
                    "field_name": "featured_image",
                    "field_type": "image",
                    "value": "https://example.com/images/post1.jpg"
                }
            ]
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 5,
        "last_page": 1
    }
}
```

#### Get Specific Post
```http
GET /api/collection/sections/{sectionSlug}/posts/{postSlug}
```

**Parameters:**
- `sectionSlug` (string, required): The section slug
- `postSlug` (string, required): The post slug

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "First Blog Post",
        "slug": "first-blog-post",
        "excerpt": "This is our first blog post...",
        "is_active": true,
        "published_at": "2025-01-01T00:00:00.000000Z",
        "section": {
            "id": 1,
            "name": "Blog",
            "slug": "blog",
            "description": "Our company blog"
        },
        "content": [
            {
                "field_id": 2,
                "field_name": "featured_image",
                "field_type": "image",
                "value": "https://example.com/images/post1.jpg"
            },
            {
                "field_id": 3,
                "field_name": "content",
                "field_type": "textarea",
                "value": "This is the full content of our first blog post..."
            }
        ],
        "tags": [
            {
                "id": 1,
                "name": "Technology",
                "slug": "technology"
            }
        ],
        "seo_meta": {
            "title": "First Blog Post - Our Blog",
            "description": "This is our first blog post...",
            "keywords": "blog, technology, first post"
        }
    }
}
```

### Languages

#### Get All Languages
```http
GET /api/languages
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "English",
            "code": "en",
            "is_active": true,
            "is_default": true
        },
        {
            "id": 2,
            "name": "Indonesian",
            "code": "id",
            "is_active": true,
            "is_default": false
        }
    ]
}
```

## Error Codes

### Common HTTP Status Codes

- `200` - OK: Request successful
- `201` - Created: Resource created successfully
- `400` - Bad Request: Invalid request parameters
- `401` - Unauthorized: Authentication required
- `403` - Forbidden: Access denied
- `404` - Not Found: Resource not found
- `422` - Unprocessable Entity: Validation errors
- `500` - Internal Server Error: Server error

### Custom Error Codes

- `RESOURCE_NOT_FOUND` - Requested resource does not exist
- `VALIDATION_ERROR` - Input validation failed
- `AUTHENTICATION_REQUIRED` - Valid authentication token required
- `INSUFFICIENT_PERMISSIONS` - User lacks required permissions
- `CONTENT_TYPE_MISMATCH` - Content type does not match expected format
- `SLUG_ALREADY_EXISTS` - Slug is already in use
- `INACTIVE_CONTENT` - Requested content is not active/published

### Example Error Response

```json
{
    "success": false,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "The given data was invalid.",
        "details": {
            "title": ["The title field is required."],
            "slug": ["The slug has already been taken."]
        }
    }
}
```

## Rate Limiting

API endpoints are rate limited to prevent abuse:

- **Public endpoints**: 60 requests per minute per IP
- **Authenticated endpoints**: 100 requests per minute per user

Rate limit headers are included in responses:
```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1640995200
```

## Pagination

List endpoints support pagination with the following parameters:

- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 15, max: 100)

Pagination metadata is included in the `meta` object:

```json
{
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 100,
        "last_page": 7,
        "from": 1,
        "to": 15,
        "links": {
            "first": "http://localhost:8000/api/pages?page=1",
            "last": "http://localhost:8000/api/pages?page=7",
            "prev": null,
            "next": "http://localhost:8000/api/pages?page=2"
        }
    }
}
```

## Content Filtering

### Search
Most list endpoints support full-text search:
```http
GET /api/pages?search=homepage
```

### Status Filtering
Filter by active/inactive status:
```http
GET /api/pages?is_active=true
```

### Sorting
Sort results by specific fields:
```http
GET /api/collection/sections/blog/posts?sort=published_at&order=desc
```

## Content Types & Dynamic Fields

Content is structured using dynamic Content Types. Each content type defines:

1. **Type**: page, collection, or component
2. **Fields**: Dynamic field definitions with types and validation
3. **Content**: Actual content values for each field

### Field Types

| Type | Description | Example Value |
|------|-------------|---------------|
| `text` | Single line text | `"Hello World"` |
| `textarea` | Multi-line text | `"Long content..."` |
| `select` | Dropdown selection | `"option1"` |
| `checkbox` | Multiple checkboxes | `["option1", "option2"]` |
| `radio` | Single radio selection | `"option1"` |
| `file` | File upload | `"https://example.com/file.pdf"` |
| `image` | Image upload | `"https://example.com/image.jpg"` |
| `date` | Date value | `"2025-01-01"` |
| `time` | Time value | `"14:30:00"` |
| `datetime` | Date and time | `"2025-01-01T14:30:00Z"` |

### Field Options

Fields can have additional configuration in the `options` object:

```json
{
    "id": 1,
    "name": "category",
    "type": "select",
    "options": {
        "choices": [
            {"value": "tech", "label": "Technology"},
            {"value": "design", "label": "Design"}
        ],
        "multiple": false
    }
}
```

## Webhooks (Future Feature)

*Note: Webhooks are planned for future implementation*

The CMS will support webhooks for real-time notifications:

- Content published/unpublished
- Content created/updated/deleted
- User actions

---

*For more information, see the main [README.md](README.md) documentation.*
