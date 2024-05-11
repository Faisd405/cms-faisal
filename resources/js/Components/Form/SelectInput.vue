<script setup>
import { onMounted, ref } from 'vue'

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    option: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['update:modelValue'])

const input = ref(null)

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus()
    }

    updateValue(props.modelValue)
})

defineExpose({ focus: () => input.value.focus() })

function updateValue(value) {
    value = validateValue(value)

    emit('update:modelValue', value)
}

function validateValue(value) {
    if (value === 'click') {
        value = null
    }

    return value
}
</script>

<template>
    <select
        ref="input"
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        :value="modelValue"
        @change="updateValue($event.target.value)"
    >
        <option value="">Select an option</option>
        <option v-for="item in option" :key="item.value" :value="item.value">
            {{ item.text }}
        </option>

        <slot></slot>
    </select>
</template>
