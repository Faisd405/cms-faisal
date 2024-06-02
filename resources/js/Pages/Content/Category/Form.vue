<template>
    <AppLayout title="Content Category">
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">
                    <Link
                        href="/content/categories"
                        class="text-blue-400 hover:text-blue-800"
                    >
                        Content Category
                    </Link>
                    /
                    <span v-if="formCategory.id != null"> Update </span>
                    <span v-else> Create </span>
                </h2>
            </div>
        </template>

        <div class="mt-8 md:col-span-2">
            <FormCategory :item="formCategory" />
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import FormCategory from '@/Components/Partials/Content/FormCategory.vue'
import { onBeforeMount, ref } from 'vue'

const formCategory = ref([])

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
    formCategory.value = props.item
})
</script>
