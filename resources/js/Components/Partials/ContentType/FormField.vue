<template>
    <div>
        <div
            class="flex justify-end bg-white px-4 py-2 shadow sm:rounded-tl-md sm:rounded-tr-md sm:px-6 sm:py-4"
        >
            <PrimaryButton @click="showContentTypeFieldModal">
                Add Field
            </PrimaryButton>
        </div>

        <div class="mt-4 flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div
                    class="align middle inline-block min-w-full py-2 sm:px-6 lg:px-8"
                >
                    <div
                        class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg"
                    >
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                    >
                                        Name
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                    >
                                        Label
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                    >
                                        Type
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                    >
                                        Placeholder
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                    >
                                        Default Value
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                    >
                                        Config
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr
                                    v-for="(field, key) in contentTypeFields"
                                    :key="key"
                                >
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ field.name }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ field.label }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ field.type }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ field.placeholder }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ field.default_value }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{
                                                field.is_required
                                                    ? 'Required'
                                                    : ''
                                            }}
                                            {{
                                                field.is_unique ? 'Unique' : ''
                                            }}
                                            {{
                                                field.is_searchable
                                                    ? 'Searchable'
                                                    : ''
                                            }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            <div
                                                class="items center justify center flex space-x-4"
                                            >
                                                <WarningButton
                                                    @click="
                                                        showContentTypeFieldModal(
                                                            field
                                                        )
                                                    "
                                                >
                                                    Edit
                                                </WarningButton>
                                                <DangerButton
                                                    @click="
                                                        showDeleteFieldModal(
                                                            field.id
                                                        )
                                                    "
                                                >
                                                    Delete
                                                </DangerButton>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <FwbModal
            v-if="isShowAddFieldModal"
            size="4xl"
            @close="isShowAddFieldModal = false"
        >
            <template #header>
                <span v-text="(updateId ? 'Update ' : 'Add ') + 'Content Type'">
                </span>
            </template>
            <template #body>
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-3">
                        <InputLabel for="name" value="Name (Slug)" />
                        <TextInput
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="mt-1 block w-full"
                            @change="
                                form.name = transformSlug($event.target.value)
                            "
                        />
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>
                    <div class="col-span-3">
                        <InputLabel for="label" value="Label" />
                        <TextInput
                            id="label"
                            v-model="form.label"
                            type="text"
                            class="mt-1 block w-full"
                        />
                        <InputError :message="form.errors.label" class="mt-2" />
                    </div>
                    <div class="col-span-6">
                        <InputLabel for="type" value="Type" />
                        <SelectInput
                            id="type"
                            v-model="form.type"
                            class="mt-1 block w-full"
                            :option="typeField"
                        >
                        </SelectInput>
                        <InputError :message="form.errors.type" class="mt-2" />
                    </div>
                    <div class="col-span-3">
                        <InputLabel for="placeholder" value="Placeholder" />
                        <TextInput
                            id="placeholder"
                            v-model="form.placeholder"
                            type="text"
                            class="mt-1 block w-full"
                        />
                        <InputError
                            :message="form.errors.placeholder"
                            class="mt-2"
                        />
                    </div>
                    <div class="col-span-3">
                        <InputLabel for="default_value" value="Default Value" />
                        <TextInput
                            id="default_value"
                            v-model="form.default_value"
                            type="text"
                            class="mt-1 block w-full"
                        />
                        <InputError
                            :message="form.errors.default_value"
                            class="mt-2"
                        />
                    </div>
                    <div class="col-span-6">
                        <InputLabel value="Config" />
                        <div class="grid grid-cols-3 gap-6">
                            <div class="">
                                <Checkbox
                                    v-model:checked="form.is_required"
                                    value="is_required"
                                >
                                    Required
                                </Checkbox>
                            </div>
                            <div class="">
                                <Checkbox
                                    v-model:checked="form.is_unique"
                                    value="is_unique"
                                >
                                    Unique
                                </Checkbox>
                            </div>
                            <div class="">
                                <Checkbox
                                    v-model:checked="form.is_searchable"
                                    value="is_searchable"
                                >
                                    Searchable
                                </Checkbox>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="flex justify-end gap-4">
                    <SecondaryButton
                        color="default"
                        type="button"
                        @click="isShowAddFieldModal = false"
                    >
                        Cancel
                    </SecondaryButton>
                    <PrimaryButton
                        color="red"
                        type="button"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="submitData"
                    >
                        <span v-if="updateId"> Update </span>
                        <span v-else> Save </span>
                    </PrimaryButton>
                </div>
            </template>
        </FwbModal>

        <FwbModal
            v-if="isShowDeleteFieldModal"
            @close="isShowDeleteFieldModal = false"
        >
            <template #header>
                <span>Delete Content Type</span>
            </template>
            <template #body>
                <p>Are you sure you want to delete this content type?</p>
            </template>
            <template #footer>
                <div class="flex justify-end gap-4">
                    <SecondaryButton
                        color="default"
                        type="button"
                        @click="isShowDeleteFieldModal = false"
                    >
                        Cancel
                    </SecondaryButton>
                    <PrimaryButton
                        color="red"
                        type="button"
                        @click="deleteContentType"
                    >
                        Delete
                    </PrimaryButton>
                </div>
            </template>
        </FwbModal>
    </div>
</template>

<script setup>
import PrimaryButton from '@/Components/Button/PrimaryButton.vue'
import SecondaryButton from '@/Components/Button/SecondaryButton.vue'
import InputLabel from '@/Components/Form/InputLabel.vue'
import TextInput from '@/Components/Form/TextInput.vue'
import SelectInput from '@/Components/Form/SelectInput.vue'
import Checkbox from '@/Components/Form/Checkbox.vue'
import InputError from '@/Components/Form/InputError.vue'
import WarningButton from '@/Components/Button/WarningButton.vue'
import { transformSlug } from '@/Helpers/textTransform'
import { FwbModal } from 'flowbite-vue'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import axios from '@/libs/axios'
import DangerButton from '@/Components/Button/DangerButton.vue'

const isShowAddFieldModal = ref(false)
const updateId = ref(null)
const isShowDeleteFieldModal = ref(false)

const form = useForm({
    name: '',
    label: '',
    type: '',
    validation: [],
    placeholder: '',
    default_value: '',
    is_required: false,
    is_unique: false,
    is_searchable: false
})

const showContentTypeFieldModal = (fieldData = null) => {
    form.clearErrors()

    if (fieldData !== null) {
        updateId.value = fieldData.id

        form.name = fieldData.name
        form.label = fieldData.label
        form.type = fieldData.type
        form.placeholder = fieldData.placeholder
        form.default_value = fieldData.default_value
        form.is_required = fieldData.is_required
        form.is_unique = fieldData.is_unique
        form.is_searchable = fieldData.is_searchable
    } else {
        form.reset()
        updateId.value = null
    }

    isShowAddFieldModal.value = true
}

const showDeleteFieldModal = (fieldDataId) => {
    updateId.value = fieldDataId
    isShowDeleteFieldModal.value = true
}

const typeField = [
    { text: 'Text', value: 'text' },
    { text: 'Textarea', value: 'textarea' },
    { text: 'Select', value: 'select' },
    { text: 'Checkbox', value: 'checkbox' },
    { text: 'Radio', value: 'radio' },
    { text: 'File', value: 'file' },
    { text: 'Image', value: 'image' },
    { text: 'Date', value: 'date' },
    { text: 'Time', value: 'time' },
    { text: 'Datetime', value: 'datetime' }
]

const props = defineProps({
    contentType: {
        type: Object,
        default: () => ({
            id: null
        })
    },
    contentTypeFields: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['deleteField', 'updateField', 'createField'])

const deleteContentType = () => {
    if (updateId.value === null) {
        return
    }

    axios
        .delete(
            route('content-types.fields.destroy', {
                contentTypeId: props.contentType.id,
                fieldId: updateId.value
            })
        )
        .then(() => {
            emit('deleteField', updateId.value)
            isShowDeleteFieldModal.value = false
        })
}

const submitData = () => {
    form.clearErrors()

    if (props.contentType.id === null) {
        return
    }

    if (updateId.value !== null && updateId.value !== undefined) {
        axios
            .put(
                route('content-types.fields.update', {
                    contentTypeId: props.contentType.id,
                    fieldId: updateId.value
                }),
                form.data()
            )
            .then((response) => {
                emit('updateField', response.data.data)
                isShowAddFieldModal.value = false
            })
            .catch((error) => {
                if (error.response.status === 422) {
                    console.log(error.response.data.errors)
                }
            })

        return
    }

    axios
        .post(
            route('content-types.fields.store', {
                contentTypeId: props.contentType.id
            }),
            form.data()
        )
        .then((res) => {
            emit('createField', res.data.data)
            isShowAddFieldModal.value = false
        })
        .catch((error) => {
            if (error.response.status === 422) {
                form.setError(error.response.data.errors)
            }
        })
}
</script>
