<template>
    <Link :href="href" custom>
        <li
            class="mb-0.5 rounded-md px-3 py-2 transition duration-150 last:mb-0 hover:bg-slate-600"
        >
            <a
                class="block truncate text-slate-200 transition duration-150 hover:text-white"
                :href="href"
                @click="navigate"
            >
                <div class="flex items-center">
                    <i :class="icon"></i>
                    <span
                        class="lg:sidebar-expanded:opacity-100 ml-3 text-sm font-medium duration-200 lg:opacity-0 2xl:opacity-100"
                    >
                        <slot></slot>
                    </span>
                </div>
            </a>
        </li>
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    icon: {
        type: String,
        required: true
    },
    href: {
        type: String,
        required: true
    }
})
</script>
