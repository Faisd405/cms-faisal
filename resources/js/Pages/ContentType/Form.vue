<template>
    <AppLayout title="Content Types">
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">
                    <Link
                        href="/content-types"
                        class="text-blue-400 hover:text-blue-800"
                    >
                        Content Types
                    </Link>
                    /
                    <span v-if="contentTypes.id != null"> Update </span>
                    <span v-else> Create </span>
                </h2>
            </div>
        </template>

        <div class="mt-8 md:col-span-2">
            <FormContentType :item="contentTypes" :types="types" />
        </div>

        <div v-if="contentTypes.id != null">
            <hr class="mt-8 border border-slate-600" />

            <div class="mt-8 md:col-span-2">
                <FormField
                    :content-type="contentTypes"
                    :content-type-fields="
                        contentTypes.fields ? contentTypes.fields : []
                    "
                    @delete-field="deleteField"
                    @update-field="updateField"
                    @create-field="createField"
                />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import FormContentType from '@/Components/Partials/ContentType/FormContentType.vue'
import FormField from '@/Components/Partials/ContentType/FormField.vue'
import { onBeforeMount, ref } from 'vue'

const contentTypes = ref([])

const props = defineProps({
    item: {
        type: Object,
        default: () => ({
            id: null,
            name: '',
            description: ''
        })
    },
    types: {
        type: Array,
        default: () => []
    }
})

const deleteField = (fieldId) => {
    contentTypes.value.fields = contentTypes.value.fields.filter(
        (item) => item.id !== fieldId
    )
}

const updateField = (field) => {
    const index = contentTypes.value.fields.findIndex(
        (item) => item.id === field.id
    )

    contentTypes.value.fields[index] = field
}

const createField = (field) => {
    contentTypes.value.fields.push(field)
}

onBeforeMount(() => {
    contentTypes.value = props.item
})
</script>
