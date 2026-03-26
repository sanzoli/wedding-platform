<script setup lang="ts">
import type { BudgetItem } from '@/types';
import { Badge } from '@/components/ui/badge';
import { formatAmount, kebabize } from '@/composables/useFormat';
import { Pencil, Trash2 } from 'lucide-vue-next';
import IconButton from '@/components/IconButton.vue';
import { destroy } from '@/actions/App/Http/Controllers/BudgetItemsController';
import { router } from '@inertiajs/vue3';
import { confirmDelete } from '@/composables/useModal';

const props = defineProps<{
    item: BudgetItem;
}>();

const deleteItem = () => confirmDelete(
    () => router.delete(destroy(props.item.id), { only: ['items'] })
);
</script>

<template>
    <tr class="group transition-colors hover:bg-muted/30">
        <td class="w-[300px] px-6 py-3 text-[15px] font-medium">
            {{ item.name }}
        </td>
        <td class="px-6 py-3">
            <Badge
                v-if="item.importance"
                :class="'badge-' + kebabize(item.importance) + '-importance'"
                class="h-[22px] rounded-full px-[10px] py-0 text-[12px]"
            >
                {{ item.importance }}
            </Badge>
            <span v-else class="text-xs text-muted-foreground">—</span>
        </td>
        <td
            class="px-6 py-3 text-right text-[15px] tracking-tight text-foreground tabular-nums"
        >
            {{ formatAmount(item.expected_amount) }}
        </td>
        <td class="px-6 py-3">
            <div class="flex items-center gap-1.5">
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
        </td>
    </tr>
</template>
