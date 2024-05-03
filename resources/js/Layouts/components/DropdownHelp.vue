<template>
    <div class="relative inline-flex">
        <button
            ref="trigger"
            class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600/80"
            :class="{ 'bg-slate-200': dropdownOpen }"
            aria-haspopup="true"
            :aria-expanded="dropdownOpen"
            @click.prevent="dropdownOpen = !dropdownOpen"
        >
            <span class="sr-only">Info</span>
            <svg
                class="h-4 w-4"
                viewBox="0 0 16 16"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    class="fill-current text-slate-500 dark:text-slate-400"
                    d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm0 12c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1zm1-3H7V4h2v5z"
                />
            </svg>
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
                class="absolute top-full z-10 mt-1 min-w-44 origin-top-right overflow-hidden rounded border border-slate-200 bg-white py-1.5 shadow-lg dark:border-slate-700 dark:bg-slate-800"
                :class="align === 'right' ? 'right-0' : 'left-0'"
            >
                <div
                    class="px-3 pb-2 pt-1.5 text-xs font-semibold uppercase text-slate-400 dark:text-slate-500"
                >
                    Need help?
                </div>
                <ul
                    ref="dropdown"
                    @focusin="dropdownOpen = true"
                    @focusout="dropdownOpen = false"
                >
                    <li>
                        <button
                            class="flex items-center px-3 py-1 text-sm font-medium text-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400"
                            @click="dropdownOpen = false"
                        >
                            <svg
                                class="mr-2 h-3 w-3 shrink-0 fill-current text-indigo-300 dark:text-indigo-500"
                                viewBox="0 0 12 12"
                            >
                                <rect y="3" width="12" height="9" rx="1" />
                                <path d="M2 0h8v2H2z" />
                            </svg>
                            <span>Documentation</span>
                        </button>
                    </li>
                    <li>
                        <button
                            class="flex items-center px-3 py-1 text-sm font-medium text-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400"
                            @click="dropdownOpen = false"
                        >
                            <svg
                                class="mr-2 h-3 w-3 shrink-0 fill-current text-indigo-300 dark:text-indigo-500"
                                viewBox="0 0 12 12"
                            >
                                <path
                                    d="M10.5 0h-9A1.5 1.5 0 000 1.5v9A1.5 1.5 0 001.5 12h9a1.5 1.5 0 001.5-1.5v-9A1.5 1.5 0 0010.5 0zM10 7L8.207 5.207l-3 3-1.414-1.414 3-3L5 2h5v5z"
                                />
                            </svg>
                            <span>Support Site</span>
                        </button>
                    </li>
                    <li>
                        <button
                            class="flex items-center px-3 py-1 text-sm font-medium text-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400"
                            @click="dropdownOpen = false"
                        >
                            <svg
                                class="mr-2 h-3 w-3 shrink-0 fill-current text-indigo-300 dark:text-indigo-500"
                                viewBox="0 0 12 12"
                            >
                                <path
                                    d="M11.854.146a.5.5 0 00-.525-.116l-11 4a.5.5 0 00-.015.934l4.8 1.921 1.921 4.8A.5.5 0 007.5 12h.008a.5.5 0 00.462-.329l4-11a.5.5 0 00-.116-.525z"
                                />
                            </svg>
                            <span>Contact us</span>
                        </button>
                    </li>
                </ul>
            </div>
        </transition>
    </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'

export default {
    name: 'DropdownHelp',
    props: {
        align: {
            type: String,
            default: 'left'
        }
    },
    setup() {
        const dropdownOpen = ref(false)
        const trigger = ref(null)
        const dropdown = ref(null)

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

        onMounted(() => {
            document.addEventListener('click', clickHandler)
            document.addEventListener('keydown', keyHandler)
        })

        onUnmounted(() => {
            document.removeEventListener('click', clickHandler)
            document.removeEventListener('keydown', keyHandler)
        })

        return {
            dropdownOpen,
            trigger,
            dropdown
        }
    }
}
</script>
