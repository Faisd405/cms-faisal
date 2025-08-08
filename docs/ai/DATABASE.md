# Database Schema Documentation - CMS Faisal

## Overview

CMS Faisal uses a flexible database schema designed to support dynamic content types and multilingual content. The schema is built around the concept of content types that define the structure of different content entities.

## Entity Relationship Diagram

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│     users       │    │ content_types   │    │content_type_    │
│                 │    │                 │    │   fields        │
│ • id            │◄──┐│ • id            │◄───│ • id            │
│ • name          │   ││ • name          │    │ • content_type_id│
│ • email         │   ││ • description   │    │ • name          │
│ • password      │   ││ • type          │    │ • type          │
│ • ...           │   ││ • created_by    │┌──►│ • options       │
└─────────────────┘   │└─────────────────┘│   │ • order         │
                      │                   │   │ • is_required   │
                      │                   │   └─────────────────┘
                      │                   │
                      │ ┌─────────────────┼─────────────────┐
                      │ │                 │                 │
                      │ ▼                 ▼                 ▼
        ┌─────────────┴───────┐  ┌─────────────────┐  ┌─────────────────┐
        │      pages          │  │collection_      │  │   components    │
        │                     │  │   sections      │  │                 │
        │ • id                │  │ • id            │  │ • id            │
        │ • content_type_id   │  │ • content_type_ │  │ • content_type_ │
        │ • title             │  │   id            │  │   id            │
        │ • slug              │  │ • name          │  │ • name          │
        │ • template          │  │ • slug          │  │ • slug          │
        │ • is_active         │  │ • description   │  │ • description   │
        │ • published_at      │  │ • is_active     │  │ • is_active     │
        │ • created_by        │  │ • created_by    │  │ • created_by    │
        └─────────────────────┘  └─────────────────┘  └─────────────────┘
                 │                         │                         │
                 ▼                         ▼                         ▼
        ┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
        │ page_contents   │    │collection_posts │    │component_       │
        │                 │    │                 │    │   contents      │
        │ • id            │    │ • id            │    │ • id            │
        │ • page_id       │    │ • collection_   │    │ • component_id  │
        │ • content_type_ │    │   section_id    │    │ • content_type_ │
        │   field_id      │    │ • title         │    │   field_id      │
        │ • value         │    │ • slug          │    │ • value         │
        └─────────────────┘    │ • excerpt       │    └─────────────────┘
                               │ • is_active     │
                               │ • published_at  │
                               │ • created_by    │
                               └─────────────────┘
                                        │
                                        ▼
                               ┌─────────────────┐
                               │collection_post_ │
                               │   contents      │
                               │                 │
                               │ • id            │
                               │ • collection_   │
                               │   post_id       │
                               │ • content_type_ │
                               │   field_id      │
                               │ • value         │
                               └─────────────────┘
```

## Core Tables

### users
Standard Laravel user table with Jetstream extensions.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) UNSIGNED | Primary key |
| name | varchar(255) | User's full name |
| email | varchar(255) | Unique email address |
| email_verified_at | timestamp | Email verification timestamp |
| password | varchar(255) | Hashed password |
| two_factor_secret | text | 2FA secret |
| two_factor_recovery_codes | text | 2FA recovery codes |
| two_factor_confirmed_at | timestamp | 2FA confirmation timestamp |
| remember_token | varchar(100) | Remember login token |
| current_team_id | bigint(20) UNSIGNED | Current team ID |
| profile_photo_path | varchar(2048) | Profile photo path |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE KEY (email)

### content_types
Defines the structure and type of content entities.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) UNSIGNED | Primary key |
| created_by | bigint(20) UNSIGNED | User who created this content type |
| updated_by | bigint(20) UNSIGNED | User who last updated |
| deleted_by | bigint(20) UNSIGNED | User who soft deleted (if applicable) |
| name | varchar(255) | Content type name |
| description | varchar(255) | Optional description |
| type | varchar(255) | Type enum (page, collection, component) |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Foreign Keys:**
- created_by → users(id)
- updated_by → users(id)
- deleted_by → users(id)

**Indexes:**
- PRIMARY KEY (id)
- INDEX (type)
- INDEX (created_by)

### content_type_fields
Defines the fields available for each content type.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) UNSIGNED | Primary key |
| content_type_id | bigint(20) UNSIGNED | Content type this field belongs to |
| name | varchar(255) | Field name/identifier |
| label | varchar(255) | Human-readable field label |
| type | varchar(255) | Field type (text, textarea, select, etc.) |
| options | json | Field configuration options |
| order | int(11) | Display order |
| is_required | tinyint(1) | Whether field is required |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Foreign Keys:**
- content_type_id → content_types(id) ON DELETE CASCADE

**Indexes:**
- PRIMARY KEY (id)
- INDEX (content_type_id)
- INDEX (order)

**Sample options JSON:**
```json
{
    "placeholder": "Enter your name",
    "max_length": 100,
    "validation": ["required", "string"],
    "choices": [
        {"value": "option1", "label": "Option 1"},
        {"value": "option2", "label": "Option 2"}
    ]
}
```

## Content Tables

### pages
Static pages with custom content structure.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) UNSIGNED | Primary key |
| created_by | bigint(20) UNSIGNED | User who created |
| updated_by | bigint(20) UNSIGNED | User who last updated |
| deleted_by | bigint(20) UNSIGNED | User who deleted (soft delete) |
| content_type_id | bigint(20) UNSIGNED | Content type definition |
| title | varchar(255) | Page title |
| slug | varchar(255) | URL-friendly slug |
| template | varchar(255) | Template file name |
| is_active | tinyint(1) | Published status |
| published_at | datetime | Publication date/time |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Foreign Keys:**
- created_by → users(id)
- updated_by → users(id)
- deleted_by → users(id)
- content_type_id → content_types(id)

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE KEY (slug)
- INDEX (is_active)
- INDEX (published_at)
- INDEX (content_type_id)

### page_contents
Dynamic content values for page fields.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) UNSIGNED | Primary key |
| page_id | bigint(20) UNSIGNED | Page this content belongs to |
| content_type_field_id | bigint(20) UNSIGNED | Field definition |
| value | json | Field value (supports various data types) |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Foreign Keys:**
- page_id → pages(id) ON DELETE CASCADE
- content_type_field_id → content_type_fields(id) ON DELETE CASCADE

**Indexes:**
- PRIMARY KEY (id)
- INDEX (page_id)
- INDEX (content_type_field_id)
- UNIQUE KEY (page_id, content_type_field_id)

### collection_sections
Sections that group collection posts.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) UNSIGNED | Primary key |
| created_by | bigint(20) UNSIGNED | User who created |
| updated_by | bigint(20) UNSIGNED | User who last updated |
| deleted_by | bigint(20) UNSIGNED | User who deleted |
| content_type_id | bigint(20) UNSIGNED | Content type for posts in this section |
| name | varchar(255) | Section name |
| slug | varchar(255) | URL-friendly slug |
| description | text | Section description |
| is_active | tinyint(1) | Active status |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Foreign Keys:**
- created_by → users(id)
- updated_by → users(id)
- deleted_by → users(id)
- content_type_id → content_types(id)

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE KEY (slug)
- INDEX (is_active)
- INDEX (content_type_id)

### collection_posts
Individual posts within collection sections.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) UNSIGNED | Primary key |
| created_by | bigint(20) UNSIGNED | User who created |
| updated_by | bigint(20) UNSIGNED | User who last updated |
| deleted_by | bigint(20) UNSIGNED | User who deleted |
| collection_section_id | bigint(20) UNSIGNED | Section this post belongs to |
| title | varchar(255) | Post title |
| slug | varchar(255) | URL-friendly slug |
| excerpt | text | Post excerpt/summary |
| is_active | tinyint(1) | Published status |
| published_at | datetime | Publication date/time |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Foreign Keys:**
- created_by → users(id)
- updated_by → users(id)
- deleted_by → users(id)
- collection_section_id → collection_sections(id) ON DELETE CASCADE

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE KEY (collection_section_id, slug)
- INDEX (is_active)
- INDEX (published_at)
- INDEX (collection_section_id)

### collection_post_contents
Dynamic content values for collection post fields.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) UNSIGNED | Primary key |
| collection_post_id | bigint(20) UNSIGNED | Post this content belongs to |
| content_type_field_id | bigint(20) UNSIGNED | Field definition |
| value | json | Field value |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Foreign Keys:**
- collection_post_id → collection_posts(id) ON DELETE CASCADE
- content_type_field_id → content_type_fields(id) ON DELETE CASCADE

**Indexes:**
- PRIMARY KEY (id)
- INDEX (collection_post_id)
- INDEX (content_type_field_id)
- UNIQUE KEY (collection_post_id, content_type_field_id)

### components
Reusable content components.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) UNSIGNED | Primary key |
| created_by | bigint(20) UNSIGNED | User who created |
| updated_by | bigint(20) UNSIGNED | User who last updated |
| deleted_by | bigint(20) UNSIGNED | User who deleted |
| content_type_id | bigint(20) UNSIGNED | Content type definition |
| name | varchar(255) | Component name |
| slug | varchar(255) | URL-friendly slug |
| description | text | Component description |
| is_active | tinyint(1) | Active status |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Foreign Keys:**
- created_by → users(id)
- updated_by → users(id)
- deleted_by → users(id)
- content_type_id → content_types(id)

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE KEY (slug)
- INDEX (is_active)
- INDEX (content_type_id)

### component_contents
Dynamic content values for component fields.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) UNSIGNED | Primary key |
| component_id | bigint(20) UNSIGNED | Component this content belongs to |
| content_type_field_id | bigint(20) UNSIGNED | Field definition |
| value | json | Field value |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Foreign Keys:**
- component_id → components(id) ON DELETE CASCADE
- content_type_field_id → content_type_fields(id) ON DELETE CASCADE

**Indexes:**
- PRIMARY KEY (id)
- INDEX (component_id)
- INDEX (content_type_field_id)
- UNIQUE KEY (component_id, content_type_field_id)

## Supporting Tables

### languages
Multi-language support configuration.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) UNSIGNED | Primary key |
| name | varchar(255) | Language name |
| code | varchar(10) | ISO language code |
| is_active | tinyint(1) | Active status |
| is_default | tinyint(1) | Default language flag |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE KEY (code)
- INDEX (is_active)
- INDEX (is_default)

### seo_metas
SEO metadata for content (polymorphic relationship).

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) UNSIGNED | Primary key |
| metable_type | varchar(255) | Model type (Page, CollectionPost, etc.) |
| metable_id | bigint(20) UNSIGNED | Model ID |
| title | varchar(255) | SEO title |
| description | text | SEO description |
| keywords | text | SEO keywords |
| og_title | varchar(255) | Open Graph title |
| og_description | text | Open Graph description |
| og_image | varchar(255) | Open Graph image URL |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Indexes:**
- PRIMARY KEY (id)
- INDEX (metable_type, metable_id)

### tags
Content tagging system.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) UNSIGNED | Primary key |
| name | varchar(255) | Tag name |
| slug | varchar(255) | URL-friendly slug |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE KEY (slug)
- INDEX (name)

### tag_contents
Polymorphic pivot table for content tagging.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) UNSIGNED | Primary key |
| tag_id | bigint(20) UNSIGNED | Tag reference |
| taggable_type | varchar(255) | Model type |
| taggable_id | bigint(20) UNSIGNED | Model ID |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Foreign Keys:**
- tag_id → tags(id) ON DELETE CASCADE

**Indexes:**
- PRIMARY KEY (id)
- INDEX (tag_id)
- INDEX (taggable_type, taggable_id)
- UNIQUE KEY (tag_id, taggable_type, taggable_id)

## Permission Tables (Spatie Laravel Permission)

### permissions
Available system permissions.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) UNSIGNED | Primary key |
| name | varchar(255) | Permission name |
| guard_name | varchar(255) | Guard name |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

### roles
User roles.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) UNSIGNED | Primary key |
| name | varchar(255) | Role name |
| guard_name | varchar(255) | Guard name |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

### model_has_permissions
Direct permission assignments to users.

| Column | Type | Description |
|--------|------|-------------|
| permission_id | bigint(20) UNSIGNED | Permission ID |
| model_type | varchar(255) | Model type (User) |
| model_id | bigint(20) UNSIGNED | Model ID |

### model_has_roles
Role assignments to users.

| Column | Type | Description |
|--------|------|-------------|
| role_id | bigint(20) UNSIGNED | Role ID |
| model_type | varchar(255) | Model type (User) |
| model_id | bigint(20) UNSIGNED | Model ID |

### role_has_permissions
Permissions assigned to roles.

| Column | Type | Description |
|--------|------|-------------|
| permission_id | bigint(20) UNSIGNED | Permission ID |
| role_id | bigint(20) UNSIGNED | Role ID |

## Data Types & JSON Structure

### Field Value Storage

The `value` column in content tables uses JSON to store different data types:

#### Text Fields
```json
"Simple text value"
```

#### Select Fields (Single)
```json
"selected_option"
```

#### Select Fields (Multiple)
```json
["option1", "option2", "option3"]
```

#### File/Image Fields
```json
{
    "url": "https://example.com/storage/files/document.pdf",
    "filename": "document.pdf",
    "mime_type": "application/pdf",
    "size": 1048576
}
```

#### Date/Time Fields
```json
{
    "date": "2025-01-01",
    "formatted": "January 1, 2025"
}
```

#### Rich Text Fields
```json
{
    "html": "<p>Rich <strong>text</strong> content</p>",
    "plain": "Rich text content"
}
```

## Performance Considerations

### Indexing Strategy

1. **Primary Keys**: All tables use auto-incrementing primary keys
2. **Foreign Keys**: Indexed for efficient joins
3. **Slugs**: Unique indexes for fast URL lookups
4. **Status Fields**: Indexed for filtering active/inactive content
5. **Timestamps**: Published dates indexed for chronological queries
6. **Polymorphic Relations**: Compound indexes on type+id

### Query Optimization

1. **Eager Loading**: Use Eloquent relationships to prevent N+1 queries
2. **Pagination**: Implement offset-based pagination for large datasets
3. **Caching**: Cache frequently accessed content types and fields
4. **JSON Columns**: Use JSON extraction functions for efficient querying

### Storage Recommendations

1. **Files**: Store uploaded files outside database, reference by URL
2. **Images**: Implement multiple sizes/formats for responsive delivery
3. **Large Text**: Consider full-text search indexing for content search
4. **Audit Trail**: Keep user tracking fields for content management audit

---

*This schema supports a flexible, scalable CMS that can adapt to various content requirements while maintaining performance and data integrity.*
