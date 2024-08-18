<script setup>
import { onBeforeMount, ref } from 'vue'
import TextareaInput from '@/Components/Form/TextareaInput.vue'
import TextInput from '@/Components/Form/TextInput.vue'
import InputLabel from '@/Components/Form/InputLabel.vue'
import PrimaryButton from '@/Components/Button/PrimaryButton.vue'
import SelectInput from '@/Components/Form/SelectInput.vue'
import FileInput from '@/Components/Form/FileInput.vue'
import Checkbox from '@/Components/Form/Checkbox.vue'
import Radio from '@/Components/Form/Radio.vue'
import axios from '@/libs/axios'

const contentTypeFields = ref({})

const form = ref({})

const isProcessingSubmit = ref(false)

const props = defineProps({
    item: {
        type: Object,
        default: () => ({
            id: null,
            name: '',
            description: ''
        })
    },
    updateUrl: {
        type: String,
        default: ''
    }
})

function submitData() {
    isProcessingSubmit.value = true

    const formData = new FormData()
    Object.keys(form.value).forEach((key) => {
        const field = form.value[key]
        if (Array.isArray(field.value)) {
            field.value.forEach((value) => {
                formData.append(`item_content[${key}][value][]`, value)
            })
        } else {
            if (field.file && field.file instanceof File) {
                formData.append(
                    `item_content[${key}][value]`,
                    field.file,
                    field.file.name
                )
            } else {
                formData.append(`item_content[${key}][value]`, field.value)
            }
        }
        formData.append(`item_content[${key}][page_id]`, field.page_id)
        formData.append(
            `item_content[${key}][content_type_field_id]`,
            field.content_type_field_id
        )
    })

    axios
        .post(`${props.updateUrl}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        .then(() => {
            isProcessingSubmit.value = false
            window.location.reload()
        })
        .catch(() => {
            isProcessingSubmit.value = false
        })
}

const isMultipleAnswer = (type) => {
    return type === 'checkbox'
}

const initializeForm = () => {
    contentTypeFields.value = props.item.content_type.fields || []

    contentTypeFields.value.forEach((field) => {
        form.value[field.name] = {
            value: isMultipleAnswer(field.type) ? [] : '',
            page_id: props.item.id,
            content_type_field_id: field.id
        }
    })

    props.item.content_value?.forEach((item) => {
        if (form.value[item.content_type_field.name]) {
            form.value[item.content_type_field.name].value = item.value
            form.value[item.content_type_field.name].provide = item.provide
        }

        if (item.content_type_field.type === 'file') {
            form.value[item.content_type_field.name].file = null
        }

        if (
            isMultipleAnswer(item.content_type_field.type) &&
            !Array.isArray(item.value)
        ) {
            form.value[item.content_type_field.name].value = !item.value
                ? []
                : [item.value]
        }
    })
}

onBeforeMount(() => {
    initializeForm()
})
</script>

<template>
    <div
        class="mb-4 bg-white px-4 py-5 shadow sm:rounded-tl-md sm:rounded-tr-md sm:p-6"
    >
        <div class="mb-2 border-b pb-2 font-semibold">Page Content</div>
        <div class="grid grid-cols-6 gap-6">
            <div
                v-for="(itemField, key) in contentTypeFields"
                :key="key"
                class="col-span-6 md:col-span-4"
            >
                <InputLabel :for="itemField.name" :value="itemField.label" />
                <TextareaInput
                    v-if="itemField.type === 'textarea'"
                    v-model="form[itemField['name']].value"
                    class="mt-1 block w-full"
                />
                <SelectInput
                    v-else-if="itemField.type === 'select'"
                    v-model="form[itemField['name']].value"
                    class="mt-1 block w-full"
                    :options="
                        itemField.options.map((item) => ({
                            value: item.value,
                            text: item.label
                        }))
                    "
                />
                <div
                    v-else-if="itemField.type === 'checkbox'"
                    class="mt-1 block w-full"
                >
                    <Checkbox
                        v-for="(itemOption, index) in itemField.options"
                        :key="index"
                        v-model:checked="form[itemField['name']].value"
                        :value="itemOption.value"
                    >
                        {{ itemOption.label }}
                    </Checkbox>
                </div>
                <div v-else-if="itemField.type === 'radio'" class="mt-1 block">
                    <Radio
                        v-for="(itemOption, index) in itemField.options"
                        :key="index"
                        v-model:checked="form[itemField['name']].value"
                        :value="itemOption.value"
                    >
                        {{ itemOption.label }}
                    </Radio>
                </div>
                <TextInput
                    v-else-if="itemField.type === 'datetime'"
                    v-model="form[itemField['name']].value"
                    :type="'datetime-local'"
                    class="mt-1 block w-full"
                />
                <div
                    v-else-if="
                        itemField.type === 'file' || itemField.type === 'image'
                    "
                    class="mt-1 w-full justify-between gap-4 md:flex"
                >
                    <FileInput
                        v-model="form[itemField['name']].file"
                        :type="'file'"
                        class="mt-1 block w-full"
                        @change="
                            (event) =>
                                (form[itemField['name']].file =
                                    event.target.files[0])
                        "
                    />

                    <div v-if="form[itemField['name']].provide">
                        <a
                            :href="form[itemField['name']].provide.filepath"
                            target="_blank"
                            class="inline-flex w-full items-center justify-center rounded-md border border-transparent bg-gray-800 px-8 py-2 text-center text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900"
                        >
                            Download File
                        </a>
                    </div>
                </div>
                <TextInput
                    v-else
                    v-model="form[itemField['name']].value"
                    :type="itemField.type"
                    class="mt-1 block w-full"
                />
            </div>
        </div>
        <div
            class="mt-2 flex justify-end border-t-2 px-4 pt-2 sm:mt-4 sm:rounded-tl-md sm:rounded-tr-md sm:px-6 sm:pt-4"
        >
            <PrimaryButton
                :class="{ 'opacity-25': isProcessingSubmit }"
                :disabled="isProcessingSubmit"
                @click="submitData"
            >
                Update Content
            </PrimaryButton>
        </div>
    </div>
</template>
