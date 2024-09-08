<template>
    <form @submit.prevent="submitData">
        <div
            class="mb-4 bg-white px-4 py-5 shadow sm:rounded-tl-md sm:rounded-tr-md sm:p-6"
        >
            <div class="mb-2 border-b pb-2">Collection Section</div>
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-4">
                    <InputLabel for="title" value="Title" />
                    <TextInput
                        id="title"
                        v-model="form.title"
                        type="text"
                        class="mt-1 block w-full"
                        @change="transformSlugPage(form.title)"
                    />
                    <InputError :message="form.errors.title" class="mt-2" />
                </div>
                <div class="col-span-6 sm:col-span-4">
                    <InputLabel for="slug" value="Slug" />
                    <TextInput
                        id="slug"
                        v-model="form.slug"
                        type="text"
                        class="mt-1 block w-full"
                        @change="transformSlugPage(form.slug)"
                    />
                    <InputError :message="form.errors.slug" class="mt-2" />
                </div>
                <div class="col-span-6 sm:col-span-4">
                    <InputLabel for="description" value="Description" />
                    <TextInput
                        id="description"
                        v-model="form.description"
                        type="text"
                        class="mt-1 block w-full"
                    />
                    <InputError
                        :message="form.errors.description"
                        class="mt-2"
                    />
                </div>
                <div v-if="updateId === null" class="col-span-6 sm:col-span-4">
                    <InputLabel
                        for="post_content_type_id"
                        value="Content Type for Post"
                    />
                    <SelectInput
                        id="post_content_type_id"
                        v-model="form.post_content_type_id"
                        class="mt-1 block w-full"
                        :options="listContentTypes"
                    />
                    <InputError
                        :message="form.errors.post_content_type_id"
                        class="mt-2"
                    />
                </div>
            </div>
        </div>

        <div
            class="flex justify-end bg-white px-4 py-2 shadow sm:rounded-tl-md sm:rounded-tr-md sm:px-6 sm:py-4"
        >
            <PrimaryButton
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                {{ updateId !== null ? 'Update' : 'Create' }}
            </PrimaryButton>
        </div>
    </form>
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3'
import InputError from '@/Components/Form/InputError.vue'
import InputLabel from '@/Components/Form/InputLabel.vue'
import TextInput from '@/Components/Form/TextInput.vue'
import SelectInput from '@/Components/Form/SelectInput.vue'
import PrimaryButton from '@/Components/Button/PrimaryButton.vue'
import { onBeforeMount, ref } from 'vue'
import axios from '@/libs/axios'
import { transformSlug } from '@/Helpers/textTransform'

const updateId = ref(null)
const form = useForm({
    title: '',
    slug: '',
    description: '',
    post_content_type_id: null
})

const transformSlugPage = (value) => {
    form.slug = transformSlug(value)
}

const submitData = () => {
    if (updateId.value !== null) {
        axios
            .put(
                route('collection.sections.update', updateId.value),
                form.data()
            )
            .then(() => {})
            .catch((err) => {
                if (err.response.status === 422) {
                    form.errors = err.response.data.errors
                }
            })

        return
    }

    axios
        .post(route('collection.sections.store'), form.data())
        .then(() => {
            router.visit(route('collection.sections.index'))
        })
        .catch((err) => {
            if (err.response.status === 422) {
                form.errors = err.response.data.errors
            }
        })
}

const props = defineProps({
    item: {
        type: Object,
        default: () => ({
            id: null,
            title: '',
            slug: '',
            description: '',
            post_content_type_id: null
        })
    },
    listContentTypes: {
        type: Array,
        default: () => []
    }
})

onBeforeMount(() => {
    if (props.item.id !== null) {
        updateId.value = props.item.id
        form.title = props.item.title
        form.slug = props.item.slug
        form.description = props.item.description
        form.post_content_type_id = props.item.post_content_type_id
    }
})
</script>
