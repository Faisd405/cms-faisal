<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import Banner from '@/Components/Banner.vue'
import Sidebar from './partials/Sidebar.vue'
import HeaderLayout from './partials/HeaderLayout.vue'

const sidebarOpen = ref(false)

defineProps({
    title: {
        type: String,
        required: true
    }
})

const logout = () => {
    router.post(route('logout'))
}
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="flex h-screen overflow-hidden bg-gray-100">
            <!-- Sidebar -->
            <Sidebar
                :sidebar-open="sidebarOpen"
                @close-sidebar="sidebarOpen = false"
            />

            <div
                class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden"
            >
                <!-- Site header -->
                <HeaderLayout
                    :sidebar-open="sidebarOpen"
                    @toggle-sidebar="sidebarOpen = !sidebarOpen"
                />

                <!-- Page Heading -->
                <header v-if="$slots.header" class="bg-white shadow">
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        <slot name="header" />
                    </div>
                </header>

                <!-- Page Content -->
                <main>
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
