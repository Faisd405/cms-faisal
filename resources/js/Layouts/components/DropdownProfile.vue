<template>
    <div class="relative inline-flex">
        <button
            ref="trigger"
            class="group inline-flex items-center justify-center"
            aria-haspopup="true"
            :aria-expanded="dropdownOpen"
            @click.prevent="dropdownOpen = !dropdownOpen"
        >
            <!-- <img class="w-8 h-8 rounded-full" :src="UserAvatar" width="32" height="32" alt="User" /> -->
            <div class="flex items-center truncate">
                <span
                    class="ml-2 truncate text-sm font-medium group-hover:text-slate-800 dark:text-slate-300 dark:group-hover:text-slate-200"
                >
                    {{ userAuth.name }}
                </span>
                <svg
                    class="ml-1 h-3 w-3 shrink-0 fill-current text-slate-400"
                    viewBox="0 0 12 12"
                >
                    <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                </svg>
            </div>
        </button>
        <transition
            enter-active-class="transition ease-out duration-200 transform"
            enter-from-class="opacity-0 -translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-out duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-show="dropdownOpen"
                class="absolute top-full z-10 mt-1 min-w-44 origin-top-right overflow-hidden rounded border border-slate-200 bg-white px-2 py-4 shadow-lg dark:border-slate-700 dark:bg-slate-800"
            >
                <div
                    class="mb-1 border-b border-slate-200 px-3 pb-2 pt-0.5 dark:border-slate-700"
                >
                    <div class="font-medium text-slate-800 dark:text-slate-100">
                        {{ userAuth.name }}
                    </div>
                    <div
                        class="text-xs italic text-slate-500 dark:text-slate-400"
                    >
                        {{ userAuth.roles.map((role) => role.name).join(', ') }}
                    </div>
                </div>
                <ul
                    ref="dropdown"
                    @focusin="dropdownOpen = true"
                    @focusout="dropdownOpen = false"
                >
                    <li>
                        <button
                            class="flex items-center px-3 py-1 text-sm font-medium text-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400"
                            to="/signin"
                            @click="logout"
                        >
                            Sign Out
                        </button>
                    </li>
                </ul>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useAuthStore } from '@/store/authStore'
import { router } from '@inertiajs/vue3'
const authStore = useAuthStore()

const dropdownOpen = ref(false)
const trigger = ref(null)
const dropdown = ref(null)

const userAuth = authStore.user

// close on click outside
const clickHandler = ({ target }) => {
    if (
        !dropdownOpen.value ||
        dropdown.value.contains(target) ||
        trigger.value.contains(target)
    )
        return
    dropdownOpen.value = false
}

// close if the esc key is pressed
const keyHandler = ({ keyCode }) => {
    if (!dropdownOpen.value || keyCode !== 27) return
    dropdownOpen.value = false
}

const logout = async () => {
    try {
        await authStore.logout()

        dropdownOpen.value = false

        router.post(route('logout'))
    } catch (error) {
        console.log(error)
    }
}

onMounted(() => {
    document.addEventListener('click', clickHandler)
    document.addEventListener('keydown', keyHandler)
})

onUnmounted(() => {
    document.removeEventListener('click', clickHandler)
    document.removeEventListener('keydown', keyHandler)
})
</script>
