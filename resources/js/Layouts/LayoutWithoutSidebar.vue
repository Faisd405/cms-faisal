<template>
    <div class="flex h-screen overflow-hidden">
        <!-- Content area -->
        <div
            class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden"
        >
            <!-- Site header -->
            <HeaderLayout :is-use-sidebar-toggle="false" />

            <main>
                <div class="max-w-9xl mx-auto w-full px-4 py-8 sm:px-6 lg:px-8">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue'
import HeaderLayout from './partials/HeaderLayout.vue'

export default {
    name: 'Dashboard',
    components: {
        HeaderLayout
    },
    setup() {
        const sidebarOpen = ref(false)

        return {
            sidebarOpen
        }
    }
}
</script>
