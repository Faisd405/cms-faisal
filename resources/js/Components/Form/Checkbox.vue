<script setup>
import { computed } from 'vue'

const emit = defineEmits(['update:checked'])

const props = defineProps({
    checked: {
        type: [Array, Boolean],
        default: false
    },
    value: {
        type: String,
        default: null
    }
})

const proxyChecked = computed({
    get() {
        return props.checked
    },

    set(val) {
        emit('update:checked', val)
    }
})
</script>

<template>
    <div class="flex items-center">
        <input
            :id="value"
            v-model="proxyChecked"
            type="checkbox"
            :value="value"
            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
        />
        <span class="ml-2 text-sm text-slate-800">
            <label :for="value"><slot /></label>
        </span>
    </div>
</template>
