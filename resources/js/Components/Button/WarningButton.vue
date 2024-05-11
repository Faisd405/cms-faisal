<script setup>
defineProps({
    type: {
        type: String,
        default: 'button'
    }
})
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center justify-center rounded-md border border-transparent bg-yellow-400 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-yellow-300 focus:outline-none focus:ring-2 focus:ring-yellow-300 focus:ring-offset-2 active:bg-yellow-500"
    >
        <slot />
    </button>
</template>
