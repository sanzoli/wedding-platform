<script setup lang="ts">
import { Button } from '@/components/ui/button';
import SearchInput from '@/components/ui/search-input/SearchInput.vue';
import { Plus } from 'lucide-vue-next';

interface Props {
    searchQuery: string;
    isAddingItem: boolean;
}

defineProps<Props>();

const emit = defineEmits<{
    'update:searchQuery': [value: string];
    'start-add-item': [];
}>();
</script>

<template>
    <section
        class="sticky top-24 z-30 -mx-6 mb-1 bg-background/95 px-6 py-3 backdrop-blur-sm transition-shadow duration-200 sm:mb-2 sm:py-4"
    >
        <div class="flex items-center justify-between gap-3">
            <!-- Search: both desktop and mobile -->
            <SearchInput
                :model-value="searchQuery"
                @update:model-value="emit('update:searchQuery', $event)"
                placeholder="Search items…"
                class="mt-1 w-full sm:max-w-xs"
            />
            <!-- Add item: desktop only -->
            <Button
                v-if="!isAddingItem"
                @click="emit('start-add-item')"
                class="hidden sm:inline-flex"
            >
                <Plus :size="15" :stroke-width="2" />
                Add item
            </Button>
        </div>
    </section>
</template>
