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
            </div>
        </div>

        <div
            class="flex justify-end bg-white px-4 py-2 shadow sm:rounded-tl-md sm:rounded-tr-md sm:px-6 sm:py-4"
        >
            <PrimaryButton
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Save
            </PrimaryButton>
        </div>
    </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import InputError from '@/Components/Form/InputError.vue'
import InputLabel from '@/Components/Form/InputLabel.vue'
import TextInput from '@/Components/Form/TextInput.vue'
import PrimaryButton from '@/Components/Button/PrimaryButton.vue'
import { onBeforeMount, ref } from 'vue'

const updateId = ref(null)
const form = useForm({
    name: '',
    description: ''
})

const submitData = () => {
    if (updateId.value !== null) {
        form.put(route('content-types.update', updateId.value), {
            errorBag: 'updateContentType',
            preserveScroll: true,
            onFinish: () => form.reset()
        })
        return
    }

    form.post(route('content-types.store'), {
        errorBag: 'createContentType',
        preserveScroll: true,
        onFinish: () => form.reset()
    })
}

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

onBeforeMount(() => {
    if (props.item.id !== null) {
        updateId.value = props.item.id
        form.name = props.item.name
        form.description = props.item.description
    }
})
</script>
