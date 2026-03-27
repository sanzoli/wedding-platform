<script setup lang="ts">
import IconButton from '@/components/IconButton.vue';
import { Badge } from '@/components/ui/badge';
import { Card } from '@/components/ui/card';
import { formatAmount, kebabize } from '@/composables/useFormat';
import type { BudgetItem } from '@/types';
import { Pencil, Trash2 } from 'lucide-vue-next';

defineEmits(['edit', 'delete']);
defineProps<{
    item: BudgetItem;
}>();

const getBadgeClass = (importance: string) =>
    'badge-' + kebabize(importance) + '-importance';
</script>

<template>
    <tr class="group hidden transition-colors hover:bg-muted/30 md:table-row">
        <td class="w-[300px] px-6 py-3 text-[15px] font-medium">
            {{ item.name }}
        </td>
        <td class="px-6 py-3">
            <Badge
                v-if="item.importance"
                :class="getBadgeClass(item.importance)"
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
                    @click="$emit('delete')"
                    class="hover:bg-destructive/10 hover:text-destructive"
                >
                    <Trash2></Trash2>
                </IconButton>
            </div>
        </td>
    </tr>

    <!--mobile-->
    <Card class="py-1 sm:hidden">
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
                        @click="$emit('delete')"
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
                    :class="getBadgeClass(item.importance)"
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
