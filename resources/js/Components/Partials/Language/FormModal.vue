<script setup>
import { router, useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/Button/PrimaryButton.vue'
import SecondaryButton from '@/Components/Button/SecondaryButton.vue'
import InputLabel from '@/Components/Form/InputLabel.vue'
import TextInput from '@/Components/Form/TextInput.vue'
import InputError from '@/Components/Form/InputError.vue'
import { FwbModal } from 'flowbite-vue'
import { watch } from 'vue'
import Checkbox from '@/Components/Form/Checkbox.vue'

const emit = defineEmits(['close'])

const form = useForm({
    iso_code: '',
    name: '',
    native_name: '',
    is_rtl: false,
    is_default: false
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
    if (props.updateId) {
        axios
            .put(
                route('localization.languages.update', {
                    languageId: props.updateId
                }),
                form.data()
            )
            .then(() => {
                router.visit(route('localization.languages.index'))
            })
            .catch((err) => {
                if (err.response.status === 422) {
                    form.errors = err.response.data.errors
                }
            })

        return
    }

    axios
        .post(route('localization.languages.store'), form.data())
        .then(() => {
            router.visit(route('localization.languages.index'))
        })
        .catch((err) => {
            if (err.response.status === 422) {
                form.errors = err.response.data.errors
            }
        })
}

const updateForm = (isUpdate) => {
    form.reset()
    if (isUpdate) {
        form.iso_code = props.updateData.iso_code
        form.name = props.updateData.name
        form.native_name = props.updateData.native_name
        form.is_rtl = props.updateData.is_rtl
        form.is_default = props.updateData.is_default
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
        <template #header> Create Language </template>
        <template #body>
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6">
                    <InputLabel for="iso_code" value="ISO Code" />
                    <TextInput
                        id="iso_code"
                        v-model="form.iso_code"
                        type="text"
                        class="mt-1 block w-full"
                    />
                    <InputError :message="form.errors.iso_code" class="mt-2" />
                </div>
                <div class="col-span-6">
                    <InputLabel for="name" value="Name" />
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full"
                    />
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>
                <div class="col-span-6">
                    <InputLabel for="native_name" value="Native Name" />
                    <TextInput
                        id="native_name"
                        v-model="form.native_name"
                        type="text"
                        class="mt-1 block w-full"
                    />
                    <InputError
                        :message="form.errors.native_name"
                        class="mt-2"
                    />
                </div>
                <div class="col-span-6 grid grid-cols-6 gap-6">
                    <div class="col-span-2">
                        <InputLabel for="is_rtl" value="RTL" />
                        <Checkbox
                            id="is_rtl"
                            v-model:checked="form.is_rtl"
                            class="mt-1 block w-full"
                        />
                        <InputError
                            :message="form.errors.is_rtl"
                            class="mt-2"
                        />
                    </div>
                    <div class="col-span-2">
                        <InputLabel for="is_default" value="Bahasa Default" />
                        <Checkbox
                            id="is_default"
                            v-model:checked="form.is_default"
                            class="mt-1 block w-full"
                        />
                        <InputError
                            :message="form.errors.is_default"
                            class="mt-2"
                        />
                    </div>
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
