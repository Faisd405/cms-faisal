<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import axios from '@/libs/axios'
import { FwbModal } from 'flowbite-vue'
import { ref } from 'vue'
import PrimaryButton from '@/Components/Button/PrimaryButton.vue'
import SecondaryButton from '@/Components/Button/SecondaryButton.vue'
import { router } from '@inertiajs/vue3'

const isShowDeleteModal = ref(false)
const tempId = ref(null)

defineProps({
    list: {
        type: Object,
        default: () => ({})
    }
})

const openDeleteModal = (id) => {
    isShowDeleteModal.value = true
    tempId.value = JSON.parse(JSON.stringify(id))
}

const deleteContentType = () => {
    axios
        .delete(`/content-types/${tempId.value}`)
        .then(() => {
            isShowDeleteModal.value = false
            router.reload()
        })
        .catch((err) => {
            console.log(err)
        })
}
</script>

<template>
    <AppLayout title="Content Types">
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Content Types
                </h2>
                <div>
                    <a
                        href="/content-types/create"
                        class="rounded-md border border-slate-800 bg-slate-800 px-6 py-2 uppercase text-pallet-lighten transition duration-300 ease-in-out hover:bg-pallet-lighten hover:text-slate-800 dark:border-slate-800"
                    >
                        <i class="ion ion-md-add"></i> Add Content Type
                    </a>
                </div>
            </div>
        </template>
        <div class="mx-auto py-8">
            <div class="mx-auto">
                <div
                    class="overflow-hidden border border-pallet-dark bg-white shadow-xl sm:rounded-lg"
                >
                    <div class="relative overflow-x-auto">
                        <table
                            class="w-full text-left text-sm text-gray-500 rtl:text-right"
                        >
                            <thead
                                class="bg-gray-50 text-xs uppercase text-gray-700"
                            >
                                <tr>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-center"
                                    ></th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-center"
                                    >
                                        Name
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-center"
                                    >
                                        Type
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-center"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(item, key) in list.data"
                                    :key="item.id"
                                    class="border-b bg-white"
                                >
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ key + list.from }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div
                                            class="text-sm font-semibold text-gray-900"
                                        >
                                            {{ item.name }}
                                        </div>
                                    </td>
                                    <td
                                        class="flex justify-center whitespace-nowrap px-6 py-4"
                                    >
                                        <span
                                            class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold uppercase leading-5 text-green-800"
                                        >
                                            {{ item.type }}
                                        </span>
                                    </td>
                                    <td class="whitespace-normal px-6 py-4">
                                        <div
                                            class="flex items-center justify-center"
                                        >
                                            <a
                                                :href="`/content-types/${item.id}/edit`"
                                                class="mb-2 me-2 rounded-lg bg-yellow-400 px-6 py-1.5 text-sm font-medium uppercase text-white hover:bg-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-300"
                                            >
                                                Edit
                                            </a>
                                            <button
                                                class="mb-2 me-2 rounded-lg bg-red-700 px-6 py-1.5 text-sm font-medium uppercase text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300"
                                                @click="
                                                    openDeleteModal(item.id)
                                                "
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <Pagination :links="list.links" />
        </div>

        <FwbModal v-if="isShowDeleteModal" @close="isShowDeleteModal = false">
            <template #header> Delete Content Type </template>
            <template #body>
                <div class="text-center">
                    Are you sure you want to delete this content type?
                </div>
            </template>
            <template #footer>
                <div class="flex justify-end gap-4">
                    <SecondaryButton
                        color="default"
                        type="button"
                        @click="isShowDeleteModal = false"
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
    </AppLayout>
</template>
