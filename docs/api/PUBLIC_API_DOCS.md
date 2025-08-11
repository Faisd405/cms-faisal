# CMS Faisal Public API Documentation

## Overview

This API provides access to pages, collections, and language data from the CMS Faisal headless content management system. All endpoints return JSON responses and support localization.

## Base URL
```
https://your-domain.com/api
```

## Authentication
All public API endpoints are currently open and do not require authentication.

## Rate Limiting
- **200 requests per minute** per IP address
- Rate limit headers are included in responses:
  - `X-RateLimit-Limit`: Request limit per window
  - `X-RateLimit-Remaining`: Requests remaining in current window

## Localization

All content endpoints support localization through the `locale` parameter:

```
GET /api/pages/about?locale=en
GET /api/pages/about?locale=id
```

### Supported Features:
- **Automatic Fallback**: If content isn't available in the requested locale, the API falls back to the default language
- **Locale Validation**: Invalid locales return a 400 error
- **Locale Information**: Response includes the actual locale used

### Available Locales:
Use the `/api/languages` endpoint to get current available languages.

## Common Parameters

### Pagination
- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 15, max: 100)

### Filtering
- `search`: Search term for title/content
- `sort`: Sort field (varies by endpoint)
- `direction`: Sort direction (`asc` or `desc`)

### Field Selection
- `fields`: Comma-separated list of fields to include
- `include`: Comma-separated list of relationships to include

Example:
```
GET /api/pages?fields=id,title,slug&include=contentType
```

## Response Format

All responses follow this structure:

```json
{
    "success": boolean,
    "message": "string",
    "data": object|array,
    "meta": object (optional)
}
```

### Success Response Example:
```json
{
    "success": true,
    "message": "Pages retrieved successfully",
    "data": {
        "data": [...],
        "pagination": {
            "current_page": 1,
            "per_page": 15,
            "total": 100,
            "last_page": 7
        }
    }
}
```

### Error Response Example:
```json
{
    "success": false,
    "message": "Page not found",
    "error": {
        "code": "PAGE_NOT_FOUND",
        "details": "No page found with slug: nonexistent"
    }
}
```

## Endpoints

### 1. Pages

#### List Pages
```http
GET /api/pages
```

**Parameters:**
- `page` (integer): Page number
- `per_page` (integer): Items per page (1-100)
- `search` (string): Search in title/content
- `sort` (string): `title`, `published_at`, `created_at`, `updated_at`
- `direction` (string): `asc` or `desc`
- `locale` (string): Language code (e.g., `en`, `id`)
- `fields` (string): Comma-separated field list
- `include` (string): Comma-separated relationship list

**Response:**
```json
{
    "success": true,
    "message": "Pages retrieved successfully",
    "data": {
        "data": [
            {
                "id": 1,
                "title": "About Us",
                "slug": "about",
                "excerpt": "Learn more about our company",
                "content": {...},
                "status": "published",
                "meta": {
                    "seo_title": "About Us - Company",
                    "seo_description": "Company description",
                    "seo_keywords": "about, company"
                },
                "content_type": {
                    "id": 1,
                    "name": "Basic Page",
                    "slug": "basic-page"
                },
                "locale": {
                    "iso_code": "en"
                },
                "published_at": "2024-01-15T10:30:00.000000Z",
                "created_at": "2024-01-15T10:30:00.000000Z",
                "updated_at": "2024-01-15T10:30:00.000000Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 15,
            "total": 25,
            "last_page": 2,
            "from": 1,
            "to": 15
        },
        "meta": {
            "locale": "en",
            "total_items": 25
        }
    }
}
```

#### Get Page by Slug
```http
GET /api/pages/{slug}
```

**Parameters:**
- `locale` (string): Language code
- `fields` (string): Comma-separated field list
- `include` (string): Comma-separated relationship list

**Response:**
```json
{
    "success": true,
    "message": "Page retrieved successfully",
    "data": {
        "id": 1,
        "title": "About Us",
        "slug": "about",
        "excerpt": "Learn more about our company",
        "content": {
            "hero_title": "Welcome to Our Company",
            "hero_description": "We are a leading technology company...",
            "sections": [...]
        },
        "status": "published",
        "meta": {
            "seo_title": "About Us - Company",
            "seo_description": "Company description",
            "seo_keywords": "about, company"
        },
        "locale": {
            "iso_code": "en"
        },
        "published_at": "2024-01-15T10:30:00.000000Z",
        "created_at": "2024-01-15T10:30:00.000000Z",
        "updated_at": "2024-01-15T10:30:00.000000Z"
    }
}
```

### 2. Collections

#### List Sections
```http
GET /api/collection/sections
```

**Parameters:**
- `page` (integer): Page number
- `per_page` (integer): Items per page (1-100)
- `search` (string): Search in title/description
- `sort` (string): `title`, `created_at`, `updated_at`
- `direction` (string): `asc` or `desc`

**Response:**
```json
{
    "success": true,
    "message": "Sections retrieved successfully",
    "data": {
        "data": [
            {
                "id": 1,
                "title": "News",
                "slug": "news",
                "description": "Latest news and updates",
                "content_type": {
                    "id": 2,
                    "name": "News Post",
                    "slug": "news-post"
                },
                "posts_count": 15,
                "created_at": "2024-01-15T10:30:00.000000Z",
                "updated_at": "2024-01-15T10:30:00.000000Z"
            }
        ]
    }
}
```

#### Get Section by Slug
```http
GET /api/collection/sections/{sectionSlug}
```

**Response:**
```json
{
    "success": true,
    "message": "Section retrieved successfully",
    "data": {
        "id": 1,
        "title": "News",
        "slug": "news",
        "description": "Latest news and updates",
        "content_type": {
            "id": 2,
            "name": "News Post",
            "slug": "news-post"
        },
        "posts_count": 15,
        "created_at": "2024-01-15T10:30:00.000000Z",
        "updated_at": "2024-01-15T10:30:00.000000Z"
    }
}
```

#### List Posts in Section
```http
GET /api/collection/sections/{sectionSlug}/posts
```

**Parameters:**
- `page` (integer): Page number
- `per_page` (integer): Items per page (1-100)
- `search` (string): Search in title/content
- `sort` (string): `title`, `published_at`, `created_at`, `updated_at`
- `direction` (string): `asc` or `desc`
- `locale` (string): Language code
- `fields` (string): Comma-separated field list
- `include` (string): Comma-separated relationship list

**Response:**
```json
{
    "success": true,
    "message": "Posts retrieved successfully",
    "data": {
        "data": [
            {
                "id": 1,
                "title": "Breaking News: New Product Launch",
                "slug": "new-product-launch",
                "excerpt": "We're excited to announce...",
                "content": {...},
                "status": "published",
                "section": {
                    "id": 1,
                    "title": "News",
                    "slug": "news"
                },
                "locale": {
                    "iso_code": "en"
                },
                "published_at": "2024-01-15T10:30:00.000000Z",
                "created_at": "2024-01-15T10:30:00.000000Z",
                "updated_at": "2024-01-15T10:30:00.000000Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 15,
            "total": 25,
            "last_page": 2
        },
        "meta": {
            "locale": "en",
            "total_items": 25
        }
    },
    "meta": {
        "section_slug": "news",
        "locale": "en"
    }
}
```

#### Get Specific Post
```http
GET /api/collection/sections/{sectionSlug}/posts/{postSlug}
```

**Parameters:**
- `locale` (string): Language code
- `fields` (string): Comma-separated field list
- `include` (string): Comma-separated relationship list

**Response:**
```json
{
    "success": true,
    "message": "Post retrieved successfully",
    "data": {
        "id": 1,
        "title": "Breaking News: New Product Launch",
        "slug": "new-product-launch",
        "excerpt": "We're excited to announce...",
        "content": {
            "body": "Full article content...",
            "featured_image": "https://example.com/image.jpg",
            "tags": ["news", "product", "launch"]
        },
        "status": "published",
        "section": {
            "id": 1,
            "title": "News",
            "slug": "news"
        },
        "content_type": {
            "id": 2,
            "name": "News Post",
            "slug": "news-post"
        },
        "locale": {
            "iso_code": "en"
        },
        "published_at": "2024-01-15T10:30:00.000000Z",
        "created_at": "2024-01-15T10:30:00.000000Z",
        "updated_at": "2024-01-15T10:30:00.000000Z"
    },
    "meta": {
        "section_slug": "news",
        "fallback_used": false
    }
}
```

### 3. Languages

#### List Available Languages
```http
GET /api/languages
```

**Parameters:**
- `active_only` (boolean): Filter to only active languages (default: true)

**Response:**
```json
{
    "success": true,
    "message": "Languages retrieved successfully",
    "data": [
        {
            "id": 1,
            "name": "English",
            "iso_code": "en",
            "is_rtl": false,
            "is_default": true,
            "is_active": true
        },
        {
            "id": 2,
            "name": "Indonesian",
            "iso_code": "id",
            "is_rtl": false,
            "is_default": false,
            "is_active": true
        }
    ],
    "meta": {
        "total_languages": 2,
        "default_language": "en"
    }
}
```

## Error Codes

| Code | HTTP Status | Description |
|------|-------------|-------------|
| `VALIDATION_ERROR` | 422 | Request validation failed |
| `PAGE_NOT_FOUND` | 404 | Requested page doesn't exist |
| `SECTION_NOT_FOUND` | 404 | Requested section doesn't exist |
| `POST_NOT_FOUND` | 404 | Requested post doesn't exist |
| `INVALID_LOCALE` | 400 | Provided locale is not supported |
| `RESOURCE_NOT_FOUND` | 404 | Generic resource not found |
| `HTTP_ERROR` | Various | HTTP-related errors |
| `INTERNAL_ERROR` | 500 | Server-side error |

## Caching

The API implements intelligent caching:

- **Pages**: Cached for 1 hour per locale
- **Collections**: Cached for 30 minutes
- **Posts**: Cached for 15 minutes per locale
- **Languages**: Cached for 2 hours

Cache is automatically invalidated when content is updated.

## Examples

### Get English pages with limited fields:
```bash
curl "https://your-domain.com/api/pages?locale=en&fields=id,title,slug&per_page=5"
```

### Search for pages:
```bash
curl "https://your-domain.com/api/pages?search=tutorial&sort=published_at&direction=desc"
```

### Get specific post in Indonesian:
```bash
curl "https://your-domain.com/api/collection/sections/news/posts/latest-update?locale=id"
```

### Get all available languages:
```bash
curl "https://your-domain.com/api/languages"
```

## SDKs and Libraries

Coming soon:
- JavaScript/TypeScript SDK
- PHP SDK
- Python SDK

## Support

For API support and questions:
- Email: api-support@your-domain.com
- Documentation: https://your-domain.com/docs/api
- Status Page: https://status.your-domain.com
