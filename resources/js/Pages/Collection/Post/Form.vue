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
                    <span> Update Content {{ posts.title }} </span>
                </h2>
            </div>
        </template>

        <div class="mt-8 md:col-span-2">
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-4">
                    <FormContent
                        :item="posts"
                        :update-url="`/collection/sections/${section.id}/posts/${posts.id}/content`"
                        :locale-language="props.locale"
                    />
                </div>
                <div class="col-span-2">
                    <language-pick
                        :languages="props.languages"
                        :locale="props.locale.iso_code"
                        @change-language="changeLanguage"
                    />
                    <div class="mb-4 bg-white shadow sm:rounded-md">
                        <div class="px-4 py-5 sm:px-6">
                            <h3
                                class="text-lg font-medium leading-6 text-gray-900"
                            >
                                Collection Section Information
                            </h3>
                        </div>

                        <div class="border-t border-gray-200">
                            <dl>
                                <div
                                    class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                                >
                                    <dt
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Title
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm text-gray-900 sm:col-span-2"
                                    >
                                        {{ section.title }}
                                    </dd>
                                </div>
                                <div
                                    class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                                >
                                    <dt
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Description
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm text-gray-900 sm:col-span-2"
                                    >
                                        {{ section.description }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    <div class="mb-4 bg-white shadow sm:rounded-md">
                        <div class="px-4 py-5 sm:px-6">
                            <h3
                                class="text-lg font-medium leading-6 text-gray-900"
                            >
                                Collection Post Information
                            </h3>
                        </div>

                        <div class="border-t border-gray-200">
                            <dl>
                                <div
                                    class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                                >
                                    <dt
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Title
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm text-gray-900 sm:col-span-2"
                                    >
                                        {{ posts.title }}
                                    </dd>
                                </div>
                                <div
                                    class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                                >
                                    <dt
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Slug
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm text-gray-900 sm:col-span-2"
                                    >
                                        {{ posts.slug }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import FormContent from '@/Components/Partials/ContentType/FormContent.vue'
import LanguagePick from '@/Components/Partials/ContentType/LanguagePick.vue'
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
    },
    languages: {
        type: Array,
        default: () => []
    },
    locale: {
        type: Object,
        default: () => ({
            id: null,
            iso_code: ''
        })
    }
})

function changeLanguage(locale) {
    const url = `/collection/sections/${props.section.id}/posts/${posts.value.id}/edit?locale=${locale}`
    window.location = url
}

onBeforeMount(() => {
    posts.value = props.item
    listContentTypes.value = props.contentTypes.map((item) => ({
        value: item.id,
        text: item.name
    }))
})
</script>
