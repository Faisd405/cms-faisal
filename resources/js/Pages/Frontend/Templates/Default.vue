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
            <meta property="og:type" content="article">
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:title" :content="seoMeta.title">
            <meta name="twitter:description" :content="seoMeta.description">
            <meta name="twitter:image" :content="seoMeta.image">
        </Head>

        <div class="min-h-screen bg-white">
            <!-- Hero Section -->
            <section v-if="getContentValue('hero_image') || getContentValue('banner')" class="relative h-96 bg-gray-900">
                <div 
                    class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                    :style="`background-image: url(${getContentValue('hero_image') || getContentValue('banner')})`"
                >
                    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                </div>
                <div class="relative container mx-auto px-4 h-full flex items-center">
                    <div class="text-white max-w-3xl">
                        <h1 class="text-5xl font-bold mb-6">{{ page.title }}</h1>
                        <p v-if="getContentValue('subtitle')" class="text-xl mb-8">
                            {{ getContentValue('subtitle') }}
                        </p>
                    </div>
                </div>
            </section>

            <!-- Standard Header (no hero image) -->
            <section v-else class="bg-gray-100 py-16">
                <div class="container mx-auto px-4 text-center">
                    <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ page.title }}</h1>
                    <p v-if="getContentValue('subtitle')" class="text-xl text-gray-600">
                        {{ getContentValue('subtitle') }}
                    </p>
                </div>
            </section>

            <!-- Main Content -->
            <div class="py-16">
                <div class="container mx-auto px-4">
                    <div class="max-w-4xl mx-auto">
                        <!-- Dynamic Content Rendering -->
                        <div v-if="content" class="space-y-12">
                            <div v-for="(value, key) in content" :key="key" class="content-section">
                                <!-- Skip already rendered fields -->
                                <template v-if="!['hero_image', 'banner', 'subtitle'].includes(key)">
                                    <!-- Rich Text Content -->
                                    <div v-if="typeof value === 'string' && value.length > 0" class="prose prose-lg max-w-none">
                                        <div v-html="value"></div>
                                    </div>
                                    
                                    <!-- Image Content -->
                                    <div v-else-if="isImageField(key, value)" class="my-8">
                                        <img 
                                            :src="value" 
                                            :alt="key"
                                            class="w-full h-auto rounded-lg shadow-lg"
                                        >
                                    </div>
                                    
                                    <!-- Page Reference -->
                                    <div v-else-if="value && value.content" class="bg-gray-50 p-8 rounded-xl">
                                        <h3 class="text-2xl font-bold mb-4">{{ value.title }}</h3>
                                        <div class="prose prose-lg max-w-none">
                                            <div v-html="value.content[Object.keys(value.content)[0]]"></div>
                                        </div>
                                        <Link 
                                            :href="route('page.show', value.slug)"
                                            class="inline-block mt-4 text-blue-600 font-semibold hover:text-blue-800"
                                        >
                                            Read More →
                                        </Link>
                                    </div>
                                    
                                    <!-- Collection Section -->
                                    <div v-else-if="value && value.posts" class="space-y-6">
                                        <h3 class="text-3xl font-bold mb-8">{{ value.title }}</h3>
                                        <div class="grid md:grid-cols-2 gap-6">
                                            <div 
                                                v-for="post in value.posts.slice(0, 4)" 
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

                                    <!-- Generic Object/Array Content -->
                                    <div v-else-if="typeof value === 'object' && value !== null" class="bg-gray-50 p-6 rounded-lg">
                                        <h3 class="text-xl font-semibold mb-4 capitalize">{{ key.replace(/_/g, ' ') }}</h3>
                                        <pre class="bg-white p-4 rounded border text-sm overflow-auto">{{ JSON.stringify(value, null, 2) }}</pre>
                                    </div>

                                    <!-- Simple Values -->
                                    <div v-else-if="value && typeof value !== 'object'" class="text-center py-8">
                                        <h3 class="text-xl font-semibold mb-2 capitalize">{{ key.replace(/_/g, ' ') }}</h3>
                                        <p class="text-gray-700">{{ value }}</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Default Content if no dynamic content -->
                        <div v-else class="prose prose-lg max-w-none">
                            <p class="text-gray-600 text-center py-12">
                                This page content is being configured. Please check back soon.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action Section -->
            <section v-if="getContentValue('cta_title') || getContentValue('cta_text')" class="bg-blue-600 text-white py-16">
                <div class="container mx-auto px-4 text-center">
                    <h2 class="text-3xl font-bold mb-4">{{ getContentValue('cta_title') || 'Ready to Get Started?' }}</h2>
                    <p class="text-xl mb-8">{{ getContentValue('cta_text') || 'Contact us today to learn more about our services.' }}</p>
                    <Link 
                        :href="getContentValue('cta_link') || '/contact'"
                        class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors"
                    >
                        {{ getContentValue('cta_button_text') || 'Get Started' }}
                    </Link>
                </div>
            </section>
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

const isImageField = (key, value) => {
    // Check if this is likely an image field
    const imageKeywords = ['image', 'photo', 'picture', 'banner', 'thumbnail', 'cover']
    const hasImageKeyword = imageKeywords.some(keyword => key.toLowerCase().includes(keyword))
    const isValidUrl = typeof value === 'string' && (value.startsWith('http') || value.startsWith('/'))
    const hasImageExtension = typeof value === 'string' && /\.(jpg|jpeg|png|gif|webp|svg)$/i.test(value)
    
    return hasImageKeyword && isValidUrl && (hasImageExtension || value.length > 10)
}

const getPostImage = (post) => {
    const content = post.content || {}
    return content.featured_image || content.image || content.thumbnail || null
}

const getPostExcerpt = (post) => {
    const content = post.content || {}
    return content.excerpt || content.description || 'Read more about this post...'
}
</script>