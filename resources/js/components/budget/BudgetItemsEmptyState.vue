<script setup lang="ts">
import { Plus } from 'lucide-vue-next';

interface Props {
    variant: 'desktop' | 'mobile';
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
            <button
                @click="emit('addItem')"
                class="inline-flex items-center gap-2 rounded-[10px] bg-primary px-5 py-2.5 text-[14px] font-medium text-primary-foreground transition-colors hover:bg-primary/90"
            >
                <Plus :size="16" :stroke-width="2" />
                Add your first item
            </button>
            <div class="space-y-2">
                <p class="text-sm font-medium text-foreground/80">No budget items yet</p>
                <p class="text-xs text-muted-foreground">Start building your dream wedding</p>
            </div>
        </div>
    </template>

    <!-- Mobile: self-contained card -->
    <template v-else>
        <div class="rounded-xl border border-border bg-card px-4 py-10 text-center shadow-sm">
            <div class="flex flex-col items-center gap-3">
                <p class="text-sm font-medium text-foreground/80">No budget items yet</p>
                <p class="text-xs text-muted-foreground">
                    Tap + to add your first item and start building your dream wedding
                </p>
            </div>
        </div>
    </template>
</template>
