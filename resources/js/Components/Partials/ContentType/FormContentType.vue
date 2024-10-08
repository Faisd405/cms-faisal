<template>
    <form @submit.prevent="submitData">
        <div
            class="mb-4 bg-white px-4 py-5 shadow sm:rounded-tl-md sm:rounded-tr-md sm:p-6"
        >
            <div class="mb-2 border-b pb-2">Content Type</div>
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-4">
                    <InputLabel for="name" value="Name" />
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full"
                    />
                    <InputError :message="form.errors.name" class="mt-2" />
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
                <div class="col-span-6 sm:col-span-4">
                    <InputLabel for="type" value="Type" />
                    <SelectInput
                        id="type"
                        v-model="form.type"
                        class="mt-1 block w-full"
                        :options="typeContent"
                    >
                    </SelectInput>
                    <InputError :message="form.errors.type" class="mt-2" />
                </div>
            </div>
        </div>

        <div
            class="flex justify-end bg-white px-4 py-2 shadow sm:rounded-tl-md sm:rounded-tr-md sm:px-6 sm:py-4"
        >
            <PrimaryButton
                :class="form.processing ? 'opacity-25' : ''"
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
import PrimaryButton from '@/Components/Button/PrimaryButton.vue'
import { onBeforeMount, ref } from 'vue'
import axios from '@/libs/axios'
import SelectInput from '@/Components/Form/SelectInput.vue'

const updateId = ref(null)
const typeContent = ref([])

const form = useForm({
    name: '',
    description: '',
    type: ''
})

const submitData = () => {
    if (updateId.value !== null) {
        axios
            .put(route('content-types.update', updateId.value), form.data())
            .then(() => {})
            .catch((err) => {
                if (err.response.status === 422) {
                    form.errors = err.response.data.errors
                }
            })

        return
    }

    axios
        .post(route('content-types.store'), form.data())
        .then(() => {
            router.visit(route('content-types.index'))
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
            name: '',
            description: '',
            type: ''
        })
    },
    types: {
        type: Array,
        default: () => []
    }
})

onBeforeMount(() => {
    if (props.item.id !== null) {
        updateId.value = props.item.id
        form.name = props.item.name
        form.description = props.item.description
        form.type = props.item.type
    }

    props.types.forEach((item) => {
        typeContent.value.push({
            value: item.value,
            text: item.label
        })
    })
})
</script>
