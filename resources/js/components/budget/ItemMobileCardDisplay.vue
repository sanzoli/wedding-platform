<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import type { BudgetItem } from '@/types';
import { formatAmount, kebabize } from '@/composables/useFormat';
import { Pencil, Trash2 } from 'lucide-vue-next';
import IconButton from '@/components/IconButton.vue';
import { Card } from '@/components/ui/card';
import { router } from '@inertiajs/vue3';
import { destroy } from '@/actions/App/Http/Controllers/BudgetItemsController';
import { confirmDelete } from '@/composables/useModal';

const props = defineProps<{
    item: BudgetItem;
}>();

const deleteItem = () =>
    confirmDelete(() =>
        router.delete(destroy(props.item.id), { only: ['items'] }),
    );
</script>

<template>
    <Card class="py-1">
        <div class="px-4 py-3">
            <!-- Row 1: name + actions -->
            <div class="flex items-center justify-between gap-2">
                <p class="truncate text-[15px] font-medium text-foreground">
                    {{ item.name }}
                </p>
                <div class="flex shrink-0 items-center gap-1.5">
                    <IconButton
                        @click="$emit('edit')"
                        class="hover:bg-primary/10 hover:text-primary"
                    >
                        <Pencil></Pencil>
                    </IconButton>
                    <IconButton
                        @click="deleteItem"
                        class="hover:bg-destructive/10 hover:text-destructive"
                    >
                        <Trash2></Trash2>
                    </IconButton>
                </div>
            </div>

            <!-- Row 2: badge + amount -->
            <div class="mt-2 flex items-center justify-between gap-2">
                <Badge
                    v-if="item.importance"
                    :class="
                        'badge-' + kebabize(item.importance) + '-importance'
                    "
                    class="h-[20px] rounded-full px-[9px] py-0 text-[11px]"
                >
                    {{ item.importance }}
                </Badge>
                <span v-else class="text-xs text-muted-foreground">—</span>
                <p
                    class="text-[16px] font-semibold tracking-tight text-foreground tabular-nums"
                >
                    {{ formatAmount(item.expected_amount) }}
                </p>
            </div>
        </div>
    </Card>
</template>
