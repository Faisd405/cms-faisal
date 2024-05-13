<template>
    <div>
        <!-- Sidebar backdrop (mobile only) -->
        <div
            class="fixed inset-0 z-40 bg-slate-900 bg-opacity-30 transition-opacity duration-200 lg:z-auto lg:hidden"
            :class="
                sidebarOpen ? 'opacity-100' : 'pointer-events-none opacity-0'
            "
            aria-hidden="true"
        ></div>

        <!-- Sidebar -->
        <div
            id="sidebar"
            ref="sidebar"
            class="no-scrollbar absolute left-0 top-0 z-40 flex h-screen w-64 shrink-0 flex-col overflow-y-scroll bg-slate-800 p-4 transition-all duration-200 ease-in-out lg:static lg:left-auto lg:top-auto lg:w-20 lg:translate-x-0 lg:overflow-y-auto lg:sidebar-expanded:!w-64 2xl:!w-64"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-64'"
        >
            <!-- Sidebar header -->
            <div class="mb-10 flex items-center justify-between pr-3 sm:px-2">
                <!-- Close button -->
                <button
                    ref="trigger"
                    class="text-slate-500 hover:text-slate-400 lg:hidden"
                    aria-controls="sidebar"
                    :aria-expanded="sidebarOpen"
                    @click.stop="$emit('close-sidebar')"
                >
                    <span class="sr-only">Close sidebar</span>
                    <svg
                        class="h-6 w-6 fill-current"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z"
                        />
                    </svg>
                </button>
                <!-- Logo -->
                <button class="block" to="/">
                    <svg width="32" height="32" viewBox="0 0 32 32">
                        <defs>
                            <linearGradient
                                id="logo-a"
                                x1="28.538%"
                                y1="20.229%"
                                x2="100%"
                                y2="108.156%"
                            >
                                <stop
                                    stop-color="#A5B4FC"
                                    stop-opacity="0"
                                    offset="0%"
                                />
                                <stop stop-color="#A5B4FC" offset="100%" />
                            </linearGradient>
                            <linearGradient
                                id="logo-b"
                                x1="88.638%"
                                y1="29.267%"
                                x2="22.42%"
                                y2="100%"
                            >
                                <stop
                                    stop-color="#38BDF8"
                                    stop-opacity="0"
                                    offset="0%"
                                />
                                <stop stop-color="#38BDF8" offset="100%" />
                            </linearGradient>
                        </defs>
                        <rect fill="#6366F1" width="32" height="32" rx="16" />
                        <path
                            d="M18.277.16C26.035 1.267 32 7.938 32 16c0 8.837-7.163 16-16 16a15.937 15.937 0 01-10.426-3.863L18.277.161z"
                            fill="#4F46E5"
                        />
                        <path
                            d="M7.404 2.503l18.339 26.19A15.93 15.93 0 0116 32C7.163 32 0 24.837 0 16 0 10.327 2.952 5.344 7.404 2.503z"
                            fill="url(#logo-a)"
                        />
                        <path
                            d="M2.223 24.14L29.777 7.86A15.926 15.926 0 0132 16c0 8.837-7.163 16-16 16-5.864 0-10.991-3.154-13.777-7.86z"
                            fill="url(#logo-b)"
                        />
                    </svg>
                </button>

                <div
                    class="duration-400 mx-1 hidden justify-end transition"
                    :class="sidebarExpanded ? 'lg:flex' : 'lg:hidden'"
                >
                    <h4 class="text-end text-sm font-medium text-slate-200">
                        CMS Headless Isal
                    </h4>
                </div>
            </div>

            <!-- Links -->
            <div class="space-y-8">
                <!-- Pages group -->
                <div>
                    <sidenav-header> Pages </sidenav-header>
                    <ul class="mb-3 flex-col space-y-4">
                        <sidenav-menu-link
                            icon="ion ion-md-home"
                            :href="route('dashboard')"
                        >
                            Home
                        </sidenav-menu-link>
                    </ul>

                    <sidenav-header> Data Master </sidenav-header>
                    <ul class="mb-3 flex-col space-y-4">
                        <sidenav-menu-link
                            icon="ion ion-md-home"
                            :href="route('content-types.index')"
                        >
                            Content Types
                        </sidenav-menu-link>
                    </ul>
                    <ul class="mb-3 flex-col space-y-4">
                        <sidenav-menu-link
                            icon="ion ion-md-home"
                            :href="route('pages.index')"
                        >
                            Page
                        </sidenav-menu-link>
                    </ul>

                    <ul class="mb-3 flex-col space-y-4"></ul>
                </div>
            </div>

            <!-- Expand / collapse button -->
            <div
                class="mt-auto hidden justify-end pt-3 lg:inline-flex 2xl:hidden"
            >
                <div class="px-3 py-2">
                    <button @click.prevent="sidebarExpanded = !sidebarExpanded">
                        <span class="sr-only">Expand / collapse sidebar</span>
                        <svg
                            class="h-6 w-6 fill-current sidebar-expanded:rotate-180"
                            viewBox="0 0 24 24"
                        >
                            <path
                                class="text-slate-400"
                                d="M19.586 11l-5-5L16 4.586 23.414 12 16 19.414 14.586 18l5-5H7v-2z"
                            />
                            <path class="text-slate-600" d="M3 23H1V1h2z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, onUnmounted, watch } from 'vue'
import SidenavMenuLink from '../components/SidenavMenuLink.vue'
import SidenavHeader from '../components/SidenavHeader.vue'

export default {
    components: {
        SidenavMenuLink,
        SidenavHeader
    },
    props: {
        sidebarOpen: {
            type: Boolean,
            default: false
        }
    },
    emits: ['close-sidebar'],
    setup(props, { emit }) {
        const trigger = ref(null)
        const sidebar = ref(null)

        const storedSidebarExpanded = localStorage.getItem('sidebar-expanded')
        const sidebarExpanded = ref(
            storedSidebarExpanded === null
                ? false
                : storedSidebarExpanded === 'true'
        )

        const currentRoute = ref(null)

        // close on click outside
        const clickHandler = ({ target }) => {
            if (!sidebar.value || !trigger.value) return
            if (
                !props.sidebarOpen ||
                sidebar.value.contains(target) ||
                trigger.value.contains(target)
            )
                return
            emit('close-sidebar')
        }

        // close if the esc key is pressed
        const keyHandler = ({ keyCode }) => {
            if (!props.sidebarOpen || keyCode !== 27) return
            emit('close-sidebar')
        }

        onMounted(() => {
            document.addEventListener('click', clickHandler)
            document.addEventListener('keydown', keyHandler)
        })

        onUnmounted(() => {
            document.removeEventListener('click', clickHandler)
            document.removeEventListener('keydown', keyHandler)
        })

        watch(sidebarExpanded, () => {
            localStorage.setItem('sidebar-expanded', sidebarExpanded.value)
            if (sidebarExpanded.value) {
                document.querySelector('body').classList.add('sidebar-expanded')
            } else {
                document
                    .querySelector('body')
                    .classList.remove('sidebar-expanded')
            }
        })

        return {
            trigger,
            sidebar,
            sidebarExpanded,
            currentRoute
        }
    }
}
</script>
