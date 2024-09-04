<script setup>
import { router, useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/Button/PrimaryButton.vue'
import SecondaryButton from '@/Components/Button/SecondaryButton.vue'
import InputLabel from '@/Components/Form/InputLabel.vue'
import TextInput from '@/Components/Form/TextInput.vue'
import SelectInput from '@/Components/Form/SelectInput.vue'
import InputError from '@/Components/Form/InputError.vue'
import { FwbModal } from 'flowbite-vue'
import { transformSlug } from '@/Helpers/textTransform'
import { watch } from 'vue'

const emit = defineEmits(['close'])

const form = useForm({
    title: '',
    slug: '',
    content_type_id: 0
})

const props = defineProps({
    updateId: {
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
            .put(route('pages.update', props.updateId), form.data())
            .then(() => {})
            .catch((err) => {
                if (err.response.status === 422) {
                    form.errors = err.response.data.errors
                }
            })

        return
    }

    axios
        .post(route('pages.store'), form.data())
        .then(() => {
            router.visit(route('pages.index'))
        })
        .catch((err) => {
            if (err.response.status === 422) {
                form.errors = err.response.data.errors
            }
        })
}

const transformSlugPage = (value) => {
    form.slug = transformSlug(value)
}

const updateForm = (isUpdate) => {
    form.reset()
    if (isUpdate) {
        form.title = props.updateData.title
        form.slug = props.updateData.slug
        form.content_type_id = props.updateData.content_type_id
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
        <template #header> Create Page </template>
        <template #body>
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6">
                    <InputLabel for="title" value="Title" />
                    <TextInput
                        id="title"
                        v-model="form.title"
                        type="text"
                        class="mt-1 block w-full"
                        @input="transformSlugPage(form.title)"
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
                        @change="transformSlugPage(form.slug)"
                    />
                    <InputError :message="form.errors.slug" class="mt-2" />
                </div>
                <div v-if="props.updateId === null" class="col-span-6">
                    <InputLabel for="content_type_id" value="Content Type" />
                    <SelectInput
                        id="content_type_id"
                        v-model="form.content_type_id"
                        class="mt-1 block w-full"
                        :options="listContentTypes"
                    />
                    <InputError
                        :message="form.errors.content_type_id"
                        class="mt-2"
                    />
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
