<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { Search, X } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    modelValue: string;
    placeholder?: string;
    class?: HTMLAttributes['class'];
}>();

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const hasValue = computed(() => props.modelValue.length > 0);

const clearSearch = () => {
    emit('update:modelValue', '');
};
</script>

<template>
    <div class="relative" :class="props.class">
        <Search
            class="pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-muted-foreground"
            :size="15"
            :stroke-width="2"
        />
        <input
            :value="modelValue"
            @input="emit('update:modelValue', ($event.target as HTMLInputElement).value)"
            type="search"
            :placeholder="placeholder ?? 'Search…'"
            class="h-[38px] w-full rounded-[10px] border border-border/60 bg-card pl-8 pr-8 text-[14px] text-foreground placeholder:text-muted-foreground shadow-none transition-colors outline-none focus:border-ring focus:ring-2 focus:ring-ring/20 dark:bg-input/30 [&::-webkit-search-cancel-button]:hidden [&::-webkit-search-decoration]:hidden"
        />
        <button
            v-if="hasValue"
            @click="clearSearch"
            class="absolute top-1/2 right-3 -translate-y-1/2 text-muted-foreground hover:text-foreground transition-colors cursor-pointer"
            type="button"
        >
            <X :size="14" :stroke-width="2" />
        </button>
    </div>
</template>
