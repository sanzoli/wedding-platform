<script setup lang="ts">
import EmptyStateCard from '@/components/budget/EmptyStateCard.vue';
import { Button } from '@/components/ui/button';
import { Plus } from 'lucide-vue-next';

interface Props {
    variant: 'desktop' | 'mobile' | 'search-desktop' | 'search-mobile';
}

defineProps<Props>();

const emit = defineEmits<{
    addItem: [];
}>();
</script>

<template>
    <!-- Desktop: rendered inside a <td colspan="4"> by the parent -->
    <template v-if="variant === 'desktop'">
        <div class="flex flex-col items-center gap-4">
            <Button @click="emit('addItem')">
                <Plus :size="16" :stroke-width="2" />
                Add your first item
            </Button>
            <div class="space-y-2">
                <p class="text-sm font-medium text-foreground/80">
                    No budget items yet
                </p>
                <p class="text-xs text-muted-foreground">
                    Start building your dream wedding
                </p>
            </div>
        </div>
    </template>

    <!-- Search: desktop (rendered inside a <td>) -->
    <template v-else-if="variant === 'search-desktop'">
        <div class="flex flex-col items-center gap-3">
            <p class="text-sm font-medium text-foreground/80">
                No items match your search
            </p>
            <p class="text-xs text-muted-foreground">
                Try adjusting your search terms
            </p>
        </div>
    </template>

    <!-- Search: mobile -->
    <template v-else-if="variant === 'search-mobile'">
        <EmptyStateCard
            title="No items match your search"
            description="Try adjusting your search terms"
        />
    </template>

    <!-- Mobile: self-contained card -->
    <template v-else-if="variant === 'mobile'">
        <EmptyStateCard
            title="No budget items yet"
            description="Tap + to add your first item and start building your dream wedding"
        />
    </template>
</template>
