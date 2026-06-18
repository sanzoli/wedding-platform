<script setup lang="ts">
import { SortOptions } from '@/types';
import { ChevronDown, ChevronUp } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    sortBy?: string;
    sortOptions?: SortOptions;
}>();

const isCurrentSorting = computed(
    () => props.sortOptions?.type === props.sortBy,
);

const nextDirection = computed(function () {
    if (!isCurrentSorting.value || !props.sortOptions?.direction) {
        return 'asc';
    }

    return props.sortOptions.direction === 'asc' ? 'desc' : null;
});
</script>

<template>
    <th class="admin-type-table-header px-6 py-5.5 text-muted-foreground">
        <button
            v-if="sortBy"
            type="button"
            class="inline-flex items-center gap-1.5 transition-colors hover:text-foreground"
            @click="$emit('update:sortOptions', sortBy, nextDirection)"
        >
            <span><slot /></span>

            <span
                v-if="!isCurrentSorting || !sortOptions?.direction"
                class="flex flex-col leading-none"
            >
                <ChevronUp :size="12" class="-mb-1 opacity-30" />
                <ChevronDown :size="12" class="opacity-30" />
            </span>
            <ChevronUp
                v-else-if="sortOptions?.direction === 'asc'"
                :size="13"
                class="opacity-80"
            />
            <ChevronDown
                v-else-if="sortOptions?.direction === 'desc'"
                :size="13"
                class="opacity-80"
            />
        </button>

        <slot v-else />
    </th>
</template>
