<template>
    <nav class="border-b border-gray-100 bg-white">
        <!-- Primary Navigation Menu -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 justify-between">
                <div class="flex">
                    <button
                        v-if="isUseSidebarToggle"
                        class="text-slate-500 hover:text-slate-600 lg:hidden"
                        aria-controls="sidebar"
                        :aria-expanded="sidebarOpen"
                        @click.stop="$emit('toggle-sidebar')"
                    >
                        <span class="sr-only">Open sidebar</span>
                        <svg
                            class="h-6 w-6 fill-current"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <rect x="4" y="5" width="16" height="2" />
                            <rect x="4" y="11" width="16" height="2" />
                            <rect x="4" y="17" width="16" height="2" />
                        </svg>
                    </button>
                </div>

                <div class="hidden sm:ms-6 sm:flex sm:items-center">
                    <div class="relative ms-3">
                        <!-- Teams Dropdown -->
                        <Dropdown
                            v-if="$page.props.jetstream.hasTeamFeatures"
                            align="right"
                            width="60"
                        >
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:bg-gray-50 focus:outline-none active:bg-gray-50"
                                    >
                                        {{
                                            $page.props.auth.user.current_team
                                                .name
                                        }}

                                        <svg
                                            class="-me-0.5 ms-2 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                                            />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div
                                        class="block px-4 py-2 text-xs text-gray-400"
                                    >
                                        Manage Team
                                    </div>

                                    <!-- Team Settings -->
                                    <DropdownLink
                                        :href="
                                            route(
                                                'teams.show',
                                                $page.props.auth.user
                                                    .current_team
                                            )
                                        "
                                    >
                                        Team Settings
                                    </DropdownLink>

                                    <DropdownLink
                                        v-if="
                                            $page.props.jetstream.canCreateTeams
                                        "
                                        :href="route('teams.create')"
                                    >
                                        Create New Team
                                    </DropdownLink>

                                    <!-- Team Switcher -->
                                    <template
                                        v-if="
                                            $page.props.auth.user.all_teams
                                                .length > 1
                                        "
                                    >
                                        <div class="border-t border-gray-200" />

                                        <div
                                            class="block px-4 py-2 text-xs text-gray-400"
                                        >
                                            Switch Teams
                                        </div>

                                        <template
                                            v-for="team in $page.props.auth.user
                                                .all_teams"
                                            :key="team.id"
                                        >
                                            <form
                                                @submit.prevent="
                                                    switchToTeam(team)
                                                "
                                            >
                                                <DropdownLink as="button">
                                                    <div
                                                        class="flex items-center"
                                                    >
                                                        <svg
                                                            v-if="
                                                                team.id ==
                                                                $page.props.auth
                                                                    .user
                                                                    .current_team_id
                                                            "
                                                            class="me-2 h-5 w-5 text-green-400"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke-width="1.5"
                                                            stroke="currentColor"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                            />
                                                        </svg>

                                                        <div>
                                                            {{ team.name }}
                                                        </div>
                                                    </div>
                                                </DropdownLink>
                                            </form>
                                        </template>
                                    </template>
                                </div>
                            </template>
                        </Dropdown>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="relative ms-3">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button
                                    v-if="
                                        $page.props.jetstream
                                            .managesProfilePhotos
                                    "
                                    class="flex rounded-full border-2 border-transparent text-sm transition focus:border-gray-300 focus:outline-none"
                                >
                                    <img
                                        class="h-8 w-8 rounded-full object-cover"
                                        :src="
                                            $page.props.auth.user
                                                .profile_photo_url
                                        "
                                        :alt="$page.props.auth.user.name"
                                    />
                                </button>

                                <span v-else class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:bg-gray-50 focus:outline-none active:bg-gray-50"
                                    >
                                        {{ $page.props.auth.user.name }}

                                        <svg
                                            class="-me-0.5 ms-2 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                                            />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <!-- Account Management -->
                                <div
                                    class="block px-4 py-2 text-xs text-gray-400"
                                >
                                    Manage Account
                                </div>

                                <DropdownLink :href="route('profile.show')">
                                    Profile
                                </DropdownLink>

                                <DropdownLink
                                    v-if="$page.props.jetstream.hasApiFeatures"
                                    :href="route('api-tokens.index')"
                                >
                                    API Tokens
                                </DropdownLink>

                                <div class="border-t border-gray-200" />

                                <!-- Authentication -->
                                <form @submit.prevent="logout">
                                    <DropdownLink as="button">
                                        Log Out
                                    </DropdownLink>
                                </form>
                            </template>
                        </Dropdown>
                    </div>
                </div>

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button
                        class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                        @click="
                            showingNavigationDropdown =
                                !showingNavigationDropdown
                        "
                    >
                        <svg
                            class="h-6 w-6"
                            stroke="currentColor"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <path
                                :class="{
                                    hidden: showingNavigationDropdown,
                                    'inline-flex': !showingNavigationDropdown
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                            <path
                                :class="{
                                    hidden: !showingNavigationDropdown,
                                    'inline-flex': showingNavigationDropdown
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div
            :class="{
                block: showingNavigationDropdown,
                hidden: !showingNavigationDropdown
            }"
            class="sm:hidden"
        >
            <div class="space-y-1 pb-3 pt-2">
                <ResponsiveNavLink
                    :href="route('dashboard')"
                    :active="route().current('dashboard')"
                >
                    Dashboard
                </ResponsiveNavLink>
            </div>

            <!-- Responsive Settings Options -->
            <div class="border-t border-gray-200 pb-1 pt-4">
                <div class="flex items-center px-4">
                    <div
                        v-if="$page.props.jetstream.managesProfilePhotos"
                        class="me-3 shrink-0"
                    >
                        <img
                            class="h-10 w-10 rounded-full object-cover"
                            :src="$page.props.auth.user.profile_photo_url"
                            :alt="$page.props.auth.user.name"
                        />
                    </div>

                    <div>
                        <div class="text-base font-medium text-gray-800">
                            {{ $page.props.auth.user.name }}
                        </div>
                        <div class="text-sm font-medium text-gray-500">
                            {{ $page.props.auth.user.email }}
                        </div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <ResponsiveNavLink
                        :href="route('profile.show')"
                        :active="route().current('profile.show')"
                    >
                        Profile
                    </ResponsiveNavLink>

                    <ResponsiveNavLink
                        v-if="$page.props.jetstream.hasApiFeatures"
                        :href="route('api-tokens.index')"
                        :active="route().current('api-tokens.index')"
                    >
                        API Tokens
                    </ResponsiveNavLink>

                    <!-- Authentication -->
                    <form method="POST" @submit.prevent="logout">
                        <ResponsiveNavLink as="button">
                            Log Out
                        </ResponsiveNavLink>
                    </form>

                    <!-- Team Management -->
                    <template v-if="$page.props.jetstream.hasTeamFeatures">
                        <div class="border-t border-gray-200" />

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            Manage Team
                        </div>

                        <!-- Team Settings -->
                        <ResponsiveNavLink
                            :href="
                                route(
                                    'teams.show',
                                    $page.props.auth.user.current_team
                                )
                            "
                            :active="route().current('teams.show')"
                        >
                            Team Settings
                        </ResponsiveNavLink>

                        <ResponsiveNavLink
                            v-if="$page.props.jetstream.canCreateTeams"
                            :href="route('teams.create')"
                            :active="route().current('teams.create')"
                        >
                            Create New Team
                        </ResponsiveNavLink>

                        <!-- Team Switcher -->
                        <template
                            v-if="$page.props.auth.user.all_teams.length > 1"
                        >
                            <div class="border-t border-gray-200" />

                            <div class="block px-4 py-2 text-xs text-gray-400">
                                Switch Teams
                            </div>

                            <template
                                v-for="team in $page.props.auth.user.all_teams"
                                :key="team.id"
                            >
                                <form @submit.prevent="switchToTeam(team)">
                                    <ResponsiveNavLink as="button">
                                        <div class="flex items-center">
                                            <svg
                                                v-if="
                                                    team.id ==
                                                    $page.props.auth.user
                                                        .current_team_id
                                                "
                                                class="me-2 h-5 w-5 text-green-400"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            <div>
                                                {{ team.name }}
                                            </div>
                                        </div>
                                    </ResponsiveNavLink>
                                </form>
                            </template>
                        </template>
                    </template>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'

defineProps({
    isUseSidebarToggle: {
        type: Boolean,
        default: true
    },
    sidebarOpen: {
        type: Boolean,
        default: false
    }
})

defineEmits(['toggle-sidebar'])

const showingNavigationDropdown = ref(false)

const switchToTeam = (team) => {
    router.put(
        route('current-team.update'),
        {
            team_id: team.id
        },
        {
            preserveState: false
        }
    )
}

const logout = () => {
    router.post(route('logout'))
}
</script>
