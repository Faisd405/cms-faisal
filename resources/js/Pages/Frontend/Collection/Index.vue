<template>
    <FrontendLayout>
        <Head>
            <title>{{ section.title }} - {{ $page.props.site.name }}</title>
            <meta name="description" :content="section.description">
            <meta property="og:title" :content="section.title">
            <meta property="og:description" :content="section.description">
            <meta property="og:type" content="website">
            <meta property="og:url" :content="$page.url">
        </Head>

        <div class="min-h-screen bg-white">
            <!-- Header Section -->
            <section class="bg-gradient-to-r from-blue-600 to-purple-700 text-white py-16">
                <div class="container mx-auto px-4 text-center">
                    <h1 class="text-4xl font-bold mb-4">{{ section.title }}</h1>
                    <p v-if="section.description" class="text-xl max-w-3xl mx-auto">
                        {{ section.description }}
                    </p>
                </div>
            </section>

            <!-- Posts Grid -->
            <div class="py-16">
                <div class="container mx-auto px-4">
                    <div v-if="posts.length > 0" class="space-y-8">
                        <!-- Featured Post (First Post) -->
                        <div v-if="posts[0]" class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-lg">
                            <div class="md:flex">
                                <div class="md:w-1/2 h-96">
                                    <img 
                                        v-if="getPostImage(posts[0])" 
                                        :src="getPostImage(posts[0])" 
                                        :alt="posts[0].title"
                                        class="w-full h-full object-cover"
                                    >
                                    <div v-else class="w-full h-full bg-gray-300 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="md:w-1/2 p-8 flex flex-col justify-center">
                                    <div class="flex items-center text-sm text-gray-500 mb-2">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium mr-2">
                                            Featured
                                        </span>
                                        <time :datetime="posts[0].published_at">
                                            {{ formatDate(posts[0].published_at || posts[0].created_at) }}
                                        </time>
                                    </div>
                                    <h2 class="text-3xl font-bold mb-4">{{ posts[0].title }}</h2>
                                    <p class="text-gray-600 mb-6 text-lg">{{ getPostExcerpt(posts[0]) }}</p>
                                    <Link 
                                        :href="route('collection.show', [section.slug, posts[0].slug])"
                                        class="inline-flex items-center text-blue-600 font-semibold hover:text-blue-800 transition-colors"
                                    >
                                        Read Full Article
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <!-- Regular Posts Grid -->
                        <div v-if="posts.length > 1" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <article 
                                v-for="post in posts.slice(1)" 
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
                                    <div v-else class="w-full h-full bg-gray-300 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <div class="flex items-center text-sm text-gray-500 mb-2">
                                        <time :datetime="post.published_at">
                                            {{ formatDate(post.published_at || post.created_at) }}
                                        </time>
                                    </div>
                                    <h3 class="text-xl font-semibold mb-2 line-clamp-2">{{ post.title }}</h3>
                                    <p class="text-gray-600 mb-4 line-clamp-3">{{ getPostExcerpt(post) }}</p>
                                    <Link 
                                        :href="route('collection.show', [section.slug, post.slug])"
                                        class="inline-flex items-center text-blue-600 font-semibold hover:text-blue-800 transition-colors"
                                    >
                                        Read More
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </Link>
                                </div>
                            </article>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="text-center py-16">
                        <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">No {{ section.title }} Yet</h3>
                        <p class="text-gray-500">Check back soon for new content.</p>
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
    section: Object,
    posts: Array,
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