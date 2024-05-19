<script setup>
import { onBeforeMount, ref } from 'vue'
import TextareaInput from '@/Components/Form/TextareaInput.vue'
import TextInput from '@/Components/Form/TextInput.vue'
import InputLabel from '@/Components/Form/InputLabel.vue'
import PrimaryButton from '@/Components/Button/PrimaryButton.vue'
import SelectInput from '@/Components/Form/SelectInput.vue'
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
    }
})

function submitData() {
    isProcessingSubmit.value = true
    axios
        .post(`/pages/${props.item.id}/content`, { page_content: form.value })
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

    props.item.page_contents?.forEach((item) => {
        if (form.value[item.content_type_field.name]) {
            form.value[item.content_type_field.name].value = item.value
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
                class="col-span-6 sm:col-span-4"
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
