<script setup lang="ts">
import SearchInput from '@/components/ui/search-input/SearchInput.vue';
import { Plus } from 'lucide-vue-next';

interface Props {
    searchQuery: string;
    isAddingItem: boolean;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    'update:searchQuery': [value: string];
    'start-add-item': [];
    'cancel-add-item': [];
}>();
</script>

<template>
    <section
        class="sticky top-24 z-30 -mx-6 mb-2 bg-background/95 px-6 py-4 backdrop-blur-sm transition-shadow duration-200"
    >
        <div class="flex items-center justify-between gap-3">
            <!-- Search: both desktop and mobile -->
            <SearchInput
                :model-value="searchQuery"
                @update:model-value="emit('update:searchQuery', $event)"
                placeholder="Search items…"
                class="w-full sm:max-w-xs"
            />
            <!-- Add item: desktop only -->
            <button
                v-if="!isAddingItem"
                @click="emit('start-add-item')"
                class="hidden shrink-0 items-center gap-2 rounded-[10px] bg-primary px-4 py-2 text-[14px] font-medium text-primary-foreground transition-colors hover:bg-primary/90 sm:inline-flex"
            >
                <Plus :size="15" :stroke-width="2" />
                Add item
            </button>
        </div>
    </section>
</template>
