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
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-4">
                    <FormContent
                        :item="pages"
                        :update-url="`/pages/${props.item.id}/content`"
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
                                Page Information
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                Page information and settings.
                            </p>
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
                                        {{ pages.title }}
                                    </dd>
                                </div>

                                <div
                                    class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                                >
                                    <dt
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Content Type
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm text-gray-900 sm:col-span-2"
                                    >
                                        {{
                                            listContentTypes.find(
                                                (item) =>
                                                    item.value ===
                                                    pages.content_type_id
                                            ).text
                                        }}
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
import LanguagePick from '@/Components/Partials/ContentType/LanguagePick.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import FormContent from '@/Components/Partials/ContentType/FormContent.vue'
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
    },
    languages: {
        type: Array,
        default: () => []
    },
    locale: {
        type: Object,
        default: () => ({})
    }
})

function changeLanguage(locale) {
    const url = `/pages/${props.item.id}/edit?locale=${locale}`
    window.location = url
}

onBeforeMount(() => {
    pages.value = props.item
    listContentTypes.value = props.contentTypes.map((item) => ({
        value: item.id,
        text: item.name
    }))
})
</script>
