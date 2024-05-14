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
                    v-model="form[itemField.name].value"
                    class="mt-1 block w-full"
                />
                <TextInput
                    v-else
                    v-model="form[itemField.name].value"
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

<script setup>
import { onBeforeMount, ref } from 'vue'
import TextareaInput from '@/Components/Form/TextareaInput.vue'
import TextInput from '@/Components/Form/TextInput.vue'
import InputLabel from '@/Components/Form/InputLabel.vue'
import PrimaryButton from '@/Components/Button/PrimaryButton.vue'
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
        .then((res) => {
            console.log(res)
            isProcessingSubmit.value = false
        })
        .catch(() => {
            isProcessingSubmit.value = false
        })
}

onBeforeMount(() => {
    contentTypeFields.value = props.item.content_type.fields

    contentTypeFields.value.forEach((item) => {
        form.value[item.name] = {
            value: '',
            page_id: props.item.id,
            content_type_field_id: item.id
        }
    })

    if (props.item.page_contents && props.item.page_contents.length > 0) {
        props.item.page_contents.forEach((item) => {
            form.value[item.content_type_field.name].value = item.value
        })
    }
})
</script>
