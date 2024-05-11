<template>
    <h3 class="mb-3 pl-3 text-xs font-semibold uppercase text-slate-500">
        <span
            class="hidden w-6 text-center lg:block lg:sidebar-expanded:hidden 2xl:hidden"
            aria-hidden="true"
        >
            •••
        </span>
        <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">
            <slot></slot>
        </span>
    </h3>
</template>
