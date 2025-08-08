# CMS Faisal - Headless Content Management System

## Overview

CMS Faisal is a modern headless Content Management System built with Laravel 11 and Vue.js 3. It provides a flexible and scalable solution for managing dynamic content through a clean API and intuitive admin interface.

## Table of Contents

1. [System Architecture](#system-architecture)
2. [Technology Stack](#technology-stack)
3. [Core Features](#core-features)
4. [Content Management](#content-management)
5. [API Reference](#api-reference)
6. [Database Schema](#database-schema)
7. [Installation & Setup](#installation--setup)
8. [Development Guidelines](#development-guidelines)

## System Architecture

CMS Faisal follows a headless CMS architecture pattern where:

- **Backend (Laravel)**: Provides RESTful APIs, content management, user authentication, and data persistence
- **Frontend (Vue.js + Inertia.js)**: Admin dashboard for content management
- **API Layer**: Public APIs for content consumption by external applications

### Architecture Diagram
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │    Backend      │    │    Database     │
│   (Vue.js +     │◄──►│    (Laravel)    │◄──►│    (MySQL)      │
│   Inertia.js)   │    │                 │    │                 │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                        │
         │                        │
         ▼                        ▼
┌─────────────────┐    ┌─────────────────┐
│   Admin Panel   │    │   Public API    │
│                 │    │   Endpoints     │
└─────────────────┘    └─────────────────┘
```

## Technology Stack

### Backend
- **Framework**: Laravel 11
- **PHP Version**: 8.2+
- **Authentication**: Laravel Jetstream with Sanctum
- **Authorization**: Spatie Laravel Permission
- **API Documentation**: Dedoc Scramble

### Frontend
- **Framework**: Vue.js 3
- **Build Tool**: Vite
- **Routing**: Inertia.js
- **State Management**: Pinia
- **UI Components**: Flowbite Vue
- **Form Validation**: Vee-validate with Zod
- **Styling**: Tailwind CSS

### Additional Libraries
- **Rich Text Editor**: TinyMCE
- **Charts**: Chart.js + Vue Chart.js
- **HTTP Client**: Axios
- **File Uploads**: Vue Image component
- **Notifications**: Vue Toastification

## Core Features

### 1. Content Type Management
- **Dynamic Content Types**: Create custom content structures (Page, Collection, Component)
- **Field Types**: Support for various field types (text, textarea, select, checkbox, radio, file, image, date, time, datetime)
- **Field Ordering**: Configurable field order within content types

### 2. Page Management
- **Dynamic Pages**: Create pages based on content types
- **SEO Support**: Built-in SEO meta management
- **Template System**: Flexible template assignment
- **Publishing Control**: Draft/publish workflow with scheduling

### 3. Collection System
- **Sections**: Organize content into sections
- **Posts**: Create posts within sections
- **Hierarchical Structure**: Nested content organization

### 4. Component System
- **Reusable Components**: Create reusable content components
- **Dynamic Content**: Components with flexible field structures

### 5. Localization
- **Multi-language Support**: Manage content in multiple languages
- **Language Management**: Dynamic language creation and management

### 6. User Management
- **Role-based Access**: Integration with Spatie Permission package
- **User Tracking**: Track content creation, updates, and deletions
- **Authentication**: Secure login with Laravel Jetstream

## Content Management

### Content Types

Content Types define the structure of your content. The system supports three main types:

1. **PAGE**: Static pages with custom fields
2. **COLLECTION**: Dynamic collections with posts
3. **COMPONENT**: Reusable content components

#### Field Types Available:
- `text` - Single line text input
- `textarea` - Multi-line text input  
- `select` - Dropdown selection
- `checkbox` - Multiple choice checkboxes
- `radio` - Single choice radio buttons
- `file` - File upload
- `image` - Image upload with preview
- `date` - Date picker
- `time` - Time picker
- `datetime` - Date and time picker

### Content Structure

```
Content Type
├── Fields (ordered)
│   ├── Field Name
│   ├── Field Type
│   ├── Field Options
│   └── Field Order
└── Content Items
    ├── Pages (for PAGE type)
    ├── Collections (for COLLECTION type)
    └── Components (for COMPONENT type)
```

## API Reference

### Public API Endpoints

#### Pages
- `GET /api/pages` - List all published pages
- `GET /api/pages/{slug}` - Get specific page by slug

#### Collections  
- `GET /api/collection/sections` - List all sections
- `GET /api/collection/sections/{sectionSlug}` - Get specific section
- `GET /api/collection/sections/{sectionSlug}/posts` - Get posts in section
- `GET /api/collection/sections/{sectionSlug}/posts/{postSlug}` - Get specific post

#### Languages
- `GET /api/languages` - List available languages

### Admin Routes (Protected)

#### Content Types
- `GET /content-types` - List content types
- `POST /content-types` - Create content type
- `GET /content-types/{id}/edit` - Edit content type
- `PUT /content-types/{id}` - Update content type
- `DELETE /content-types/{id}` - Delete content type

#### Content Type Fields
- `POST /content-types/{id}/fields` - Add field to content type
- `PUT /content-types/{id}/fields/{fieldId}` - Update field
- `DELETE /content-types/{id}/fields/{fieldId}` - Delete field

#### Pages
- `GET /pages` - List pages
- `POST /pages` - Create page
- `GET /pages/{id}/edit` - Edit page
- `PUT /pages/{id}` - Update page
- `DELETE /pages/{id}` - Delete page
- `PUT /pages/{id}/content` - Update page content

#### Collections
- `GET /collection/sections` - List sections
- `POST /collection/sections` - Create section
- `GET /collection/sections/{id}/edit` - Edit section
- `PUT /collection/sections/{id}` - Update section
- `DELETE /collection/sections/{id}` - Delete section

#### Posts
- `GET /collection/sections/{sectionId}/posts` - List posts in section
- `POST /collection/sections/{sectionId}/posts` - Create post
- `GET /collection/sections/{sectionId}/posts/{id}/edit` - Edit post
- `PUT /collection/sections/{sectionId}/posts/{id}` - Update post
- `DELETE /collection/sections/{sectionId}/posts/{id}` - Delete post

## Database Schema

### Core Tables

#### content_types
- `id` - Primary key
- `name` - Content type name
- `description` - Content type description
- `type` - Type enum (page, collection, component)
- `created_by`, `updated_by`, `deleted_by` - User tracking
- `timestamps`

#### content_type_fields
- `id` - Primary key
- `content_type_id` - Foreign key to content_types
- `name` - Field name
- `type` - Field type
- `options` - Field configuration (JSON)
- `order` - Field display order
- `is_required` - Required flag
- `timestamps`

#### pages
- `id` - Primary key
- `content_type_id` - Foreign key to content_types
- `title` - Page title
- `slug` - URL slug
- `template` - Template name
- `is_active` - Published status
- `published_at` - Publication date
- `created_by`, `updated_by`, `deleted_by` - User tracking
- `timestamps`

#### page_contents
- `id` - Primary key
- `page_id` - Foreign key to pages
- `content_type_field_id` - Foreign key to content_type_fields
- `value` - Field value (JSON)
- `timestamps`

#### collection_sections
- `id` - Primary key
- `content_type_id` - Foreign key to content_types
- `name` - Section name
- `slug` - URL slug
- `description` - Section description
- `is_active` - Active status
- `created_by`, `updated_by`, `deleted_by` - User tracking
- `timestamps`

#### collection_posts
- `id` - Primary key
- `collection_section_id` - Foreign key to collection_sections
- `title` - Post title
- `slug` - URL slug
- `excerpt` - Post excerpt
- `is_active` - Published status
- `published_at` - Publication date
- `created_by`, `updated_by`, `deleted_by` - User tracking
- `timestamps`

#### components
- `id` - Primary key
- `content_type_id` - Foreign key to content_types
- `name` - Component name
- `slug` - URL slug
- `description` - Component description
- `is_active` - Active status
- `created_by`, `updated_by`, `deleted_by` - User tracking
- `timestamps`

### Supporting Tables

#### languages
- `id` - Primary key
- `name` - Language name
- `code` - Language code (ISO)
- `is_active` - Active status
- `is_default` - Default language flag
- `timestamps`

#### seo_metas
- `id` - Primary key
- `metable_type` - Polymorphic type
- `metable_id` - Polymorphic ID
- `title` - SEO title
- `description` - SEO description
- `keywords` - SEO keywords
- `og_title` - Open Graph title
- `og_description` - Open Graph description
- `og_image` - Open Graph image
- `timestamps`

#### tags
- `id` - Primary key
- `name` - Tag name
- `slug` - Tag slug
- `timestamps`

#### tag_contents
- `id` - Primary key
- `tag_id` - Foreign key to tags
- `taggable_type` - Polymorphic type
- `taggable_id` - Polymorphic ID
- `timestamps`

## Installation & Setup

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm/pnpm
- MySQL/MariaDB
- Web server (Apache/Nginx)

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/Faisd405/cms-faisal.git
   cd cms-faisal
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   pnpm install
   # or
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database configuration**
   Update `.env` file with database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=cms_faisal
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed database (optional)**
   ```bash
   php artisan db:seed
   ```

8. **Build frontend assets**
   ```bash
   pnpm run build
   # or for development
   pnpm run dev
   ```

9. **Serve the application**
   ```bash
   php artisan serve
   ```

### Development Setup

For development with hot reload:

1. **Start the backend server**
   ```bash
   php artisan serve
   ```

2. **Start the frontend development server**
   ```bash
   pnpm run dev
   ```

## Development Guidelines

### Code Organization

#### Backend Structure
- `app/Base/` - Base classes for common functionality
- `app/Models/` - Eloquent models organized by feature
- `app/Http/Controllers/` - Controllers for admin and API
- `app/Repositories/` - Repository pattern implementation
- `app/Services/` - Business logic services
- `app/Enums/` - Enum definitions
- `app/Traits/` - Reusable traits

#### Frontend Structure
- `resources/js/Pages/` - Inertia.js pages
- `resources/js/Components/` - Reusable Vue components
- `resources/js/Layouts/` - Layout components
- `resources/js/store/` - Pinia stores
- `resources/js/utils/` - Utility functions

### Best Practices

#### Backend
1. **Use Repository Pattern** - Separate data access logic
2. **Service Layer** - Keep business logic in services
3. **Base Classes** - Extend base classes for common functionality
4. **User Tracking** - Use `UseTrackUserActions` trait for audit trails
5. **Validation** - Use Form Request classes for validation
6. **API Resources** - Use API resources for consistent responses

#### Frontend
1. **Component Composition** - Break down complex components
2. **State Management** - Use Pinia for global state
3. **Form Validation** - Use Vee-validate with Zod schemas
4. **Error Handling** - Implement consistent error handling
5. **Loading States** - Show loading indicators for async operations
6. **Responsive Design** - Use Tailwind CSS for responsive layouts

### API Design Principles

1. **RESTful URLs** - Follow REST conventions
2. **Consistent Responses** - Use standard response formats
3. **Error Handling** - Return meaningful error messages
4. **Pagination** - Implement pagination for list endpoints
5. **Filtering** - Support filtering and searching
6. **Versioning** - Consider API versioning for breaking changes

### Security Considerations

1. **Authentication** - Use Laravel Sanctum for API authentication
2. **Authorization** - Implement role-based permissions
3. **Input Validation** - Validate all user inputs
4. **CSRF Protection** - Enable CSRF protection for forms
5. **SQL Injection** - Use Eloquent ORM to prevent SQL injection
6. **XSS Prevention** - Sanitize output and use Content Security Policy

---

*This documentation is maintained by the development team. Last updated: August 8, 2025*
