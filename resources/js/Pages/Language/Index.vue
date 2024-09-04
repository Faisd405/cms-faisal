<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import axios from '@/libs/axios'
import { FwbModal } from 'flowbite-vue'
import { onBeforeMount, ref } from 'vue'
import PrimaryButton from '@/Components/Button/PrimaryButton.vue'
import SecondaryButton from '@/Components/Button/SecondaryButton.vue'
import { router } from '@inertiajs/vue3'
import FormModal from '@/Components/Partials/Language/FormModal.vue'

const isShowDeleteModal = ref(false)
const isShowCreateModal = ref(false)
const tempId = ref(null)
const tempData = ref({})
const listContentTypes = ref([])

const props = defineProps({
    list: {
        type: Object,
        default: () => ({})
    },
    contentTypes: {
        type: Array,
        default: () => []
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

const deleteLanguage = () => {
    axios
        .delete(`/localization/languages/${tempId.value}`)
        .then(() => {
            isShowDeleteModal.value = false
            router.reload()
        })
        .catch((err) => {
            console.log(err)
        })
}

onBeforeMount(() => {
    listContentTypes.value = props.contentTypes.map((item) => ({
        value: item.id,
        text: item.name
    }))
})
</script>

<template>
    <AppLayout title="Languages">
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Languages
                </h2>
                <div>
                    <button
                        class="rounded-md border border-slate-800 bg-slate-800 px-6 py-2 uppercase text-pallet-lighten transition duration-300 ease-in-out hover:bg-pallet-lighten hover:text-slate-800 dark:border-slate-800"
                        @click="openCreateModal"
                    >
                        <i class="ion ion-md-add"></i> Add Language
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
                                        ISO Code
                                    </th>
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
                                        Native Name
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-center"
                                    >
                                        Default Language
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
                                            {{ item.iso_code }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ item.name }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ item.native_name }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            <span
                                                v-if="item.is_default"
                                                class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800"
                                            >
                                                Yes
                                            </span>
                                            <span
                                                v-else
                                                class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800"
                                            >
                                                No
                                            </span>
                                        </div>
                                    </td>
                                    <td class="whitespace-normal px-6 py-4">
                                        <div
                                            class="flex items-center justify-center"
                                        >
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
            <template #header> Delete Language </template>
            <template #body>
                <div class="text-center">
                    Are you sure you want to delete this Language?
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
                        @click="deleteLanguage"
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
            :update-data="tempData"
            @close="isShowCreateModal = false"
        />
    </AppLayout>
</template>
