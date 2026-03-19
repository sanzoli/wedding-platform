<script setup lang="ts">
interface Props {
    ariaLabel?: string;
}

withDefaults(defineProps<Props>(), {
    ariaLabel: 'Action',
});
</script>

<template>
    <button
        class="fixed right-5 bottom-6 z-40 flex h-13 w-13 items-center justify-center rounded-full bg-primary text-primary-foreground shadow-lg transition-transform active:scale-95"
        :aria-label="ariaLabel"
        v-bind="$attrs"
    >
        <slot />
    </button>
</template>
