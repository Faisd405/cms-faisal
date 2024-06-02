<template>
    <AppLayout title="Content Section">
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">
                    <Link
                        href="/content/sections"
                        class="text-blue-400 hover:text-blue-800"
                    >
                        Content Section
                    </Link>
                    /
                    <span v-if="formSection.id != null"> Update </span>
                    <span v-else> Create </span>
                </h2>
            </div>
        </template>

        <div class="mt-8 md:col-span-2">
            <FormSection :item="formSection" />
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import FormSection from '@/Components/Partials/Content/FormSection.vue'
import { onBeforeMount, ref } from 'vue'

const formSection = ref([])

const props = defineProps({
    item: {
        type: Object,
        default: () => ({
            id: null,
            name: '',
            description: ''
        })
    }
})

onBeforeMount(() => {
    formSection.value = props.item
})
</script>
