<script setup>
import { onBeforeMount, ref } from 'vue'

const localeLanguage = ref('')

const props = defineProps({
    languages: {
        type: Array,
        default: () => []
    },
    locale: {
        type: String,
        default: 'en'
    }
})

onBeforeMount(() => {
    localeLanguage.value = props.locale
})

const emit = defineEmits(['changeLanguage'])

function changeLanguage() {
    emit('changeLanguage', localeLanguage.value)
}
</script>

<template>
    <div class="mb-4 bg-white shadow sm:rounded-md">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">
                Internationalization
            </h3>
        </div>

        <div class="border-t border-gray-200">
            <dl>
                <div
                    class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                >
                    <dt class="text-sm font-medium text-gray-500">Language</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">
                        <select
                            v-model="localeLanguage"
                            class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                            @change="changeLanguage"
                        >
                            <option
                                v-for="(language, key) in languages"
                                :key="key"
                                :value="language.iso_code"
                                :selected="language.iso_code == locale"
                            >
                                {{ language.name }}
                            </option>
                        </select>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</template>
