<template>
    <div>
        <button
            ref="trigger"
            class="rounded-full"
            :class="
                dropdownOpen
                    ? 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400'
                    : 'text-slate-400 hover:text-slate-500 dark:text-slate-500 dark:hover:text-slate-400'
            "
            aria-haspopup="true"
            :aria-expanded="dropdownOpen"
            @click.prevent="dropdownOpen = !dropdownOpen"
        >
            <span class="sr-only">Menu</span>
            <svg class="h-8 w-8 fill-current" viewBox="0 0 32 32">
                <circle cx="16" cy="16" r="2" />
                <circle cx="10" cy="16" r="2" />
                <circle cx="22" cy="16" r="2" />
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
                class="absolute top-full z-10 mt-1 min-w-36 origin-top-right overflow-hidden rounded border border-slate-200 bg-white py-1.5 shadow-lg dark:border-slate-700 dark:bg-slate-800"
                :class="align === 'right' ? 'right-0' : 'left-0'"
            >
                <ul
                    ref="dropdown"
                    @focusin="dropdownOpen = true"
                    @focusout="dropdownOpen = false"
                >
                    <slot />
                </ul>
            </div>
        </transition>
    </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue'

export default {
    name: 'DropdownEditMenu',
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
