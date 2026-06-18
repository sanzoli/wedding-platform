<script setup lang="ts">
import { Search, X } from 'lucide-vue-next';

defineProps({
    searchValue: String,
    placeholder: {
        type: String,
        default: 'Search...',
    },
});

defineEmits(['update:searchValue']);
</script>

<template>
    <div class="relative mt-1 w-full sm:max-w-xs">
        <Search
            class="pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-muted-foreground"
            :size="15"
            :stroke-width="2"
        />
        <input
            type="search"
            :placeholder="placeholder"
            :value="searchValue"
            @input="
                $emit(
                    'update:searchValue',
                    ($event.target as HTMLInputElement).value,
                )
            "
            class="h-9.5 w-full rounded-[10px] border border-border/60 bg-card pr-8 pl-8 text-[14px] text-foreground shadow-none transition-colors outline-none placeholder:text-muted-foreground focus:border-ring focus:ring-2 focus:ring-ring/20 dark:bg-input/30 [&::-webkit-search-cancel-button]:hidden [&::-webkit-search-decoration]:hidden"
        />
        <button
            v-show="searchValue"
            class="absolute top-1/2 right-3 -translate-y-1/2 cursor-pointer text-muted-foreground transition-colors hover:text-foreground"
            type="button"
            @click="$emit('update:searchValue', undefined)"
            aria-label="cancel-search"
        >
            <X :size="14" :stroke-width="2" />
        </button>
    </div>
</template>
