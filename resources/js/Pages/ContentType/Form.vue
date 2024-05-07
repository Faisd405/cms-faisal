<template>
    <AppLayout title="Content Types">
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-slate-800">
                    <Link
                        href="/content-types"
                        class="text-blue-400 hover:text-blue-800"
                    >
                        Content Types
                    </Link>
                    /
                    <span v-if="updateId != null"> Update </span>
                    <span v-else> Create </span>
                </h2>
            </div>
        </template>

        <div class="mt-8 md:col-span-2">
            <form @submit.prevent="submitData">
                <div
                    class="mb-4 bg-white px-4 py-5 shadow sm:rounded-tl-md sm:rounded-tr-md sm:p-6"
                >
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="name" value="Name" />
                            <TextInput
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full"
                            />
                            <InputError
                                :message="form.errors.name"
                                class="mt-2"
                            />
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
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
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
