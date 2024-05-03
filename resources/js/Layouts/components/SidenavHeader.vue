<template>
    <h3 class="mb-3 pl-3 text-xs font-semibold uppercase text-slate-500">
        <span
            class="lg:sidebar-expanded:hidden hidden w-6 text-center lg:block 2xl:hidden"
            aria-hidden="true"
        >
            •••
        </span>
        <span class="lg:sidebar-expanded:block lg:hidden 2xl:block">
            <slot></slot>
        </span>
    </h3>
</template>
