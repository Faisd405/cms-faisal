<template>
    <AppLayout title="Pages">
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">
                    <Link
                        href="/pages"
                        class="text-blue-400 hover:text-blue-800"
                    >
                        Pages
                    </Link>
                    /
                    <span v-if="pages.id != null"> Update </span>
                    <span v-else> Create </span>
                </h2>
            </div>
        </template>

        <div class="mt-8 md:col-span-2">
            <FormContent
                :item="pages"
                :update-url="`/pages/${props.item.id}/content`"
            />
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import FormContent from '@/Components/Partials/Page/FormContent.vue'
import { onBeforeMount, ref } from 'vue'

const pages = ref([])
const listContentTypes = ref([])

const props = defineProps({
    item: {
        type: Object,
        default: () => ({
            id: null,
            name: '',
            description: ''
        })
    },
    contentTypes: {
        type: Array,
        default: () => []
    }
})

onBeforeMount(() => {
    pages.value = props.item
    listContentTypes.value = props.contentTypes.map((item) => ({
        value: item.id,
        text: item.name
    }))
})
</script>
