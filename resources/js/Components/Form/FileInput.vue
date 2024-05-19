<script setup>
import { onMounted, ref } from 'vue'

defineProps({
    modelValue: {
        type: String,
        default: ''
    }
})

defineEmits(['update:modelValue'])

const input = ref(null)

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus()
    }
})

defineExpose({ focus: () => input.value.focus() })
</script>

<template>
    <input
        ref="input"
        class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 focus:outline-none"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>
