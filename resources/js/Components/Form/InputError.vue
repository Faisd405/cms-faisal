<script setup>
defineProps({
    message: {
        type: [String, Array],
        default: ''
    }
})
</script>

<template>
    <div v-show="message">
        <span v-if="Array.isArray(message)">
            <p v-for="item in message" :key="item" class="text-sm text-red-600">
                {{ item }}
            </p>
        </span>

        <p v-else class="text-sm text-red-600">
            {{ message }}
        </p>
    </div>
</template>
