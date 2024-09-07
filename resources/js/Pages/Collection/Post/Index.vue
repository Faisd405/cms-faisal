<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import axios from '@/libs/axios'
import { FwbModal } from 'flowbite-vue'
import { ref } from 'vue'
import PrimaryButton from '@/Components/Button/PrimaryButton.vue'
import SecondaryButton from '@/Components/Button/SecondaryButton.vue'
import { router } from '@inertiajs/vue3'
import FormModal from '@/Components/Partials/Collection/FormModal.vue'

const isShowDeleteModal = ref(false)
const isShowCreateModal = ref(false)
const tempId = ref(null)
const tempData = ref({})

const props = defineProps({
    list: {
        type: Object,
        default: () => ({})
    },
    section: {
        type: Object,
        default: () => ({})
    }
})

const openDeleteModal = (id) => {
    isShowDeleteModal.value = true
    tempId.value = JSON.parse(JSON.stringify(id))
}

const openUpdateModal = (data) => {
    isShowCreateModal.value = true
    tempId.value = JSON.parse(JSON.stringify(data.id))
    tempData.value = JSON.parse(JSON.stringify(data))
}

const openCreateModal = () => {
    isShowCreateModal.value = true
    tempId.value = null
    tempData.value = {}
}

const deletePost = () => {
    axios
        .delete(
            `/collection/sections/${props.section.id}/posts/${tempId.value}`
        )
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
    <AppLayout title="Posts">
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Posts
                </h2>
                <div>
                    <button
                        class="rounded-md border border-slate-800 bg-slate-800 px-6 py-2 uppercase text-pallet-lighten transition duration-300 ease-in-out hover:bg-pallet-lighten hover:text-slate-800 dark:border-slate-800"
                        @click="openCreateModal"
                    >
                        <i class="ion ion-md-add"></i> Add Post
                    </button>
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
                                        Title
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-center"
                                    >
                                        Slug
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
                                            {{ item.title }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ item.slug }}
                                        </div>
                                    </td>
                                    <td class="whitespace-normal px-6 py-4">
                                        <div
                                            class="flex items-center justify-center"
                                        >
                                            <a
                                                :href="`/collection/sections/${item.section_id}/posts/${item.id}/edit`"
                                                class="mb-2 me-2 rounded-lg bg-blue-500 px-6 py-1.5 text-sm font-medium uppercase text-white hover:bg-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-300"
                                            >
                                                Update Content
                                            </a>
                                            <button
                                                class="mb-2 me-2 rounded-lg bg-yellow-400 px-6 py-1.5 text-sm font-medium uppercase text-white hover:bg-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-300"
                                                @click="openUpdateModal(item)"
                                            >
                                                Edit
                                            </button>
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
            <template #header> Delete Post </template>
            <template #body>
                <div class="text-center">
                    Are you sure you want to delete this Post?
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
                        @click="deletePost"
                    >
                        Delete
                    </PrimaryButton>
                </div>
            </template>
        </FwbModal>

        <FormModal
            :list-content-types="listContentTypes"
            :is-show-create-modal="isShowCreateModal"
            :update-id="tempId"
            :section-id="section.id"
            :update-data="tempData"
            @close="isShowCreateModal = false"
        />
    </AppLayout>
</template>
