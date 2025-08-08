# Frontend Architecture Documentation - CMS Faisal

## Overview

The frontend of CMS Faisal is built using modern web technologies with Vue.js 3 as the core framework, Inertia.js for seamless server-side rendering integration, and Tailwind CSS for styling. The architecture follows a component-based approach with a clear separation of concerns.

## Technology Stack

### Core Framework
- **Vue.js 3**: Progressive JavaScript framework with Composition API
- **Inertia.js**: Modern monolith approach connecting Laravel and Vue.js
- **Vite**: Fast build tool and development server
- **TypeScript**: Type-safe JavaScript (configuration ready)

### UI & Styling
- **Tailwind CSS**: Utility-first CSS framework
- **Flowbite Vue**: Vue.js components built on Tailwind CSS
- **TinyMCE**: Rich text editor for content creation

### State Management & Data
- **Pinia**: Modern state management for Vue.js
- **Vue Query**: Data fetching and caching library
- **Vee-validate**: Form validation with Zod schemas
- **Axios**: HTTP client for API requests

### Development Tools
- **ESLint**: Code linting with Vue.js rules
- **Prettier**: Code formatting
- **PostCSS**: CSS processing with Autoprefixer

## Project Structure

```
resources/js/
├── app.js                 # Main application entry point
├── app.ts                 # TypeScript configuration file
├── bootstrap.js           # Bootstrap configuration
├── ziggy.js              # Laravel route helpers
├── Components/           # Reusable Vue components
│   ├── Form/            # Form-related components
│   ├── Layout/          # Layout components
│   ├── UI/              # Generic UI components
│   └── ...
├── Helpers/             # Utility functions and helpers
├── Layouts/             # Page layouts
│   ├── AppLayout.vue    # Main app layout
│   ├── AuthLayout.vue   # Authentication layout
│   └── GuestLayout.vue  # Guest layout
├── libs/                # Third-party library configurations
├── Pages/               # Inertia.js pages
│   ├── Auth/           # Authentication pages
│   ├── Dashboard/      # Dashboard pages
│   ├── ContentTypes/   # Content type management
│   ├── Pages/          # Page management
│   ├── Collections/    # Collection management
│   └── ...
├── store/              # Pinia stores
│   ├── auth.js         # Authentication store
│   ├── content.js      # Content management store
│   └── ...
└── utils/              # Utility functions
    ├── api.js          # API helpers
    ├── validation.js   # Validation schemas
    └── ...
```

## Component Architecture

### Base Components

#### Layout Components
Located in `Layouts/`, these provide the overall page structure:

- **AppLayout.vue**: Main authenticated app layout with navigation
- **AuthLayout.vue**: Layout for authentication pages
- **GuestLayout.vue**: Layout for public pages

#### Reusable Components
Located in `Components/`, organized by functionality:

```
Components/
├── Form/
│   ├── FormInput.vue        # Text input component
│   ├── FormTextarea.vue     # Textarea component
│   ├── FormSelect.vue       # Select dropdown component
│   ├── FormCheckbox.vue     # Checkbox component
│   ├── FormRadio.vue        # Radio button component
│   ├── FormFileUpload.vue   # File upload component
│   └── FormDatePicker.vue   # Date picker component
├── Layout/
│   ├── Sidebar.vue          # Navigation sidebar
│   ├── Header.vue           # Page header
│   ├── Breadcrumb.vue       # Breadcrumb navigation
│   └── Footer.vue           # Page footer
├── UI/
│   ├── Button.vue           # Button component
│   ├── Modal.vue            # Modal dialog
│   ├── Card.vue             # Card container
│   ├── DataTable.vue        # Data table with sorting/pagination
│   ├── LoadingSpinner.vue   # Loading indicator
│   └── Alert.vue            # Alert/notification component
└── Content/
    ├── ContentEditor.vue    # Dynamic content editor
    ├── FieldRenderer.vue    # Dynamic field renderer
    └── ContentPreview.vue   # Content preview component
```

### Page Components

#### Authentication Pages
```
Pages/Auth/
├── Login.vue               # Login page
├── Register.vue            # Registration page
├── ForgotPassword.vue      # Password reset request
├── ResetPassword.vue       # Password reset form
└── VerifyEmail.vue         # Email verification
```

#### Main Application Pages
```
Pages/
├── Dashboard.vue           # Main dashboard
├── Welcome.vue            # Landing page
├── ContentTypes/
│   ├── Index.vue          # Content types list
│   ├── Create.vue         # Create content type
│   ├── Edit.vue           # Edit content type
│   └── Show.vue           # View content type
├── Pages/
│   ├── Index.vue          # Pages list
│   ├── Create.vue         # Create page
│   ├── Edit.vue           # Edit page
│   └── Show.vue           # View page
├── Collections/
│   ├── Sections/
│   │   ├── Index.vue      # Sections list
│   │   ├── Create.vue     # Create section
│   │   └── Edit.vue       # Edit section
│   └── Posts/
│       ├── Index.vue      # Posts list
│       ├── Create.vue     # Create post
│       └── Edit.vue       # Edit post
└── Components/
    ├── Index.vue          # Components list
    ├── Create.vue         # Create component
    └── Edit.vue           # Edit component
```

## State Management (Pinia)

### Store Structure

#### Authentication Store (`store/auth.js`)
```javascript
import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    permissions: [],
    roles: []
  }),
  
  getters: {
    isAuthenticated: (state) => !!state.user,
    hasPermission: (state) => (permission) => 
      state.permissions.includes(permission),
    hasRole: (state) => (role) => 
      state.roles.includes(role)
  },
  
  actions: {
    setUser(user) {
      this.user = user
    },
    setPermissions(permissions) {
      this.permissions = permissions
    },
    logout() {
      this.user = null
      this.permissions = []
      this.roles = []
    }
  }
})
```

#### Content Store (`store/content.js`)
```javascript
import { defineStore } from 'pinia'

export const useContentStore = defineStore('content', {
  state: () => ({
    contentTypes: [],
    currentContentType: null,
    fields: [],
    loading: false
  }),
  
  getters: {
    getContentTypeById: (state) => (id) => 
      state.contentTypes.find(ct => ct.id === id),
    getFieldsByType: (state) => (typeId) => 
      state.fields.filter(field => field.content_type_id === typeId)
  },
  
  actions: {
    async fetchContentTypes() {
      this.loading = true
      try {
        const response = await api.get('/content-types')
        this.contentTypes = response.data
      } finally {
        this.loading = false
      }
    },
    
    setCurrentContentType(contentType) {
      this.currentContentType = contentType
      this.fields = contentType.fields || []
    }
  }
})
```

## Form Handling & Validation

### Vee-validate with Zod

#### Validation Schema Example
```javascript
// utils/validation.js
import { z } from 'zod'

export const contentTypeSchema = z.object({
  name: z.string()
    .min(1, 'Name is required')
    .max(255, 'Name must be less than 255 characters'),
  description: z.string()
    .max(500, 'Description must be less than 500 characters')
    .optional(),
  type: z.enum(['page', 'collection', 'component'], {
    required_error: 'Please select a content type'
  })
})

export const fieldSchema = z.object({
  name: z.string()
    .min(1, 'Field name is required')
    .regex(/^[a-zA-Z0-9_]+$/, 'Field name can only contain letters, numbers, and underscores'),
  label: z.string()
    .min(1, 'Field label is required'),
  type: z.enum(['text', 'textarea', 'select', 'checkbox', 'radio', 'file', 'image', 'date', 'time', 'datetime']),
  is_required: z.boolean().default(false),
  options: z.record(z.any()).default({})
})
```

#### Form Component Example
```vue
<!-- Components/Form/ContentTypeForm.vue -->
<template>
  <form @submit="onSubmit" class="space-y-6">
    <div>
      <label for="name" class="block text-sm font-medium text-gray-700">
        Name
      </label>
      <input
        v-model="values.name"
        :class="{ 'border-red-500': errors.name }"
        type="text"
        id="name"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
      />
      <p v-if="errors.name" class="mt-1 text-sm text-red-600">
        {{ errors.name }}
      </p>
    </div>

    <div>
      <label for="type" class="block text-sm font-medium text-gray-700">
        Type
      </label>
      <select
        v-model="values.type"
        :class="{ 'border-red-500': errors.type }"
        id="type"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
      >
        <option value="">Select a type</option>
        <option value="page">Page</option>
        <option value="collection">Collection</option>
        <option value="component">Component</option>
      </select>
      <p v-if="errors.type" class="mt-1 text-sm text-red-600">
        {{ errors.type }}
      </p>
    </div>

    <button
      type="submit"
      :disabled="!meta.valid || isSubmitting"
      class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
    >
      <LoadingSpinner v-if="isSubmitting" class="mr-2" />
      {{ isSubmitting ? 'Saving...' : 'Save' }}
    </button>
  </form>
</template>

<script setup>
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import { contentTypeSchema } from '@/utils/validation'
import LoadingSpinner from '@/Components/UI/LoadingSpinner.vue'

const props = defineProps({
  initialValues: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['submit'])

const { values, errors, meta, handleSubmit, isSubmitting } = useForm({
  validationSchema: toTypedSchema(contentTypeSchema),
  initialValues: props.initialValues
})

const onSubmit = handleSubmit(async (values) => {
  emit('submit', values)
})
</script>
```

## Dynamic Content Rendering

### Field Renderer Component

```vue
<!-- Components/Content/FieldRenderer.vue -->
<template>
  <div class="space-y-4">
    <component
      :is="getFieldComponent(field.type)"
      v-for="field in fields"
      :key="field.id"
      :field="field"
      :model-value="getValue(field.name)"
      @update:model-value="updateValue(field.name, $event)"
    />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import FormInput from '@/Components/Form/FormInput.vue'
import FormTextarea from '@/Components/Form/FormTextarea.vue'
import FormSelect from '@/Components/Form/FormSelect.vue'
import FormCheckbox from '@/Components/Form/FormCheckbox.vue'
import FormRadio from '@/Components/Form/FormRadio.vue'
import FormFileUpload from '@/Components/Form/FormFileUpload.vue'
import FormDatePicker from '@/Components/Form/FormDatePicker.vue'

const props = defineProps({
  fields: {
    type: Array,
    required: true
  },
  modelValue: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['update:modelValue'])

const fieldComponents = {
  text: FormInput,
  textarea: FormTextarea,
  select: FormSelect,
  checkbox: FormCheckbox,
  radio: FormRadio,
  file: FormFileUpload,
  image: FormFileUpload,
  date: FormDatePicker,
  time: FormDatePicker,
  datetime: FormDatePicker
}

const getFieldComponent = (type) => {
  return fieldComponents[type] || FormInput
}

const getValue = (fieldName) => {
  return props.modelValue[fieldName] || ''
}

const updateValue = (fieldName, value) => {
  emit('update:modelValue', {
    ...props.modelValue,
    [fieldName]: value
  })
}
</script>
```

## API Integration

### API Helper Functions

```javascript
// utils/api.js
import axios from 'axios'
import { router } from '@inertiajs/vue3'

const api = axios.create({
  baseURL: '/api',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  }
})

// Request interceptor for adding auth token
api.interceptors.request.use(
  (config) => {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    if (token) {
      config.headers['X-CSRF-TOKEN'] = token
    }
    return config
  },
  (error) => Promise.reject(error)
)

// Response interceptor for handling errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      router.visit('/login')
    }
    return Promise.reject(error)
  }
)

export default api

// API service functions
export const contentTypesApi = {
  getAll: () => api.get('/content-types'),
  getById: (id) => api.get(`/content-types/${id}`),
  create: (data) => api.post('/content-types', data),
  update: (id, data) => api.put(`/content-types/${id}`, data),
  delete: (id) => api.delete(`/content-types/${id}`)
}

export const pagesApi = {
  getAll: (params) => api.get('/pages', { params }),
  getById: (id) => api.get(`/pages/${id}`),
  create: (data) => api.post('/pages', data),
  update: (id, data) => api.put(`/pages/${id}`, data),
  updateContent: (id, content) => api.put(`/pages/${id}/content`, content),
  delete: (id) => api.delete(`/pages/${id}`)
}
```

## Routing with Inertia.js

### Route Helpers with Ziggy

```javascript
// Using Laravel routes in Vue components
import { router } from '@inertiajs/vue3'

// Navigate to pages
router.visit(route('pages.index'))
router.visit(route('pages.edit', { pageId: 1 }))

// Navigate with data
router.post(route('pages.store'), formData)
router.put(route('pages.update', { pageId: 1 }), formData)

// Check current route
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const isCurrentRoute = (routeName) => {
  return page.component === routeName
}
```

## Performance Optimization

### Code Splitting
```javascript
// Lazy load page components
const pages = import.meta.glob('./Pages/**/*.vue')

// Dynamic imports for large components
const TinyMCE = defineAsyncComponent(() => import('@tinymce/tinymce-vue'))
```

### Caching Strategy
```javascript
// Vue Query for API caching
import { useQuery } from 'vue-query'

export function useContentTypes() {
  return useQuery(
    ['content-types'],
    () => contentTypesApi.getAll(),
    {
      staleTime: 5 * 60 * 1000, // 5 minutes
      cacheTime: 10 * 60 * 1000 // 10 minutes
    }
  )
}
```

### Bundle Optimization
```javascript
// vite.config.js
export default defineConfig({
  build: {
    rollupOptions: {
      output: {
        manualChunks: {
          vendor: ['vue', 'axios'],
          ui: ['flowbite-vue', '@tinymce/tinymce-vue'],
          utils: ['lodash', 'date-fns']
        }
      }
    }
  }
})
```

## Testing Strategy

### Component Testing
```javascript
// Example component test
import { mount } from '@vue/test-utils'
import { describe, it, expect } from 'vitest'
import FormInput from '@/Components/Form/FormInput.vue'

describe('FormInput', () => {
  it('renders with correct props', () => {
    const wrapper = mount(FormInput, {
      props: {
        label: 'Test Label',
        modelValue: 'test value',
        required: true
      }
    })

    expect(wrapper.find('label').text()).toBe('Test Label')
    expect(wrapper.find('input').element.value).toBe('test value')
    expect(wrapper.find('input').attributes('required')).toBeDefined()
  })

  it('emits update:modelValue on input', async () => {
    const wrapper = mount(FormInput)
    const input = wrapper.find('input')
    
    await input.setValue('new value')
    
    expect(wrapper.emitted('update:modelValue')).toBeTruthy()
    expect(wrapper.emitted('update:modelValue')[0]).toEqual(['new value'])
  })
})
```

### E2E Testing with Cypress
```javascript
// cypress/integration/content-management.spec.js
describe('Content Management', () => {
  beforeEach(() => {
    cy.login() // Custom command for authentication
  })

  it('creates a new content type', () => {
    cy.visit('/content-types/create')
    cy.get('[data-cy="name-input"]').type('Test Content Type')
    cy.get('[data-cy="type-select"]').select('page')
    cy.get('[data-cy="submit-button"]').click()
    
    cy.url().should('include', '/content-types')
    cy.contains('Test Content Type').should('be.visible')
  })
})
```

## Build & Deployment

### Development Build
```bash
# Start development server with hot reload
npm run dev

# Lint code
npm run eslint

# Fix linting issues
npm run eslint-fix
```

### Production Build
```bash
# Build for production
npm run build

# Build and analyze bundle
npm run build -- --analyze
```

### Environment Configuration
```javascript
// .env variables accessible in frontend
const appName = import.meta.env.VITE_APP_NAME
const apiUrl = import.meta.env.VITE_API_URL
const isDevelopment = import.meta.env.DEV
const isProduction = import.meta.env.PROD
```

---

*This frontend architecture provides a scalable, maintainable, and modern development experience for managing content in the CMS Faisal application.*
