<script setup lang="ts">
import { Users } from 'lucide-vue-next';

defineOptions({ inheritAttrs: false });
</script>

<template>
    <div class="flex items-center gap-4 rounded-lg bg-card px-6 py-3 shadow-sm">
        <div class="rounded-full p-3" :class="$attrs.class">
            <Users class="h-6 w-6" />
        </div>
        <div class="flex w-full flex-row items-center justify-around">
            <p class="text-sm font-medium text-muted-foreground">
                <slot name="text"></slot>
            </p>
            <p class="text-2xl font-bold text-foreground">
                <slot name="value"></slot>
            </p>
        </div>
    </div>
</template>
