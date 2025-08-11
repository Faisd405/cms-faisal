# Database Design Comparison - CMS Faisal

## Current Normalized Approach vs JSON Field Approach

### 1. Current Normalized Design (Recommended)

#### Schema Structure
```sql
-- Pages table
CREATE TABLE pages (
    id BIGINT UNSIGNED PRIMARY KEY,
    content_type_id BIGINT UNSIGNED,
    title VARCHAR(255),
    slug VARCHAR(255),
    is_active BOOLEAN,
    published_at DATETIME,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (content_type_id) REFERENCES content_types(id),
    INDEX idx_slug (slug),
    INDEX idx_active_published (is_active, published_at)
);

-- Separate content table
CREATE TABLE page_contents (
    id BIGINT UNSIGNED PRIMARY KEY,
    page_id BIGINT UNSIGNED,
    content_type_field_id BIGINT UNSIGNED,
    value JSON,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE,
    FOREIGN KEY (content_type_field_id) REFERENCES content_type_fields(id),
    UNIQUE KEY unique_page_field (page_id, content_type_field_id),
    INDEX idx_page_id (page_id)
);
```

#### Query Examples
```sql
-- Get page with content
SELECT 
    p.*,
    ctf.name as field_name,
    ctf.type as field_type,
    pc.value
FROM pages p
JOIN page_contents pc ON p.id = pc.page_id
JOIN content_type_fields ctf ON pc.content_type_field_id = ctf.id
WHERE p.slug = 'homepage';

-- Search pages by content field
SELECT DISTINCT p.*
FROM pages p
JOIN page_contents pc ON p.id = pc.page_id
JOIN content_type_fields ctf ON pc.content_type_field_id = ctf.id
WHERE ctf.name = 'title' 
  AND JSON_UNQUOTE(pc.value) LIKE '%search term%'
  AND p.is_active = 1;
```

---

### 2. Alternative JSON Field Approach

#### Schema Structure
```sql
-- Pages table with JSON content
CREATE TABLE pages (
    id BIGINT UNSIGNED PRIMARY KEY,
    content_type_id BIGINT UNSIGNED,
    title VARCHAR(255),
    slug VARCHAR(255),
    content JSON,  -- All dynamic content in JSON
    is_active BOOLEAN,
    published_at DATETIME,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (content_type_id) REFERENCES content_types(id),
    INDEX idx_slug (slug),
    INDEX idx_active_published (is_active, published_at)
);

-- Add generated columns for searchable fields (MySQL 8.0+)
ALTER TABLE pages 
ADD COLUMN content_title VARCHAR(255) 
GENERATED ALWAYS AS (JSON_UNQUOTE(JSON_EXTRACT(content, '$.title'))) STORED,
ADD INDEX idx_content_title (content_title);
```

#### JSON Content Structure
```json
{
    "title": "Homepage Title",
    "subtitle": "Welcome to our site",
    "featured_image": {
        "url": "https://example.com/image.jpg",
        "alt": "Homepage banner"
    },
    "content": "<p>Page content here...</p>",
    "meta_description": "Page description",
    "custom_field": "Custom value"
}
```

#### Query Examples
```sql
-- Get page with content
SELECT id, title, slug, content, is_active
FROM pages 
WHERE slug = 'homepage';

-- Search in JSON content
SELECT *
FROM pages
WHERE JSON_SEARCH(content, 'one', '%search term%') IS NOT NULL
  AND is_active = 1;

-- Query specific JSON field
SELECT *
FROM pages
WHERE JSON_UNQUOTE(JSON_EXTRACT(content, '$.title')) LIKE '%homepage%';
```

---

## Performance Comparison

### Query Performance Analysis

#### Normalized Approach
```php
// Laravel Eloquent - Optimized with eager loading
$pages = Page::with([
    'contentValues' => function($query) {
        $query->select('page_id', 'content_type_field_id', 'value')
              ->with('field:id,name,type');
    }
])
->where('is_active', true)
->get();

// Result: 2 queries total (with eager loading)
```

#### JSON Approach
```php
// Laravel Eloquent - Single query
$pages = Page::select('id', 'title', 'slug', 'content', 'is_active')
    ->where('is_active', true)
    ->get();

// Result: 1 query, but larger payload
```

### Storage Comparison

#### Normalized Approach
```
Page: 1KB
Content records: 5 × 200B = 1KB
Total per page: ~2KB
```

#### JSON Approach
```
Page with JSON: 1.5KB
Total per page: ~1.5KB
```

---

## Recommended Hybrid Approach

### Best of Both Worlds

```sql
-- Keep normalized structure for queries
CREATE TABLE page_contents (
    id BIGINT UNSIGNED PRIMARY KEY,
    page_id BIGINT UNSIGNED,
    content_type_field_id BIGINT UNSIGNED,
    value JSON,
    -- Add computed JSON for quick access
    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
);

-- Add materialized JSON view for performance
CREATE TABLE page_content_cache (
    page_id BIGINT UNSIGNED PRIMARY KEY,
    content_json JSON,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
);
```

### Implementation Strategy

```php
class Page extends Model
{
    // Keep current relationships
    public function contentValues()
    {
        return $this->hasMany(PageContent::class);
    }
    
    // Add cached JSON accessor
    public function getContentAttribute()
    {
        // Check cache first
        $cached = $this->contentCache;
        if ($cached && $cached->updated_at >= $this->updated_at) {
            return $cached->content_json;
        }
        
        // Build from normalized data
        $content = $this->contentValues()
            ->with('field')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->field->name => $item->value];
            });
            
        // Update cache
        $this->updateContentCache($content);
        
        return $content;
    }
}
```

---

## Recommendation: Enhanced Normalized Approach

### Why Stick with Normalized Design

1. **CMS Requirements**: Content management systems need complex querying
2. **Search Capabilities**: Individual field indexing for better search
3. **Data Integrity**: Foreign key constraints ensure consistency
4. **Performance**: Predictable query performance with proper indexing
5. **Flexibility**: Can add new query patterns without schema changes

### Optimizations to Implement

```php
// 1. Eager Loading Strategy
class PageRepository 
{
    public function getWithContent($filters = [])
    {
        return Page::with([
            'contentValues' => function($query) {
                $query->select('page_id', 'content_type_field_id', 'value')
                      ->with('field:id,name,type,order');
            },
            'contentType:id,name,type'
        ])
        ->when($filters['search'] ?? null, function($query, $search) {
            $query->whereHas('contentValues.field', function($q) use ($search) {
                $q->where('name', 'title')
                  ->where('value', 'LIKE', "%{$search}%");
            });
        })
        ->get();
    }
}

// 2. Caching Strategy
class CachedPageService extends PageService
{
    public function findBySlug($slug)
    {
        return Cache::remember("page.{$slug}", 3600, function() use ($slug) {
            return parent::findBySlug($slug);
        });
    }
}

// 3. Database Indexes
// Add these indexes for better performance
CREATE INDEX idx_page_content_field_value ON page_contents(content_type_field_id, value(100));
CREATE INDEX idx_content_search ON page_contents(page_id, content_type_field_id);
```

### Database View for Common Queries

```sql
-- Create a view for frequently accessed content
CREATE VIEW page_content_flat AS
SELECT 
    p.id,
    p.title,
    p.slug,
    p.is_active,
    p.published_at,
    JSON_OBJECTAGG(ctf.name, pc.value) as content
FROM pages p
LEFT JOIN page_contents pc ON p.id = pc.page_id
LEFT JOIN content_type_fields ctf ON pc.content_type_field_id = ctf.id
GROUP BY p.id, p.title, p.slug, p.is_active, p.published_at;
```

---

## Final Verdict

**Keep the normalized approach** because:

1. ✅ Better for CMS-specific requirements (search, filter, sort)
2. ✅ More maintainable and debuggable
3. ✅ Better data integrity
4. ✅ Easier to optimize performance bottlenecks
5. ✅ More flexible for future requirements

The key is to implement the optimizations I've shown above to mitigate the disadvantages while keeping all the benefits of the normalized design.
