<template>
    <FrontendLayout>
        <Head>
            <title>{{ seoMeta.title }}</title>
            <meta name="description" :content="seoMeta.description">
            <meta name="keywords" :content="seoMeta.keywords">
            <meta property="og:title" :content="seoMeta.title">
            <meta property="og:description" :content="seoMeta.description">
            <meta property="og:image" :content="seoMeta.image">
            <meta property="og:url" :content="seoMeta.url">
            <meta property="og:type" content="website">
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:title" :content="seoMeta.title">
            <meta name="twitter:description" :content="seoMeta.description">
            <meta name="twitter:image" :content="seoMeta.image">
        </Head>

        <div class="min-h-screen bg-white">
            <!-- Hero Section -->
            <section class="bg-gradient-to-r from-blue-600 to-purple-700 text-white py-20">
                <div class="container mx-auto px-4 text-center">
                    <h1 class="text-5xl font-bold mb-6">{{ page?.title || 'Welcome to Our Website' }}</h1>
                    <p class="text-xl mb-8 max-w-3xl mx-auto">
                        {{ getContentValue('description') || 'Discover amazing content and experiences with our powerful CMS platform.' }}
                    </p>
                    <div class="flex justify-center gap-4">
                        <Link 
                            :href="route('collection.index', 'blog')" 
                            class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors"
                        >
                            Explore Blog
                        </Link>
                        <Link 
                            href="/about" 
                            class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors"
                        >
                            Learn More
                        </Link>
                    </div>
                </div>
            </section>

            <!-- Content Sections -->
            <div class="py-16">
                <div class="container mx-auto px-4">
                    <!-- Dynamic Content Rendering -->
                    <div v-if="content" class="space-y-12">
                        <div v-for="(value, key) in content" :key="key" class="content-section">
                            <!-- Text Content -->
                            <div v-if="typeof value === 'string'" class="prose prose-lg max-w-4xl mx-auto">
                                <div v-html="value"></div>
                            </div>
                            
                            <!-- Page Reference -->
                            <div v-else-if="value && value.content" class="bg-gray-50 p-8 rounded-xl">
                                <h3 class="text-2xl font-bold mb-4">{{ value.title }}</h3>
                                <div class="prose prose-lg">
                                    <div v-html="value.content[Object.keys(value.content)[0]]"></div>
                                </div>
                            </div>
                            
                            <!-- Collection Section -->
                            <div v-else-if="value && value.posts" class="space-y-6">
                                <h3 class="text-3xl font-bold text-center mb-8">{{ value.title }}</h3>
                                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    <div 
                                        v-for="post in value.posts.slice(0, 6)" 
                                        :key="post.id"
                                        class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow"
                                    >
                                        <div class="h-48 bg-gray-300">
                                            <img 
                                                v-if="getPostImage(post)" 
                                                :src="getPostImage(post)" 
                                                :alt="post.title"
                                                class="w-full h-full object-cover"
                                            >
                                        </div>
                                        <div class="p-6">
                                            <h4 class="text-xl font-semibold mb-2">{{ post.title }}</h4>
                                            <p class="text-gray-600 mb-4">{{ getPostExcerpt(post) }}</p>
                                            <Link 
                                                :href="route('collection.show', [value.slug, post.slug])"
                                                class="text-blue-600 font-semibold hover:text-blue-800"
                                            >
                                                Read More →
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <Link 
                                        :href="route('collection.index', value.slug)"
                                        class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors"
                                    >
                                        View All {{ value.title }}
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Default Content if no dynamic content -->
                    <div v-else class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <div class="w-12 h-12 bg-blue-600 rounded-lg mb-4 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-2">Fast Performance</h3>
                            <p class="text-gray-600">Lightning-fast loading times with optimized code and modern technologies.</p>
                        </div>
                        
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <div class="w-12 h-12 bg-green-600 rounded-lg mb-4 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-2">Easy to Use</h3>
                            <p class="text-gray-600">Intuitive interface that makes content management simple and efficient.</p>
                        </div>
                        
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <div class="w-12 h-12 bg-purple-600 rounded-lg mb-4 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17v4a2 2 0 002 2h4M13 13h4a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4a2 2 0 012-2h4z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-2">Customizable</h3>
                            <p class="text-gray-600">Flexible content types and fields to match your unique requirements.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </FrontendLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import FrontendLayout from '@/Layouts/FrontendLayout.vue'

const props = defineProps({
    page: Object,
    content: Object,
    seoMeta: Object,
    language: Object,
    availableLanguages: Array,
})

const getContentValue = (key) => {
    return props.content?.[key] || null
}

const getPostImage = (post) => {
    // Extract image from post content - adjust this based on your content structure
    const content = post.content || {}
    return content.featured_image || content.image || null
}

const getPostExcerpt = (post) => {
    const content = post.content || {}
    return content.excerpt || content.description || 'Read more about this post...'
}
</script>