<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Plus } from 'lucide-vue-next';

interface Props {
    variant: 'desktop' | 'mobile' | 'search-desktop' | 'search-mobile';
}

const props = defineProps<Props>();

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

    <!-- Search results (desktop y mobile) -->
    <template
        v-else-if="variant === 'search-desktop' || variant === 'search-mobile'"
    >
        <div
            :class="
                variant === 'search-desktop'
                    ? 'flex flex-col items-center gap-3'
                    : 'rounded-xl border border-border bg-card px-4 py-10 text-center shadow-sm'
            "
        >
            <div class="flex flex-col items-center gap-3">
                <p class="text-sm font-medium text-foreground/80">
                    No items match your search
                </p>
                <p class="text-xs text-muted-foreground">
                    Try adjusting your search terms
                </p>
            </div>
        </div>
    </template>

    <!-- Mobile: self-contained card -->
    <template v-else-if="variant === 'mobile'">
        <div
            class="rounded-xl border border-border bg-card px-4 py-10 text-center shadow-sm"
        >
            <div class="flex flex-col items-center gap-3">
                <p class="text-sm font-medium text-foreground/80">
                    No budget items yet
                </p>
                <p class="text-xs text-muted-foreground">
                    Tap + to add your first item and start building your dream
                    wedding
                </p>
            </div>
        </div>
    </template>
</template>
