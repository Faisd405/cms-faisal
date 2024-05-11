<script setup>
import { onMounted, ref } from 'vue'

defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    option: {
        type: Array,
        default: () => []
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
    <select
        ref="input"
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
        <option value="">Select</option>
        <option v-for="item in option" :key="item.value" :value="item.value">
            {{ item.text }}
        </option>

        <slot></slot>
    </select>
</template>
