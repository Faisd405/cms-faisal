<template>
    <div class="relative inline-flex">
        <button
            ref="trigger"
            class="btn border-slate-200 bg-white text-slate-500 hover:border-slate-300 hover:text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:border-slate-600 dark:hover:text-slate-300"
            aria-haspopup="true"
            :aria-expanded="dropdownOpen"
            @click.prevent="dropdownOpen = !dropdownOpen"
        >
            <span class="sr-only">Filter</span><wbr />
            <svg class="h-4 w-4 fill-current" viewBox="0 0 16 16">
                <path
                    d="M9 15H7a1 1 0 010-2h2a1 1 0 010 2zM11 11H5a1 1 0 010-2h6a1 1 0 010 2zM13 7H3a1 1 0 010-2h10a1 1 0 010 2zM15 3H1a1 1 0 010-2h14a1 1 0 010 2z"
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
                class="absolute left-0 right-auto top-full z-10 mt-1 min-w-56 origin-top-right overflow-hidden rounded border border-slate-200 bg-white pt-1.5 shadow-lg dark:border-slate-700 dark:bg-slate-800"
                :class="
                    align === 'right'
                        ? 'md:left-auto md:right-0'
                        : 'md:left-0 md:right-auto'
                "
            >
                <div ref="dropdown">
                    <div
                        class="px-3 pb-2 pt-1.5 text-xs font-semibold uppercase text-slate-400 dark:text-slate-500"
                    >
                        Filters
                    </div>
                    <ul class="mb-4">
                        <li class="px-3 py-1">
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox" />
                                <span class="ml-2 text-sm font-medium"
                                    >Direct VS Indirect</span
                                >
                            </label>
                        </li>
                        <li class="px-3 py-1">
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox" />
                                <span class="ml-2 text-sm font-medium"
                                    >Real Time Value</span
                                >
                            </label>
                        </li>
                        <li class="px-3 py-1">
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox" />
                                <span class="ml-2 text-sm font-medium"
                                    >Top Channels</span
                                >
                            </label>
                        </li>
                        <li class="px-3 py-1">
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox" />
                                <span class="ml-2 text-sm font-medium"
                                    >Sales VS Refunds</span
                                >
                            </label>
                        </li>
                        <li class="px-3 py-1">
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox" />
                                <span class="ml-2 text-sm font-medium"
                                    >Last Order</span
                                >
                            </label>
                        </li>
                        <li class="px-3 py-1">
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox" />
                                <span class="ml-2 text-sm font-medium"
                                    >Total Spent</span
                                >
                            </label>
                        </li>
                    </ul>
                    <div
                        class="border-t border-slate-200 bg-slate-50 px-3 py-2 dark:border-slate-700 dark:bg-slate-700/20"
                    >
                        <ul class="flex items-center justify-between">
                            <li>
                                <button
                                    class="btn-xs border-slate-200 bg-white text-slate-500 hover:border-slate-300 hover:text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-slate-600 dark:hover:text-slate-200"
                                >
                                    Clear
                                </button>
                            </li>
                            <li>
                                <button
                                    class="btn-xs bg-indigo-500 text-white hover:bg-indigo-600"
                                    @click="dropdownOpen = false"
                                    @focusout="dropdownOpen = false"
                                >
                                    Apply
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue'

export default {
    name: 'DropdownFilter',
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
