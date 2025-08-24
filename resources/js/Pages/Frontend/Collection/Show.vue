<template>
    <FrontendLayout>
        <Head>
            <title>{{ post.title }} - {{ section.title }} - {{ $page.props.site.name }}</title>
            <meta name="description" :content="getPostExcerpt(post)">
            <meta property="og:title" :content="post.title">
            <meta property="og:description" :content="getPostExcerpt(post)">
            <meta property="og:image" :content="getPostImage(post)">
            <meta property="og:type" content="article">
            <meta property="og:url" :content="$page.url">
            <meta property="article:published_time" :content="post.published_at">
            <meta property="article:modified_time" :content="post.updated_at">
            <meta property="article:section" :content="section.title">
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:title" :content="post.title">
            <meta name="twitter:description" :content="getPostExcerpt(post)">
            <meta name="twitter:image" :content="getPostImage(post)">
        </Head>

        <div class="min-h-screen bg-white">
            <!-- Breadcrumb -->
            <nav class="bg-gray-50 py-4">
                <div class="container mx-auto px-4">
                    <ol class="flex items-center space-x-2 text-sm text-gray-600">
                        <li>
                            <Link :href="route('home')" class="hover:text-gray-900">Home</Link>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <Link :href="route('collection.index', section.slug)" class="hover:text-gray-900">
                                {{ section.title }}
                            </Link>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-900 font-medium">{{ post.title }}</span>
                        </li>
                    </ol>
                </div>
            </nav>

            <!-- Article Header -->
            <header class="py-12">
                <div class="container mx-auto px-4">
                    <div class="max-w-4xl mx-auto text-center">
                        <!-- Category & Date -->
                        <div class="flex justify-center items-center space-x-4 text-sm text-gray-600 mb-4">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-medium">
                                {{ section.title }}
                            </span>
                            <time :datetime="post.published_at">
                                {{ formatDate(post.published_at || post.created_at) }}
                            </time>
                        </div>

                        <!-- Title -->
                        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">{{ post.title }}</h1>

                        <!-- Excerpt -->
                        <p v-if="getPostExcerpt(post)" class="text-xl text-gray-600 max-w-3xl mx-auto">
                            {{ getPostExcerpt(post) }}
                        </p>
                    </div>
                </div>
            </header>

            <!-- Featured Image -->
            <div v-if="getPostImage(post)" class="mb-12">
                <div class="container mx-auto px-4">
                    <div class="max-w-4xl mx-auto">
                        <img 
                            :src="getPostImage(post)" 
                            :alt="post.title"
                            class="w-full h-96 object-cover rounded-lg shadow-lg"
                        >
                    </div>
                </div>
            </div>

            <!-- Article Content -->
            <article class="pb-16">
                <div class="container mx-auto px-4">
                    <div class="max-w-4xl mx-auto">
                        <!-- Dynamic Content Rendering -->
                        <div v-if="content" class="prose prose-lg max-w-none">
                            <div v-for="(value, key) in content" :key="key" class="content-section">
                                <!-- Skip already rendered fields -->
                                <template v-if="!['featured_image', 'image', 'thumbnail', 'cover', 'excerpt', 'description', 'summary'].includes(key)">
                                    <!-- Rich Text Content -->
                                    <div v-if="typeof value === 'string' && value.length > 0" v-html="value"></div>
                                    
                                    <!-- Additional Images -->
                                    <div v-else-if="isImageField(key, value)" class="my-8 text-center">
                                        <img 
                                            :src="value" 
                                            :alt="key"
                                            class="max-w-full h-auto rounded-lg shadow-lg mx-auto"
                                        >
                                    </div>
                                    
                                    <!-- Simple Values -->
                                    <div v-else-if="value && typeof value !== 'object'" class="bg-gray-50 p-6 rounded-lg my-6">
                                        <h3 class="text-lg font-semibold mb-2 capitalize">{{ key.replace(/_/g, ' ') }}</h3>
                                        <p class="text-gray-700">{{ value }}</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Default Content if no dynamic content -->
                        <div v-else class="prose prose-lg max-w-none">
                            <p class="text-gray-600 text-center py-12">
                                This post content is being configured. Please check back soon.
                            </p>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Article Footer -->
            <footer class="border-t border-gray-200 py-8">
                <div class="container mx-auto px-4">
                    <div class="max-w-4xl mx-auto">
                        <!-- Share Buttons -->
                        <div class="flex justify-center space-x-4 mb-8">
                            <a 
                                :href="`https://twitter.com/intent/tweet?text=${encodeURIComponent(post.title)}&url=${encodeURIComponent($page.url)}`"
                                target="_blank"
                                class="flex items-center space-x-2 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                                <span>Tweet</span>
                            </a>
                            <a 
                                :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent($page.url)}`"
                                target="_blank"
                                class="flex items-center space-x-2 bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                <span>Share</span>
                            </a>
                            <a 
                                :href="`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent($page.url)}`"
                                target="_blank"
                                class="flex items-center space-x-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                                <span>Share</span>
                            </a>
                        </div>

                        <!-- Navigation -->
                        <div class="flex justify-between items-center">
                            <Link 
                                :href="route('collection.index', section.slug)"
                                class="flex items-center text-blue-600 hover:text-blue-800 font-semibold transition-colors"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Back to {{ section.title }}
                            </Link>
                        </div>
                    </div>
                </div>
            </footer>

            <!-- Related Posts -->
            <section v-if="relatedPosts.length > 0" class="bg-gray-50 py-16">
                <div class="container mx-auto px-4">
                    <div class="max-w-6xl mx-auto">
                        <h2 class="text-3xl font-bold text-center mb-12">Related {{ section.title }}</h2>
                        <div class="grid md:grid-cols-3 gap-8">
                            <article 
                                v-for="relatedPost in relatedPosts" 
                                :key="relatedPost.id"
                                class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow"
                            >
                                <div class="h-48 bg-gray-300">
                                    <img 
                                        v-if="getPostImage(relatedPost)" 
                                        :src="getPostImage(relatedPost)" 
                                        :alt="relatedPost.title"
                                        class="w-full h-full object-cover"
                                    >
                                    <div v-else class="w-full h-full bg-gray-300 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <h3 class="text-xl font-semibold mb-2 line-clamp-2">{{ relatedPost.title }}</h3>
                                    <p class="text-gray-600 mb-4 line-clamp-3">{{ getPostExcerpt(relatedPost) }}</p>
                                    <Link 
                                        :href="route('collection.show', [section.slug, relatedPost.slug])"
                                        class="text-blue-600 font-semibold hover:text-blue-800 transition-colors"
                                    >
                                        Read More →
                                    </Link>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </FrontendLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import FrontendLayout from '@/Layouts/FrontendLayout.vue'

const props = defineProps({
    section: Object,
    post: Object,
    content: Object,
    relatedPosts: Array,
    language: Object,
    availableLanguages: Array,
})

const getPostImage = (post) => {
    const content = post.content || {}
    return content.featured_image || content.image || content.thumbnail || content.cover || null
}

const getPostExcerpt = (post) => {
    const content = post.content || {}
    return content.excerpt || content.description || content.summary || 'Click to read more...'
}

const isImageField = (key, value) => {
    const imageKeywords = ['image', 'photo', 'picture', 'banner', 'thumbnail', 'cover']
    const hasImageKeyword = imageKeywords.some(keyword => key.toLowerCase().includes(keyword))
    const isValidUrl = typeof value === 'string' && (value.startsWith('http') || value.startsWith('/'))
    const hasImageExtension = typeof value === 'string' && /\.(jpg|jpeg|png|gif|webp|svg)$/i.test(value)
    
    return hasImageKeyword && isValidUrl && (hasImageExtension || value.length > 10)
}

const formatDate = (dateString) => {
    if (!dateString) return ''
    
    try {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        })
    } catch (error) {
        return dateString
    }
}
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>