<template>
    <AppLayout title="Posts">
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">
                    <Link
                        href="/posts"
                        class="text-blue-400 hover:text-blue-800"
                    >
                        Posts
                    </Link>
                    /
                    <Link
                        :href="`/collection/sections/${section.id}/posts`"
                        class="text-blue-400 hover:text-blue-800"
                    >
                        {{ section.title }}
                    </Link>
                    /
                    <span v-if="posts.id != null">
                        Update {{ posts.title }}
                    </span>
                    <span v-else> Create </span>
                </h2>
            </div>
        </template>

        <div class="mt-8 md:col-span-2">
            <FormCollectionPost
                :item="posts"
                :section="section"
                :list-content-types="listContentTypes"
            />
        </div>

        <div v-if="posts.id != null">
            <hr class="mt-8 border border-slate-600" />
            <div class="mt-8 md:col-span-2">
                <FormContent
                    :item="posts"
                    :update-url="`/collection/sections/${section.id}/posts/${posts.id}/content`"
                />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import FormCollectionPost from '@/Components/Partials/Collection/FormPost.vue'
import FormContent from '@/Components/Partials/ContentType/FormContent.vue'
import { onBeforeMount, ref } from 'vue'

const posts = ref([])
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
    },
    section: {
        type: Object,
        default: () => ({
            id: null,
            title: '',
            description: ''
        })
    }
})

onBeforeMount(() => {
    posts.value = props.item
    listContentTypes.value = props.contentTypes.map((item) => ({
        value: item.id,
        text: item.name
    }))
})
</script>
