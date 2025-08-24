<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center py-4">
                    <!-- Logo -->
                    <Link :href="route('home')" class="text-2xl font-bold text-gray-800">
                        {{ $page.props.site.name }}
                    </Link>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex space-x-8">
                        <Link 
                            :href="route('home')" 
                            class="text-gray-600 hover:text-gray-900 transition-colors"
                            :class="{ 'text-blue-600 font-semibold': $page.url === '/' }"
                        >
                            Home
                        </Link>
                        <Link 
                            href="/about" 
                            class="text-gray-600 hover:text-gray-900 transition-colors"
                        >
                            About
                        </Link>
                        <Link 
                            :href="route('collection.index', 'blog')" 
                            class="text-gray-600 hover:text-gray-900 transition-colors"
                        >
                            Blog
                        </Link>
                        <Link 
                            href="/contact" 
                            class="text-gray-600 hover:text-gray-900 transition-colors"
                        >
                            Contact
                        </Link>
                    </div>

                    <!-- Language Switcher & Mobile Menu -->
                    <div class="flex items-center space-x-4">
                        <!-- Language Switcher -->
                        <div class="relative" v-if="$page.props.language.available.length > 1">
                            <button 
                                @click="showLanguageMenu = !showLanguageMenu"
                                class="flex items-center space-x-2 text-gray-600 hover:text-gray-900 transition-colors"
                            >
                                <span class="text-sm font-medium">{{ $page.props.language.current?.name || 'EN' }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div 
                                v-show="showLanguageMenu"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                                @click.outside="showLanguageMenu = false"
                            >
                                <Link
                                    v-for="language in $page.props.language.available"
                                    :key="language.id"
                                    :href="route('language.switch', language.code)"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    :class="{ 'bg-blue-50 text-blue-600': language.code === $page.props.language.current?.code }"
                                >
                                    {{ language.name }}
                                </Link>
                            </div>
                        </div>

                        <!-- Mobile Menu Toggle -->
                        <button 
                            @click="showMobileMenu = !showMobileMenu"
                            class="md:hidden text-gray-600 hover:text-gray-900"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Navigation -->
                <div v-show="showMobileMenu" class="md:hidden py-4 border-t border-gray-200">
                    <div class="space-y-2">
                        <Link 
                            :href="route('home')" 
                            class="block py-2 text-gray-600 hover:text-gray-900"
                            @click="showMobileMenu = false"
                        >
                            Home
                        </Link>
                        <Link 
                            href="/about" 
                            class="block py-2 text-gray-600 hover:text-gray-900"
                            @click="showMobileMenu = false"
                        >
                            About
                        </Link>
                        <Link 
                            :href="route('collection.index', 'blog')" 
                            class="block py-2 text-gray-600 hover:text-gray-900"
                            @click="showMobileMenu = false"
                        >
                            Blog
                        </Link>
                        <Link 
                            href="/contact" 
                            class="block py-2 text-gray-600 hover:text-gray-900"
                            @click="showMobileMenu = false"
                        >
                            Contact
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        <div v-if="$page.props.flash.success" class="bg-green-500 text-white px-4 py-3">
            <div class="container mx-auto">
                {{ $page.props.flash.success }}
            </div>
        </div>
        <div v-if="$page.props.flash.error" class="bg-red-500 text-white px-4 py-3">
            <div class="container mx-auto">
                {{ $page.props.flash.error }}
            </div>
        </div>

        <!-- Main Content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white">
            <div class="container mx-auto px-4 py-12">
                <div class="grid md:grid-cols-4 gap-8">
                    <!-- Company Info -->
                    <div>
                        <h3 class="text-xl font-bold mb-4">{{ $page.props.site.name }}</h3>
                        <p class="text-gray-300 mb-4">
                            Building amazing digital experiences with our powerful CMS platform.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-300 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                        <ul class="space-y-2">
                            <li>
                                <Link href="/about" class="text-gray-300 hover:text-white transition-colors">
                                    About Us
                                </Link>
                            </li>
                            <li>
                                <Link href="/services" class="text-gray-300 hover:text-white transition-colors">
                                    Services
                                </Link>
                            </li>
                            <li>
                                <Link href="/contact" class="text-gray-300 hover:text-white transition-colors">
                                    Contact
                                </Link>
                            </li>
                            <li>
                                <Link href="/privacy" class="text-gray-300 hover:text-white transition-colors">
                                    Privacy Policy
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <!-- Resources -->
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Resources</h4>
                        <ul class="space-y-2">
                            <li>
                                <Link :href="route('collection.index', 'blog')" class="text-gray-300 hover:text-white transition-colors">
                                    Blog
                                </Link>
                            </li>
                            <li>
                                <Link href="/help" class="text-gray-300 hover:text-white transition-colors">
                                    Help Center
                                </Link>
                            </li>
                            <li>
                                <Link href="/documentation" class="text-gray-300 hover:text-white transition-colors">
                                    Documentation
                                </Link>
                            </li>
                            <li>
                                <Link href="/faq" class="text-gray-300 hover:text-white transition-colors">
                                    FAQ
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Contact Info</h4>
                        <ul class="space-y-2 text-gray-300">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                123 Business St, City, State 12345
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                +1 (555) 123-4567
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                contact@example.com
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-300">
                    <p>&copy; {{ new Date().getFullYear() }} {{ $page.props.site.name }}. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const showLanguageMenu = ref(false)
const showMobileMenu = ref(false)
</script>