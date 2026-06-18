<script setup lang="ts">
import { Users } from 'lucide-vue-next';
</script>

<template>
    <div
        class="shadow-smdark:bg-gray-800 flex items-center gap-4 rounded-lg bg-white px-6 py-3"
    >
        <div class="rounded-full p-3" :class="$attrs.class">
            <Users class="h-6 w-6" />
        </div>
        <div class="flex w-full flex-row items-center justify-around">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                <slot name="text"></slot>
            </p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                <slot name="value"></slot>
            </p>
        </div>
    </div>
</template>
