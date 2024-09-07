<script setup>
import { router, useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/Button/PrimaryButton.vue'
import SecondaryButton from '@/Components/Button/SecondaryButton.vue'
import InputLabel from '@/Components/Form/InputLabel.vue'
import TextInput from '@/Components/Form/TextInput.vue'
import InputError from '@/Components/Form/InputError.vue'
import { FwbModal } from 'flowbite-vue'
import { transformSlug } from '@/Helpers/textTransform'
import { watch } from 'vue'

const emit = defineEmits(['close'])

const form = useForm({
    title: '',
    slug: ''
})

const props = defineProps({
    updateId: {
        type: Number,
        default: null
    },
    sectionId: {
        type: Number,
        default: null
    },
    updateData: {
        type: Object,
        default: () => ({})
    },
    listContentTypes: {
        type: Array,
        default: () => []
    },
    isShowCreateModal: {
        type: Boolean,
        default: false
    }
})

const submitData = () => {
    if (props.updateId !== null) {
        axios
            .put(
                route('collection.sections.posts.update', {
                    postId: props.updateId,
                    sectionId: props.sectionId
                }),
                form.data()
            )
            .then(() => {
                router.visit(route('collection.sections.posts.index'))
            })
            .catch((err) => {
                if (err.response.status === 422) {
                    form.errors = err.response.data.errors
                }
            })

        return
    }

    axios
        .post(
            route('collection.sections.posts.store', {
                sectionId: props.section.id
            }),
            form.data()
        )
        .then(() => {
            router.visit(route('collection.sections.posts.index'))
        })
        .catch((err) => {
            if (err.response.status === 422) {
                form.errors = err.response.data.errors
            }
        })
}

const transformSlugPost = (value) => {
    form.slug = transformSlug(value)
}

const updateForm = (isUpdate) => {
    form.reset()
    if (isUpdate) {
        form.title = props.updateData.title
        form.slug = props.updateData.slug
    }
}

watch(
    () => props.isShowCreateModal,
    (value) => {
        updateForm(value)
    }
)
</script>

<template>
    <FwbModal v-if="isShowCreateModal" @close="emit('close')">
        <template #header> Create Post </template>
        <template #body>
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6">
                    <InputLabel for="title" value="Title" />
                    <TextInput
                        id="title"
                        v-model="form.title"
                        type="text"
                        class="mt-1 block w-full"
                        @input="transformSlugPost(form.title)"
                    />
                    <InputError :message="form.errors.title" class="mt-2" />
                </div>
                <div class="col-span-6">
                    <InputLabel for="slug" value="Slug" />
                    <TextInput
                        id="slug"
                        v-model="form.slug"
                        type="text"
                        class="mt-1 block w-full"
                        @change="transformSlugPost(form.slug)"
                    />
                    <InputError :message="form.errors.slug" class="mt-2" />
                </div>
            </div>
        </template>
        <template #footer>
            <div class="flex justify-end gap-4">
                <SecondaryButton
                    color="default"
                    type="button"
                    @click="emit('close')"
                >
                    Cancel
                </SecondaryButton>
                <PrimaryButton
                    color="red"
                    type="button"
                    @click.prevent="submitData"
                >
                    {{ props.updateId !== null ? 'Update' : 'Create' }}
                </PrimaryButton>
            </div>
        </template>
    </FwbModal>
</template>
